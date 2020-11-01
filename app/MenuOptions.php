<?php

namespace AllCommerce;


use AllCommerce\Clients;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class MenuOptions extends Model
{
    use Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name', 'type', 'route','page_shown', 'menu_name', 'is_submenu',
        'permitted_role', 'active','order','icon', 'onclick', 'is_host_user',
        'client_id', 'persist'
    ];

    protected $casts = [
        'id' => 'uuid'
    ];

    protected $icons = [
        'cash-register' => 'fad fa-cash-register',
        'mobile' => 'fad fa-mobile',
        'anchor' => 'fad fa-anchor'
    ];

    protected $sidebar_bp = 'c-sidebar-nav-icon';

    public static function getOptions($type, $menu_name, $page_shown = 'any', string $role = '')
    {
        $results = [];

        $records = self::whereType($type)->whereMenuName($menu_name);

        if(!empty($role))
        {
            $records = $records->wherePermittedRole($role);
        }

        if($page_shown == 'any')
        {
            $records = $records->wherePageShown('any');
        }
        else
        {
            $records = $records->whereIn('page_shown', ['any', $page_shown]);

        }

        if(count($records = $records->whereActive(1)->orderBy('order', 'ASC')->get()) > 0)
        {
            //dd(session()->has('active_client'));
            $curated = [];
            foreach($records as $idx => $record)
            {
                $user_and_record_is_qualified_to_persist_across_clients = backpack_user()->isHostUser() && ($record->persist == 1);
                $active_client = session()->has('active_client') ? session()->get('active_client') : Clients::getHostClient();
                $record_is_scoped_to_active_client = ($active_client == $record->client_id);
                if($user_and_record_is_qualified_to_persist_across_clients || $record_is_scoped_to_active_client)
                {
                    $permitted_role = $record->permitted_role;
                    $user_is_permitted_role = Bouncer::is(backpack_user())->a($permitted_role);
                    if(($permitted_role == 'any') || $user_is_permitted_role)
                    {
                        $is_a_host_only_option = ($record->is_host_user == 1);
                        if($is_a_host_only_option)
                        {
                            if(backpack_user()->isHostUser())
                            {
                                $curated[] = $record;
                            }
                        }
                        else
                        {
                            $curated[] = $record;
                        }
                    }
                }
            }

            $results = collect($curated);
        }

        return $results;
    }

    public function getOrCreate($link, $client_id)
    {
        $results = false;

        switch($link)
        {
            case 'checkout-funnels':
                $results = $this->gcCheckoutFunnels($client_id);
                break;

            case 'payment-gateways':
                $results = $this->gcPaymentGateways($client_id);
                break;

            case 'sms':
                $results = $this->gcSMS($client_id);
                break;

            case 'switch':
                $results = $this->gcAdminSwitch($client_id);
                break;
        }

        return $results;
    }

    private function gcAdminSwitch($client_id)
    {
        $results = false;

        $client = Clients::find($client_id);

        $menu_count = $this->whereMenuName('Clients')
            ->whereActive(1)
            ->get();

        if(!is_null($client))
        {
            $prev_menu = $this->whereMenuName('Clients')
                ->whereRoute('/switch/'.$client_id)
                ->first();

            if(!is_null($prev_menu))
            {
                $prev_menu->name = $client->name;
                $prev_menu->active = $client->active;
                $prev_menu->save();
                $results = $prev_menu;
            }
            else
            {
                $payload = [
                    'name' => $client->name,
                    'type' => 'sidebar',
                    'route' => '/switch/'.$client_id,
                    'page_shown' => 'any',
                    'menu_name' => 'Clients',
                    'is_submenu' => 0,
                    'permitted_role' => 'any',
                    'active' => 1,
                    'order' => intval(count($menu_count) + 1),
                    'icon' => "{$this->icons['anchor']} {$this->sidebar_bp}",
                    'is_host_user' => 0,
                    'client_id' => Clients::getHostClient(),
                    'ability' => null,
                    'persist' => 1
                ];

                $record = $this->firstOrCreate($payload);

                if(!is_null($record))
                {
                    $results = $record;
                }
            }
        }

        return $results;
    }

    private function gcCheckoutFunnels($client_id)
    {
        $results = false;

        $payload = [
            'name' => 'Checkout Funnels',
            'type' => 'sidebar',
            'route' => '/access/shop/checkout-funnels',
            'page_shown' => 'any',
            'menu_name' => 'Navigation',
            'is_submenu' => 0,
            'permitted_role' => 'executive',
            'active' => 1,
            'order' => 4,
            'icon' => "{$this->icons['cash-register']} {$this->sidebar_bp}",
            'is_host_user' => 0,
            'client_id' => $client_id,
            'ability' => null,
            'persist' => 1
        ];

        $record = $this->firstOrCreate($payload);

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    private function gcPaymentGateways($client_id)
    {
        $results = false;

        $payload = [
            'name' => 'Payment Gateways',
            'type' => 'sidebar',
            'route' => '/access/payment-gateways',
            'page_shown' => 'any',
            'menu_name' => 'Navigation',
            'is_submenu' => 0,
            'permitted_role' => 'executive',
            'active' => 1,
            'order' => 2,
            'icon' => "{$this->icons['cash-register']} {$this->sidebar_bp}",
            'is_host_user' => 0,
            'client_id' => $client_id,
            'ability' => null,
            'persist' => 1
        ];

        $record = $this->firstOrCreate($payload);

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

    private function gcSMS($client_id)
    {
        $results = false;

        $payload = [
            'name' => 'SMS',
            'type' => 'sidebar',
            'route' => '/access/sms-manager',
            'page_shown' => 'any',
            'menu_name' => 'Navigation',
            'is_submenu' => 0,
            'permitted_role' => 'executive',
            'active' => 1,
            'order' => 4,
            'icon' => "{$this->icons['mobile']} {$this->sidebar_bp}",
            'is_host_user' => 0,
            'client_id' => $client_id,
            'ability' => null,
            'persist' => 1
        ];

        $record = $this->firstOrCreate($payload);

        if(!is_null($record))
        {
            $results = $record;
        }

        return $results;
    }

}
