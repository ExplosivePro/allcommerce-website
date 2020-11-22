<?php

namespace Database\Seeders\MenuOptions;

use App\Models\Utility\SidebarOptions;
use Illuminate\Database\Seeder;

class MenuOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mapping = [
            ['name' => 'Manage',            'route' => '/access/user',              'menu_name' => 'Users',           'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   1, 'icon' => 'la la-user-tie'],
            ['name' => 'Manage',            'route' => '/access/clients',           'menu_name' => 'Clients',         'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   1, 'icon' => 'la la-user-tie'],
            ['name' => 'Manage',            'route' => '/access/merchants',         'menu_name' => 'Merchants',       'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   1, 'icon' => 'las la-industry'],
            ['name' => 'Manage',            'route' => '/access/shops',             'menu_name' => 'Shops',           'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   1, 'icon' => 'fad fa-store-alt'],
            ['name' => 'Users',             'route' => '',                          'menu_name' => 'Admin',           'is_submenu' => 1, 'permitted_role' => 'admin',      'order' =>   1, 'icon' => 'la la-user-nurse'],
            ['name' => 'Clients',           'route' => '',                          'menu_name' => 'Admin',           'is_submenu' => 1, 'permitted_role' => 'admin',      'order' =>   2, 'icon' => 'fad fa-user-tie'],
            ['name' => 'Utility',           'route' => '',                          'menu_name' => 'Admin',           'is_submenu' => 1, 'permitted_role' => 'admin',      'order' =>   3, 'icon' => 'las la-tools'],
            ['name' => 'Shops',             'route' => '',                          'menu_name' => 'Shops',           'is_submenu' => 1, 'permitted_role' => 'client',     'order' =>   2, 'icon' => 'fad fa-cash-register'],
            ['name' => 'Merchants',         'route' => '',                          'menu_name' => 'Merchants',       'is_submenu' => 1, 'permitted_role' => 'client',     'order' =>   1, 'icon' => 'fad fa-piggy-bank'],
            ['name' => 'New User',          'route' => '/access/user/create',       'menu_name' => 'Users',           'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   1, 'icon' => 'la la-user-plus'],
            ['name' => 'New Client',        'route' => '/access/clients/create',    'menu_name' => 'Clients',         'is_submenu' => 0, 'permitted_role' => 'admin',      'order' => 999, 'icon' => 'la la-plus-circle'],
            ['name' => 'New Merchant',      'route' => '/access/merchants/create',  'menu_name' => 'Merchants',       'is_submenu' => 0, 'permitted_role' => 'client',     'order' => 999, 'icon' => 'la la-plus-square'],
            ['name' => 'New Shop',          'route' => '/access/shops/create',      'menu_name' => 'Shops',           'is_submenu' => 0, 'permitted_role' => 'client',     'order' => 999, 'icon' => 'la la-plus-square'],
            ['name' => 'Icons',             'route' => '/access/iconsset',          'menu_name' => 'Utility',         'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   1, 'icon' => 'las la-icons'],
            ['name' => 'Payment Providers', 'route' => '/access/payment-gateways',  'menu_name' => 'Utility',         'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   2, 'icon' => 'fad fa-cash-register'],
            ['name' => 'SMS Providers',     'route' => '/access/sms-providers',     'menu_name' => 'Utility',         'is_submenu' => 0, 'permitted_role' => 'admin',      'order' =>   3, 'icon' => 'fad fa-mobile-android-alt'],

            ['name' => 'Features',          'route' => '',                          'menu_name' => 'Features',        'is_submenu' => 1, 'permitted_role' => 'client',     'order' =>   3, 'icon' => 'fad fa-backpack'],
            ['name' => 'Checkout Funnels',  'route' => '/access/checkout-funnels',  'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   3, 'icon' => 'fad fa-funnel-dollar'],
            ['name' => 'Payment Gateways',  'route' => '/access/payment-gateways',  'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   1, 'icon' => 'fad fa-cash-register'],
            ['name' => 'SMS Providers',     'route' => '/access/sms-providers',     'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   2, 'icon' => 'fad fa-mobile-android-alt'],
            ['name' => 'Customers',         'route' => '/access/customers',         'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   4, 'icon' => 'fad fa-user-tag'],
            ['name' => 'Orders',            'route' => '/access/orders',            'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   5, 'icon' => 'fad fa-shopping-cart'],
            ['name' => 'Checkout Themes',   'route' => '/access/checkout-themes',   'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   6, 'icon' => 'fad fa-paint-brush'],
            ['name' => 'Checkout Plugin',   'route' => '/access/checkout-plugin',   'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   7, 'icon' => 'fad fa-plug'],
            ['name' => 'Sales Reports',     'route' => '/access/sales-reports',     'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   8, 'icon' => 'fad fa-file-chart-pie'],
            ['name' => 'Products',          'route' => '/access/product-catalog',   'menu_name' => 'Features',        'is_submenu' => 0, 'permitted_role' => 'client',     'order' =>   9, 'icon' => 'fad fa-box-full'],
        ];

        foreach($mapping as $map)
        {
            $map['page_shown'] = 'all';
            $map['active'] = 1;
            $map['icon'] .= ' nav-icon';

            SidebarOptions::firstOrCreate($map);
        }
    }
}
