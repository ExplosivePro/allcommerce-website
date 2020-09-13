<?php

namespace AnchorCMS\Actions\Widgets;

use AnchorCMS\Clients;
use AnchorCMS\Widgets;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade as Bouncer;

class GetWidgetsForUser
{
    protected $clients, $widgets;

    public function __construct(Clients $clients, Widgets $widgets)
    {
        $this->clients = $clients;
        $this->widgets = $widgets;
    }

    public function execute($page)
    {
        $results = [];

        try
        {
            $client = $this->clients->find(backpack_user()->client_id);

            if(backpack_user()->isHostUser())
            {
                if(session()->has('active_client'))
                {
                    $client = $this->clients->find(session()->get('active_client'));
                }
            }

            $available_widgets = [];
            $preferred_widgets = [];

            // If god, get all widgets as available
            if(backpack_user()->isHostUser() && Bouncer::is(backpack_user())->a('god'))
            {
                $widgets = $this->widgets->whereClientId($client->id)
                    ->whereActive(1)->wherePage($page)->get();

                if(count($widgets) > 0)
                {
                    // Get all scoped if active_client is toggled
                    if(session()->has('active_client'))
                    {
                        $client = $this->clients->find(session()->get('active_client'));
                        $widgets = $widgets->where('client_id', '=', $client->id);
                    }
                }

                if(count($widgets) > 0)
                {
                    $available_widgets = array_values($widgets->toArray());

                    // getPreferredWidgets
                    $client_id = session()->has('active_client') ? $client->id : null;
                    $preferred_widgets = $this->widgets->specific_user_preferred(backpack_user()->id, $page, $client_id);


                    // no scoping for gods.
                    $results = [
                        'available' => $available_widgets,
                        'default' => ($preferred_widgets) ? $preferred_widgets->toArray() : [collect($available_widgets)->first()]
                    ];
                }
            }
            else
            {
                if(backpack_user()->isHostUser())
                {
                    // get all role_assigned_widgets as available, followed by user_assigned_widgets as also_available
                    $roles = backpack_user()->getRoles();
                    $client_id = session()->has('active_client') ? session()->get('active_client') : backpack_user()->client_id;

                    $widgets = $this->widgets->whereActive(1)
                        ->whereClientId($client_id)
                        ->wherePage($page);

                    foreach ($roles as $idx => $role)
                    {
                        if($idx == 0)
                        {
                            $widgets = $widgets->where(function ($query) use ($role) {
                                $query->where('allowed_roles', 'LIKE', "%$role%");
                            });
                        }
                        else
                        {
                            $widgets = $widgets->where(function ($query) use ($role) {
                                $query->orWhere('allowed_roles', 'LIKE', "%$role%");
                            });
                        }
                    }

                    $widgets = $widgets->get();

                    if(count($widgets) > 0)
                    {
                        foreach($widgets as $idx => $widget)
                        {
                            if(backpack_user()->cannot($widget->allowed_abilities, $widget))
                            {
                                unset($widgets[$idx]);
                            }
                        }

                        // Get all scoped if active_client is toggled
                        if(session()->has('active_client'))
                        {
                            $client = $this->clients->find(session()->get('active_client'));
                            $widgets = $widgets->where('client_id', '=', $client->id);
                        }
                    }

                    // get all user_preferred_widgets as default
                    if(count($widgets) > 0)
                    {
                        $available_widgets = array_values($widgets->toArray());

                        // getPreferredWidgets
                        $client_id = session()->has('active_client') ? $client->id : null;
                        $preferred_widgets = $this->widgets->specific_user_preferred(backpack_user()->id, $page, $client_id);


                        // no scoping for gods.
                        $results = [
                            'available' => $available_widgets,
                            'default' => ($preferred_widgets) ? $preferred_widgets->toArray() : [collect($available_widgets)->first()]
                        ];
                    }
                }
                else
                {
                    $widgets = $this->widgets
                        ->whereClientId($client->id)
                        ->whereActive(1)
                        ->wherePage($page)
                        ->get();

                    if(count($widgets) > 0)
                    {
                        foreach ($widgets as $idx => $widget)
                        {
                            $access_granted = false;
                            $allowed_roles = str_getcsv($widget->allowed_roles,',');
                            $allowed_abilities = str_getcsv($widget->allowed_abilities,',');

                            foreach ($allowed_roles as $role)
                            {
                                if($access_granted = Bouncer::is(backpack_user())->a($role))
                                {
                                    break;
                                }
                            }

                            if(!$access_granted)
                            {
                                foreach ($allowed_abilities as $ability)
                                {
                                    if((!is_null($ability)))
                                    {
                                        if($access_granted = backpack_user()->can($ability, $widget))
                                        {
                                            break;
                                        }
                                    }
                                }
                            }

                            if(!$access_granted)
                            {
                                unset($widgets[$idx]);
                            }
                            else
                            {
                                $available_widgets[] = $widget;
                            }
                        }

                        $preferred_widgets = $this->widgets->specific_user_preferred(backpack_user()->id, $page, $client->id);

                        $results = [
                            'available' => $available_widgets,
                            'default' => ($preferred_widgets) ? $preferred_widgets->toArray() : [collect($available_widgets)->first()]
                        ];
                    }
                }
            }

            if(count($results['default']) == 1 && (is_null($results['default'][0])))
            {
                unset($results['default'][0]);
            }
        }
        catch(\Exception $e) {
            // @todo - Derp ?
            $results = [];
        }

        return $results;
    }
}
