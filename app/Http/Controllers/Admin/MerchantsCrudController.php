<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Aggregates\Merchants\MerchantConfigAggregate;
use AllCommerce\Clients;
use AllCommerce\Jobs\OnBoarding\NewMerchantOnboarding;
use AllCommerce\Jobs\OnBoarding\UpdateClientOnboarding;
use AllCommerce\Jobs\OnBoarding\UpdateMerchantOnboarding;
use Backpack\CRUD\CrudPanel;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AllCommerce\Http\Requests\StandardStoreRequest as StoreRequest;
use AllCommerce\Http\Requests\StandardUpdateRequest as UpdateRequest;

/**
 * Class MerchantsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MerchantsCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'manage-merchants';

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AllCommerce\Merchants');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/manage-merchants');
        $this->crud->setEntityNameStrings('Merchant', 'Manage Merchants');

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

        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Merchant Name", // the human-readable label for it
            'type' => 'text', // the kind of column to show
            'attributes' => [
                'required' => 'required',
            ]
        ];

        $active = [
            'name' => 'active', // the db column name (attribute name)
            'label' => "Active?", // the human-readable label for it
            'type' => 'boolean' // the kind of column to show
        ];

        $client = [
            'name' => 'client.name',
            'label' => 'Client',
            'type' => 'text'
        ];

        $add_role_client_select = [
            'name' => (!backpack_user()->isHostUser()) ? 'client.id' : 'client_id',
            'label' => 'Assign a Client',
            'type' => 'select2_from_array',
            'options' => Clients::getAllClientsDropList(),
            'default' => backpack_user()->client_id,
            'attributes' => [
                'required' => 'required',
            ]
        ];

        if(!backpack_user()->isHostUser())
        {
            $add_role_client_select['attributes']['disabled'] = 'disabled';

            $hidden_client = [
                'name' => 'client_id',
                'type' => 'hidden',
                'value' => backpack_user()->client_id
            ];
        }

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

        $column_defs = [$name, $client, $active];
        $add_edit_defs = [$name, $add_role_client_select,$active];

        if(!backpack_user()->isHostUser())
        {
            $add_edit_defs[] = $hidden_client;
        }

        $this->crud->addColumns($column_defs);
        $this->crud->addFields($add_edit_defs, 'both');
        // add asterisk for fields that are required in RolesRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);

        $entry = $this->crud->entry;

        if(!is_null($entry))
        {
            // execute the on-boarding job
            NewMerchantOnboarding::dispatch($entry)->onQueue('allcommerce-'.env('APP_ENV').'-events');
        }

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);

        $entry = $this->crud->entry;

        if(!is_null($entry))
        {
            UpdateMerchantOnboarding::dispatch($entry)->onQueue('allcommerce-'.env('APP_ENV').'-events');
        }

        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function destroy($id)
    {
        $results = parent::destroy($id);

        MerchantConfigAggregate::retrieve($id)
            ->deleteMerchant($id)
            ->persist();

        return $results;
    }
}
