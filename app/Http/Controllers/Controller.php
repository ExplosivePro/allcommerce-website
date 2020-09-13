<?php

namespace AnchorCMS\Http\Controllers;

use AnchorCMS\MenuOptions;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Ixudra\Curl\Facades\Curl;
use Silber\Bouncer\BouncerFacade as Bouncer;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $menu_options;

    public function __construct()
    {
        $this->menu_options = new MenuOptions();
    }

    public function menu_options()
    {
        return $this->menu_options;
    }

    public function canAccessFeatureAttribute(string $name, string $client_id, string $feature_attr)
    {
        $results = false;

        // See if the client has the kpi feature enabled or fail
        $feature_record = $this->features->getFeature($name, $client_id);

        if($feature_record)
        {
            $access_granted = false;
            $allowed_roles = str_getcsv($feature_record->allowed_roles,',');
            $allowed_abilities = str_getcsv($feature_record->allowed_abilities,',');

            // ACL - See if the user has permissions to view report or fail
            if(!Bouncer::is(backpack_user())->a('god'))
            {
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
                        if($access_granted = backpack_user()->can($ability))
                        {
                            break;
                        }
                    }
                }
            }
            else
            {
                $access_granted = true;
            }

            if($access_granted)
            {
                $feature_attributes = $feature_record->feature_attributes;

                // Get feature and attributes records or fail
                if(count($feature_attributes) > 0)
                {
                    $results = true;
                }
                else
                {
                    $results = 'Client has not finished setting up feature.';
                }
            }
            else
            {
                $results = 'Access Denied. You do not have permissions to view this report.';
            }
        }

        return $results;
    }
}
