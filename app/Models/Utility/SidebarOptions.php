<?php

namespace App\Models\Utility;

use App\Aggregates\Users\UserProfileAggregate;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class SidebarOptions extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['name', 'route', 'page_shown', 'menu_name', 'is_submenu', 'permitted_role',
        'permitted_abilities', 'active', 'order', 'icon'
    ];

    public static function getSidebarOptions()
    {
        $results = [];

        $aggy = UserProfileAggregate::retrieve(backpack_user()->id);
        if($aggy->isRegistered())
        {
            $options = self::whereIsSubmenu(1)
                ->orderBy('order', 'ASC')
                ->get();

            if(count($options) > 0)
            {
                // Organize array
                // Make the options
                foreach ($options as $option)
                {
                    if(Bouncer::is(backpack_user())->a($option->permitted_role))
                    {
                        $results[] = $option;
                    }
                }
            }
        }

        return $results;
    }

    public static function getSubSidebarOptions($menu_name)
    {
        $results = [];

        $options = self::whereMenuName($menu_name)
            ->whereIsSubmenu(0)
            ->orderBy('order', 'ASC')
            ->get();

        if(count($options) > 0)
        {
            // Organize array
            // Make the options
            foreach ($options as $option)
            {
                $results[] = $option;
            }
        }

        return $results;
    }
}
