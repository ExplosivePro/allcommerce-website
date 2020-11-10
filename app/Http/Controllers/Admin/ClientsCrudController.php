<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Jobs\OnBoarding\NewClientOnboarding;
use AllCommerce\Jobs\OnBoarding\UpdateClientOnboarding;
use Backpack\CRUD\CrudPanel;
use Prologue\Alerts\Facades\Alert;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AllCommerce\Http\Requests\RolesRequest as StoreRequest;
use AllCommerce\Http\Requests\RolesRequest as UpdateRequest;

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
        $this->crud->setModel('AllCommerce\Clients');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-clients');
        $this->crud->setEntityNameStrings('Client', 'Clients');

        if(backpack_user()->isHostUser())
        {
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
        else
        {
            $this->crud->hasAccessOrFail('nope');
        }

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        // show a success message
        if($this->crud->entry->id == '0')
        {
            //Re-retrieve the new entry or skip (blah)
            $entry = $this->crud->entry->whereName($this->crud->entry->name)
                ->whereActive($this->crud->entry->active)
                ->whereCreatedAt($this->crud->entry->created_at)
                ->first();
        }
        else
        {
            $entry = $this->crud->entry;
        }

        if(!is_null($entry))
        {
            NewClientOnboarding::dispatch($entry)->onQueue('allcommerce-'.env('APP_ENV').'-events');
        }

        return $redirect_location;//redirect('/crud-clients');
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);

        $entry = $this->crud->entry;

        if(!is_null($entry))
        {
            UpdateClientOnboarding::dispatch($entry)->onQueue('allcommerce-'.env('APP_ENV').'-events');
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;//redirect('/crud-clients');
    }
}
