<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Merchants;
use AllCommerce\ShopTypes;
use Backpack\CRUD\CrudPanel;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use AllCommerce\Http\Requests\StandardStoreRequest as StoreRequest;
use AllCommerce\Http\Requests\StandardUpdateRequest as UpdateRequest;

/**
 * Class ShopsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ShopsCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'manage-shops';

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('AllCommerce\Shops');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/manage-shops');
        $this->crud->setEntityNameStrings('Online Shop', 'Manage Shops');

        $client_id = backpack_user()->client_id;
        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));
                $client_id = session()->get('active_client');
            }
            else
            {
                $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
            }
        }
        else
        {
            $this->crud->addClause('where', 'client_id', '=', backpack_user()->client_id);
        }

        $this->setMerchantClause($client_id);

        $name = [
            'name' => 'name', // the db column name (attribute name)
            'label' => "Shop Name", // the human-readable label for it
            'type' => 'text', // the kind of column to show
            'attributes' => [
                'placeholder' => 'My Shop',
            ]
        ];

        $shop_type_text = [
            'name' => 'shoptype.name', // the db column name (attribute name)
            'label' => "Shop Type", // the human-readable label for it
            'type' => 'text', // the kind of column to show
        ];

        $shop_type = [  // Select
            'label' => "Shop Type",
            'type' => 'select',
            'name' => 'shop_type', // the db column for the foreign key
            'entity' => 'shop_type', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "AllCommerce\ShopTypes",

            // optional
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ];

        $shop_url = [
            'name' => 'shop_url', // the db column name (attribute name)
            'label' => "Shop Url", // the human-readable label for it
            'type' => 'text', // the kind of column to show
            'attributes' => [
                'placeholder' => 'your-store.myshopify.com',
            ]
        ];


        $active = [
            'name' => 'active', // the db column name (attribute name)
            'label' => "Active?", // the human-readable label for it
            'type' => 'boolean' // the kind of column to show
        ];

        $merchant = [  // Select
            'label' => "Merchant",
            'type' => 'select',
            'name' => 'merchant_id', // the db column for the foreign key
            'entity' => 'merchant', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "AllCommerce\Merchants",

            // optional
            'options'   => (function ($query) use ($client_id){
                return $query->whereClientId($client_id)
                    ->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        ];

        if(backpack_user()->isHostUser())
        {
            $client  = [  // Select
                'label' => "Client",
                'type' => 'select',
                'name' => 'client_id', // the db column for the foreign key
                'entity' => 'client', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "AllCommerce\Clients",
                // optional
                'options'   => (function ($query) use ($client_id){
                    return $query->whereId($client_id)
                        ->orderBy('name', 'ASC')->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            ];
        }
        else
        {
            $client  = [  // Select
                'label' => "Client",
                'type' => 'text',
                'name' => 'client_id', // the db column for the foreign key
                'entity' => 'client', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "AllCommerce\Clients",
                'attributes' => [
                    'disabled' => 'disabled',
                ],
            ];
        }

        $column_defs   = [$name, $shop_type_text, $shop_url, $active];
        $add_edit_defs = [$name, $shop_url, $merchant, $client, $shop_type, $active];
        $this->crud->addColumns($column_defs);
        $this->crud->addFields($add_edit_defs, 'both');

        $this->crud->addButtonFromView('line', 'shopify', 'connect-to-shopify', 'last');
        // add asterisk for fields that are required in RolesRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    private function setMerchantClause($client_id)
    {
        if(session()->has('active_merchant'))
        {
            $this->crud->addClause('where', 'merchant_id', '=', session()->get('active_merchant'));
        }
        else
        {
            if(count($merchants = Merchants::clientMerchants($client_id)) > 0)
            {
                $merchant = $merchants->first();
                $this->crud->addClause('where', 'merchant_id', '=', $merchant->id);
            }
            else
            {
                $this->crud->hasAccessOrFail('');
            }
        }
    }

    public function store(StoreRequest $request)
    {
        $redirect_location = parent::storeCrud($request);

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
