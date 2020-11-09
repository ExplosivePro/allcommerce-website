<?php

use AllCommerce\Clients;
use AllCommerce\MenuOptions;
use Illuminate\Database\Seeder;

class MenuOptionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client_id = Clients::getHostClient();

        $this->setNavOptions($client_id);
        $this->setClientOptions($client_id);
        $this->setAdminOptions($client_id);
    }

    private function setNavOptions($client_id) : void
    {
        $nav_options = [
            [
                'name' => 'Clients',
                'type' => 'sidebar',
                'route' => null,
                'page_shown' => 'any',
                'menu_name' => 'Navigation',
                'is_submenu' => 1,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 1,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Manage Merchants',
                'type' => 'sidebar',
                'route' => '/access/manage-merchants',
                'page_shown' => 'any',
                'menu_name' => 'Navigation',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 2,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Manage Shops',
                'type' => 'sidebar',
                'route' => '/access/manage-shops',
                'page_shown' => 'any',
                'menu_name' => 'Navigation',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 3,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
        ];

        foreach ($nav_options as $option)
        {
            $op = MenuOptions::firstOrCreate($option);

            switch($option['name'])
            {
                case 'Clients':
                    $op->icon = 'fad fa-user-tie c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-user-tie c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Manage Merchants':
                    $op->icon = 'fad fa-cash-register c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-cash-register c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Manage Shops':
                    $op->icon = 'fad fa-piggy-bank c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-piggy-bank c-sidebar-nav-icon';
                    $op->save();
                    break;
            }
        }

    }

    private function setClientOptions($client_id) : void
    {
        $client_options = [
            [
                'name' => 'AllCommerce',
                'type' => 'sidebar',
                'route' => '/switch/'.$client_id,
                'page_shown' => 'any',
                'menu_name' => 'Clients',
                'is_submenu' => 0,
                'permitted_role' => 'any',
                'active' => 1,
                'order' => 1,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
        ];

        foreach ($client_options as $option)
        {
            $op = MenuOptions::firstOrCreate($option);

            switch($option['name'])
            {
                case 'AllCommerce':
                    $op->icon = 'fad fa-anchor c-sidebar-nav-icon';
                    $op->save();
            }
        }
    }

    private function setAdminOptions($client_id) : void
    {
        $admin_options = [
            [
                'name' => 'Users',
                'type' => 'sidebar',
                'route' => '/access/crud-users',
                'page_shown' => 'any',
                'menu_name' => 'Admin',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 2,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Access Control',
                'type' => 'sidebar',
                'route' => null,
                'page_shown' => 'any',
                'menu_name' => 'Admin',
                'is_submenu' => 1,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 3,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Manage Clients',
                'type' => 'sidebar',
                'route' => '/access/crud-clients',
                'page_shown' => 'any',
                'menu_name' => 'Access Control',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 1,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Roles',
                'type' => 'sidebar',
                'route' => '/access/crud-roles',
                'page_shown' => 'any',
                'menu_name' => 'Access Control',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 2,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ],
            [
                'name' => 'Abilities',
                'type' => 'sidebar',
                'route' => '/access/crud-abilities',
                'page_shown' => 'any',
                'menu_name' => 'Access Control',
                'is_submenu' => 0,
                'permitted_role' => 'god',
                'active' => 1,
                'order' => 3,
                'is_host_user' => 1,
                'client_id' => $client_id,
                'persist' => 1
            ]
        ];

        foreach ($admin_options as $option)
        {
            $op = MenuOptions::firstOrCreate($option);

            switch($option['name'])
            {
                case 'Users':
                    $op->icon = 'fad fa-user-alien c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-user-alien c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Access Control':
                    $op->icon = 'fad fa-link c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-link c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Manage Clients':
                    $op->icon = 'fad fa-piggy-bank c-sidebar-nav-icon';
                    $op->save();

                    $option['permitted_role'] = 'admin';
                    $op = MenuOptions::firstOrCreate($option);
                    $op->icon = 'fad fa-piggy-bank c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Roles':
                    $op->icon = 'fad fa-link c-sidebar-nav-icon';
                    $op->save();
                    break;

                case 'Abilities':
                    $op->icon = 'fad fa-biking-mountain c-sidebar-nav-icon';
                    $op->save();
                    break;
            }
        }
    }
}
