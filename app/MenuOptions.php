<?php

namespace AnchorCMS;


use AnchorCMS\Clients;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Eloquent\SoftDeletes;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class MenuOptions extends Model
{
    use Uuid, SoftDeletes;

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
}
