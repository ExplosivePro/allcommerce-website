<?php

use AllCommerce\User;
use AllCommerce\Roles;
use AllCommerce\Clients;
use AllCommerce\AssignedRoles;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        echo "Creating AllCommerce Default Admin User\n";
        $client_id = Clients::getHostClient();

        $user = User::firstOrCreate([
            'first_name' => 'AllCommerce',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'developers@capeandbay.com',

            'profile_img' => 'https://png.pngitem.com/pimgs/s/508-5087336_person-man-user-account-profile-employee-profile-template.png',
            'client_id' => $client_id
        ]);

        if(is_null($user->email_verified_at))
        {
            $user->email_verified_at = date('Y-m-d H:i:s', strtotime('now'));
            $user->password = bcrypt('Hello123!');
            $user->save();
        }

        if(!Bouncer::is($user)->a('god'))
        {
            echo 'Assigning Role to new user';

            $god = Roles::whereClientId($client_id)
                ->whereName('god')
                ->first();

            $new_god = Bouncer::role()->find($god->id);
            Bouncer::assign($new_god)->to($user);
            $assigned = AssignedRoles::whereEntityId($user->id)
                ->whereEntityType('AllCommerce\User')->first();

            $assigned2 = AssignedRoles::whereEntityId($user->id)
                ->whereEntityType('AllCommerce\Models\User')->first();

            if(is_null($assigned2))
            {
                $assigned2 = $assigned->replicate();
                $assigned2->entity_type = 'AllCommerce\Models\BackpackUser';
                $assigned2->save();
            }

            $assigned3 = AssignedRoles::whereEntityId($user->id)
                ->whereEntityType('App\User')->first();

            if(is_null($assigned3))
            {
                $assigned3 = $assigned->replicate();
                $assigned3->entity_type = 'App\User';
                $assigned3->save();
            }


        }

        echo ' AllCommerce Admin UUID - '.$user->id."\n";
    }
}
