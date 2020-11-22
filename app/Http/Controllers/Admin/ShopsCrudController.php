<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ShopsRequest;
use App\Models\Merchant;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\ReviseOperation\ReviseOperation;
use Silber\Bouncer\BouncerFacade as Bouncer;

/**
 * Class ShopsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ShopsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use ReviseOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Shops\Shop::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shops');
        CRUD::setEntityNameStrings('shop', 'shops');

        if(Bouncer::is(backpack_user())->an('admin'))
        {
            $this->crud->hasAccessOrFail('nope');
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addClause('whereClientId', backpack_user()->client_id);
        CRUD::column('name')->type('text');
        CRUD::column('shop_url')->label('Shop Link')->type('closure')->function(function($entry) {
            return '<a href="'.$entry->shop_url.'" target="_blank">Click Me</a>';
        });
        CRUD::column('shoptype.name')->type('text')->label('Shop Type');
        CRUD::column('merchant.name')->type('text')->label('Merchant');

        if(Bouncer::is(backpack_user())->an('admin'))
        {
            CRUD::column('merchant_id')->type('text')->entity('client')->attribute('name');
        }

        CRUD::column('active')->type('boolean');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
        $this->crud->addFilter([
            'name'  => 'merchant_id',
            'type'  => 'select2',
            'label' => 'Merchant'
        ], function () {
            $merchants = (Bouncer::is(backpack_user())->an('admin'))
                ? Merchant::all()
                : Merchant::whereClientId(backpack_user()->client_id)->get();

            if(count($merchants) > 0)
            {
                $results = [];

                foreach ($merchants as $merchant)
                {
                    $results[$merchant->id] = $merchant->name;
                }

                return $results;
            }
            else
            {
                return [];
            }

        }, function ($value) { // if the filter is active
             $this->crud->addClause('where', 'client_id', $value);
        });
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ShopsRequest::class);

        CRUD::addField([
            'name' => 'name', // the db column name (attribute name)
            'label' => "Shop Name", // the human-readable label for it
            'type' => 'text', // the kind of column to show
            'attributes' => [
                'placeholder' => 'My Shop',
                'required' => true
            ]
        ]);

        CRUD::addField([  // Select
            'label' => "Shop Type",
            'type' => 'select2',
            'name' => 'shop_type', // the db column for the foreign key
            'entity' => 'shop_type_model', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'model' => "App\Models\Shops\ShopTypes",

            // optional
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        CRUD::addField([
            'name' => 'shop_url', // the db column name (attribute name)
            'label' => "Shop Url", // the human-readable label for it
            'type' => 'url', // the kind of column to show
            'attributes' => [
                'placeholder' => 'your-store.myshopify.com',
                'required' => 'required'
            ],
            'hint' => 'We currently support Independently Managed eShops and Shopify Shops.'
        ]);

        CRUD::addField([  // Select
        'label' => "Merchant",
        'type' => 'select2',
        'name' => 'merchant_id', // the db column for the foreign key
        'entity' => 'merchant', // the method that defines the relationship in your Model
        'attribute' => 'name', // foreign key attribute that is shown to user
        'model' => "App\Models\Merchant",
        'attributes' => [
            'required' => 'required'
        ],

        // optional
        'options'   => (function ($query){
            if(!Bouncer::is(backpack_user())->an('admin'))
            {
                $query = $query->whereClientId(backpack_user()->client_id);
            }
            return $query->orderBy('name', 'ASC')->get();
        }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
    ]);

        if(Bouncer::is(backpack_user())->an('admin'))
        {
            CRUD::addField([  // Select
                'label' => "Client",
                'type' => 'select',
                'name' => 'client_id', // the db column for the foreign key
                'entity' => 'client', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Client",
                // optional
                'options'   => (function ($query) {
                    return $query
                        ->orderBy('name', 'ASC')->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                'attributes' => [
                    'required' => 'required'
                ]
            ]);
        }
        else
        {
            CRUD::addField([  // Select
                'label' => "Account",
                'type' => 'select',
                'name' => 'fake_client_id', // the db column for the foreign key
                'entity' => 'client', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Client",
                'default' => backpack_user()->client_id,
                // optional
                'options'   => (function ($query) {
                    return $query->whereId(backpack_user()->client_id)
                        ->orderBy('name', 'ASC')->get();
                }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                'attributes' => [
                    'required' => true,
                    'disabled' => true
                ]
            ]);

            CRUD::field('client_id')->type('hidden')->value(backpack_user()->client_id);
        }

        CRUD::field('active')->type('boolean');
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
    }
}
