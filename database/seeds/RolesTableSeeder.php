<?php

use AllCommerce\Roles;
use AllCommerce\Clients;
use AllCommerce\Abilities;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $client_id = Clients::getHostClient();
        echo "Creating AllCommerce Abilities\n";
        $abilities = [
            [
                'name' => 'create-abilities',
                'title' => 'Create New Abilities',
                'client_id' => $client_id,
            ],
            [
                'name' => 'edit-abilities',
                'title' => 'Edit Existing Abilities',
                'client_id' => $client_id,
            ],
            [
                'name' => 'delete-abilities',
                'title' => 'Delete Existing Abilities',
                'client_id' => $client_id,
            ],
            [
                'name' => 'create-users',
                'title' => 'Create New Users',
                'client_id' => $client_id,
            ],
            [
                'name' => 'edit-users',
                'title' => 'Edit Existing Users',
                'client_id' => $client_id,
            ],
            [
                'name' => 'delete-users',
                'title' => 'Delete Users',
                'client_id' => $client_id,
            ],
            [
                'name' => 'receive-notifications',
                'title' => 'Receive Notifications',
                'client_id' => $client_id,
            ],
        ];

        echo "Creating AllCommerce Roles\n";
        $god = Roles::firstOrCreate([
            'name' => 'god',
            'title' => 'Developer',
            'client_id' => $client_id
        ]);
        $god = Bouncer::role()->find($god->id);
        echo "God\n";

        $admin = Roles::firstOrCreate([
            'name' => 'admin',
            'title' => 'Site Admin',
            'client_id' => $client_id
        ]);
        $admin = Bouncer::role()->find($admin->id);
        echo "Admin\n";

        echo 'Assigning abilities';
        foreach($abilities as $ability)
        {
            $ab = Abilities::firstOrCreate($ability);
            $do_this_thing = Bouncer::ability()->find($ab->id);

            switch($ab->name)
            {
                case 'delete-users':
                case 'delete-abilities':
                    Bouncer::allow($god)->to($do_this_thing);
                    break;

                default:
                    Bouncer::allow($god)->to($do_this_thing);
                    Bouncer::allow($admin)->to($do_this_thing);
            }


        }
    }
}
