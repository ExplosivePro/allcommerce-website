<?php

namespace AnchorCMS\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class MobileAppCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MobileAppCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-mobile-apps';
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AnchorCMS\MobileApps');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-mobile-apps');
        $this->crud->setEntityNameStrings('Cape & Bay Produced Mobile App', 'Mobile Apps!');

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
            }
        }
        else
        {
            if(backpack_user()->can('manage-mobile-apps'))
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

        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->setFromDb();

        // add asterisk for fields that are required in MobileAppRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
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
