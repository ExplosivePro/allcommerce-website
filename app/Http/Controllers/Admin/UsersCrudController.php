<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Clients;
use AllCommerce\Jobs\User\OnboardNewUser;
use AllCommerce\Jobs\User\UserWasUpdated;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AllCommerce\Roles;
use AllCommerce\Http\Requests\StandardStoreRequest as StoreRequest;
use AllCommerce\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Silber\Bouncer\BouncerFacade as Bouncer;

/**
 * Class UsersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UsersCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-users';
        if(backpack_user()->cannot('create-users'))
        {
            $this->crud->hasAccessOrFail('');
        }

        if(backpack_user()->cannot('edit-users'))
        {
            $this->crud->denyAccess('update');
        }

        if(backpack_user()->cannot('delete-users'))
        {
            $this->crud->denyAccess('delete');
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AllCommerce\User');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-users');
        $this->crud->setEntityNameStrings('AllCommerce User', 'AllCommerce Users');

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }
        }
        else
        {
            $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
        }

        $host_client_uuid = Clients::getHostClient();

        if(backpack_user()->client_id != $host_client_uuid)
        {
            $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $username = [
            'name' => 'username', // the db column name (attribute name)
            'label' => "Username", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $first = [
            'name' => 'first_name', // the db column name (attribute name)
            'label' => "First", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $last = [
            'name' => 'last_name', // the db column name (attribute name)
            'label' => "Last", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $email = [
            'name' => 'email', // the db column name (attribute name)
            'label' => "Email", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $verified = [
            'name' => 'email_verified_at', // the db column name (attribute name)
            'label' => "Completed Registration On", // the human-readable label for it
            'type' => 'text', // the kind of column to show
            'attributes' => [
                'placeholder' => 'Incomplete',
                'class' => 'form-control some-class',
                'readonly'=>'readonly',
                'disabled'=>'disabled',
            ], // change the HTML attributes of your input
        ];

        $roles = [
            'name' => 'role',
            'label' => 'Role(s)',
            'type' => 'closure',
            'function' => function ($entry) {
            $results = '';
                $roles = $entry->getRoles();

                foreach ($roles as $idx => $role_name)
                {
                    $results .= ($idx > 0) ? ',' : '';
                    $results .= Roles::getRoleTitle($role_name);
                }

                return $results;
            }
        ];

        $client = [
            'name' => 'client',
            'label' => 'Client',
            'type' => 'closure',
            'function' => function ($entry) {
                $results = 'None Assigned';
                $client_record = $entry->client()->first();

                if(!is_null($client_record))
                {
                    $results = $client_record->name;
                }

                return $results;
            }
        ];

        $roles_select = [
            'name' => 'roles', // the db column name (attribute name)
            'label' =>'Assigned Role'
        ];

        $client_select = [
            'name' => 'client_id', // the db column name (attribute name)
            'label' => 'Assigned Client',
            'type' => 'select2_from_array',
            'options' => Clients::getAllClientsDropList()
        ];

        $client_d = json_encode(Clients::getAllClientsDropList());
        $user_client_id = '';
        $role = '';
        if(array_key_exists('crud_user', $route = \Route::current()->originalParameters()))
        {
            $user = backpack_user()->find($route['crud_user']);
            if(!is_null($user))
            {
                $user_client_id = $user->client_id;
                $user_roles = $user->getRoles();

                if(count($user_roles) > 0)
                {
                    $role = $user_roles[0];
                }
            }
        }

        $client_and_role_select = [
            'name' => 'client_id2', // the db column name (attribute name)
            'label' => 'Assigned Client',
            'type' => 'custom_html',
            'value' => "<user-client-role-ability-assign
                :clients='".$client_d."'
                client-id='{$user_client_id}'
                role='{$role}'
            ></user-client-role-ability-assign>"
        ];


        $column_defs = [$username, $first, $last, $email,$roles, $client];
        $edit_create_defs = [$username, $first, $last, $email, $client_and_role_select];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($edit_create_defs, 'both');

        $edit_defs = [$verified];
        $this->crud->addFields($edit_defs, 'update');

        // add asterisk for fields that are required in UsersRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $data = $request->all();
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $this->crud->entry->profile_img = 'https://png.pngitem.com/pimgs/s/508-5087336_person-man-user-account-profile-employee-profile-template.png';
        $this->crud->entry->save();

        try {
            Bouncer::assign($data['role'])->to($this->crud->entry);
            Bouncer::assign($data['role'])->to(backpack_user()->find($this->crud->entry->id));
        }
        catch(\Exception $e)
        {
            $this->crud->entry->forceDelete();
            \Alert::error('Action Reversed! You may be missing some user roles for this client.');
        }


        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $data = $request->all();
        $redirect_location = parent::updateCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $roles = $this->crud->entry->getRoles();

        if(count($roles) > 0)
        {
            foreach($roles as $role)
            {
                $this->crud->entry->retract($role);
                backpack_user()->find($this->crud->entry->id)->retract($role);
            }
        }

        if(is_null($this->crud->entry->profile_img))
        {
            $this->crud->entry->profile_img = 'https://png.pngitem.com/pimgs/s/508-5087336_person-man-user-account-profile-employee-profile-template.png';
            $this->crud->entry->save();
        }

        Bouncer::assign($data['role'])->to($this->crud->entry);
        Bouncer::assign($data['role'])->to(backpack_user()->find($this->crud->entry->id));

        UserWasUpdated::dispatch($this->crud->entry, backpack_user())->onQueue('anchor-'.env('APP_ENV').'-emails');

        return $redirect_location;
    }

    /**
     * The search function that is called by the data table.
     *
     * @return array JSON Array of cells in HTML form.
     */
    public function search()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $totalRows = $this->crud->model->count();
        $filteredRows = $this->crud->count();
        $startIndex = $this->request->input('start') ?: 0;
        // if a search term was present
        if ($this->request->input('search') && $this->request->input('search')['value']) {
            // filter the results accordingly
            $this->crud->applySearchTerm($this->request->input('search')['value']);
            // recalculate the number of filtered rows
            $filteredRows = $this->crud->count();
        }
        // start the results according to the datatables pagination
        if ($this->request->input('start')) {
            $this->crud->skip((int) $this->request->input('start'));
        }
        // limit the number of results according to the datatables pagination
        if ($this->request->input('length')) {
            $this->crud->take((int) $this->request->input('length'));
        }
        // overwrite any order set in the setup() method with the datatables order
        if ($this->request->input('order')) {
            $column_number = $this->request->input('order')[0]['column'];
            $column_direction = $this->request->input('order')[0]['dir'];
            $column = $this->crud->findColumnById($column_number);
            if ($column['tableColumn']) {
                // clear any past orderBy rules
                $this->crud->query->getQuery()->orders = null;
                // apply the current orderBy rules
                $this->crud->query->orderBy($column['name'], $column_direction);
            }

            // check for custom order logic in the column definition
            if (isset($column['orderLogic'])) {
                $this->crud->customOrderBy($column, $column_direction);
            }
        }
        $entries = $this->crud->getEntries();

        if(count($entries) > 0)
        {
            $has_all_access = Bouncer::is(backpack_user())->a('god');
            foreach ($entries as $idx => $user)
            {
                // Dev is highest rank, no one can see devs if they aren't one
                $lookup_user_is_a_dev = Bouncer::is($user)->a('god');
                if(!$has_all_access)
                {
                    if($lookup_user_is_a_dev)
                    {
                        unset($entries[$idx]);
                    }
                    else
                    {
                        if((!backpack_user()->isHostUser()))
                        {
                            if($user->isHostUser())
                            {
                                unset($entries[$idx]);
                            }
                            else
                            {
                                // @todo - scope to make sure that client users have trickle down access
                            }
                        }
                    }
                }
            }
        }

        return $this->crud->getEntriesAsJsonForDatatables($entries, $totalRows, $filteredRows, $startIndex);
    }
}
