<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Clients;
use Backpack\CRUD\CrudPanel;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AllCommerce\Http\Requests\RolesRequest as StoreRequest;
use AllCommerce\Http\Requests\RolesRequest as UpdateRequest;

/**
 * Class RolesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AbilitiesCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-abilities';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AllCommerce\Abilities');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-abilities');
        $this->crud->setEntityNameStrings('ability', 'abilities');

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }
        }
        else
        {
            if(backpack_user()->can('create-abilities'))
            {
                $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
            }
            else
            {
                $this->crud->hasAccessOrFail('');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Ability Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];
        $title = [
            'name' => 'title', // the db column name (attribute name)
            'label' => "Ability Title", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $client = [
            'name' => 'client.name',
            'label' => 'Client',
            'type' => 'text'
        ];

        $add_role_client_select = [
            'name' => 'client_id',
            'label' => 'Assign a Client',
            'type' => 'select2_from_array',
            'options' => Clients::getAllClientsDropList()
        ];

        $route = \Route::current()->uri();
        $mode = 'edit';
        if(strpos($route, 'create') !== false)
        {
            $mode = 'create';
        }

        if($mode == 'edit')
        {
            $add_role_client_select['attributes'] = [];
            $add_role_client_select['attributes']['disabled'] = 'disabled';
        }

        $column_defs = [$name, $title, $client];
        $add_edit_defs = [$name, $title, $add_role_client_select];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($add_edit_defs, 'both');
        // add asterisk for fields that are required in RolesRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        if(backpack_user()->can('create-abilities'))
        {
            // your additional operations before save here
            //$redirect_location = parent::storeCrud();
            $new_ability = Bouncer::ability()->firstOrCreate([
                'name' => $request->all()['name'],
                'title' => $request->all()['title']
            ]);

            if($new_ability)
            {
                $new_ability->client_id = $request->all()['client_id'];
                $new_ability->save();

                \Alert::success(trans('backpack::crud.insert_success'))->flash();
            }
            else
            {
                \Alert::error(trans('backpack::crud.insert_fail'))->flash();
            }
            // your additional operations after save here
            // use $this->data['entry'] or $this->crud->entry
            // show a success message
        }
        else
        {
            \Alert::error('Access Denied. You do not have permission to create new abilities.')->flash();
        }


        return redirect('/crud-abilities');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
