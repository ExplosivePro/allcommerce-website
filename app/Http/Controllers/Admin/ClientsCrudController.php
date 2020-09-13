<?php

namespace AnchorCMS\Http\Controllers\Admin;

use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AnchorCMS\Http\Requests\RolesRequest as StoreRequest;
use AnchorCMS\Http\Requests\RolesRequest as UpdateRequest;

/**
 * Class RolesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ClientsCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-clients';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\Clients');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-clients');
        $this->crud->setEntityNameStrings('Client', 'Clients');

        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Client Name", // the human-readable label for it
            'type' => 'text' // the kind of column to show
        ];

        $active = [
            'name' => 'active', // the db column name (attribute name)
            'label' => "Active", // the human-readable label for it
            'type' => 'boolean' // the kind of column to show
        ];

        $active_edit = [
            'name' => 'active', // the db column name (attribute name)
            'label' => "Active", // the human-readable label for it
            'type' => 'checkbox' // the kind of column to show
        ];

        $column_defs = [$name, $active];
        $this->crud->addColumns($column_defs);

        $edit_create_defs = [$name, $active_edit];
        $this->crud->addFields($edit_create_defs, 'both');

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // show a success message

        return $redirect_location;//redirect('/crud-clients');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);


        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;//redirect('/crud-clients');
    }
}
