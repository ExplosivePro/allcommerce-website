<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Aggregates\Users\UserProfileAggregate;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');


    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('where', 'id','!=', backpack_user()->id);

        CRUD::column('name')->type('text');
        CRUD::column('role')->type('closure')->function(function ($entry) {
            return '<span>'.$entry->getRoles()[0].'</span>';
        });
        CRUD::column('email');
        CRUD::column('created_at')->label('Created')->type('closure')->function(function($entry) {
            $date = new \DateTime(date('Y-m-d H:i:s', strtotime($entry->created_at)));
            $date->setTimezone(new \DateTimeZone('America/New_York'));
            return $date->format('M d, Y h:i A');
        });

        CRUD::column('email_verified_at')->label('Registered')->type('closure')->function(function($entry) {
            if(!is_null($entry->email_verified_at))
            {
                $date = new \DateTime(date('Y-m-d H:i:s', strtotime($entry->email_verified_at)));
                $date->setTimezone(new \DateTimeZone('America/New_York'));
                return $date->format('M d, Y h:i A');
            }
            else
            {
                return 'No';
            }
        });
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        $this->crud->denyAccess('show');

        CRUD::addButtonFromView('line', 'Resend Welcome Email', 'resend-email', 'end');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::field('name')->label('Full Name');
        CRUD::field('email');

        $this->crud->addField([  // Select2
            'label'     => "Role",
            'type'      => 'select2_from_array',
            'name'      => 'assigned_role', // the db column for the foreign key
            // also optional
            'options'   => [
                'admin' => 'Administrator',
                'client' => 'Client User'
            ], // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ]);

        CRUD::field('client_id')->type('select2')->label('Client')
            ->entity('client')->model('App\Models\Client')->attribute('name')
            //->options(function ($query) {
            //    return $query->orderBy('name', 'ASC')->get();
            //})
            ->hint('Admins do not get assigned a client.');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        $user_id = $this->crud->getCurrentEntryId();
        $entry = $this->crud->getModel()->find($user_id);
        $default = $entry->getRoles()[0];

        $this->crud->removeField('assigned_role');
        $this->crud->addField([  // Select2
            'label'     => "Role",
            'type'      => 'select2_from_array',
            'name'      => 'assigned_role', // the db column for the foreign key
            'value'   => $default,
            // also optional
            'options'   => [
                'admin' => 'Administrator',
                'guest' => 'Guest User'
            ], // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            'attributes' => [
                'disabled' => 'disabled'
            ]
        ]);
    }

    public function update($id)
    {
        $data = $this->crud->getRequest()->request->all();

        UserProfileAggregate::retrieve($id)
            ->updateUsername($data['name'])
            ->updateEmail($data['email'])
            ->persist();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        return $this->crud->performSaveAction($id);
    }

    public function store()
    {
        $data = $this->crud->getRequest()->all();
        $role = false;
        if(array_key_exists('assigned_role', $data))
        {
            $role = $data['assigned_role'];
            //$this->crud->getRequest()->request->remove('assigned_role');

            if(array_key_exists('client_id', $data))
            {
                if(($role == 'admin') && (!is_null($data['client_id'])))
                {
                    \Alert::warning('Admins do not get assigned a Client.')->flash();
                    return redirect()->back()->withInput();
                }
            }

        }

        $response = $this->traitStore();

        // assign the user the role that was selected
        if($role)
        {
            $user = $this->crud->entry;

            if(!is_null($user))
            {
                $aggy = UserProfileAggregate::retrieve($user->id);

                if($role == 'admin')
                {
                    $aggy = $aggy->assignAdmin();
                }
                else
                {
                    $aggy = $aggy->assignClient();
                }

                $aggy->persist();
            }
        }

        return $response;
    }
}
