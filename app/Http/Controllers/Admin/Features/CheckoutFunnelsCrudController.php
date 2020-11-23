<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Requests\Features\CheckoutFunnelsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\ReviseOperation\ReviseOperation;

/**
 * Class CheckoutFunnelsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CheckoutFunnelsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CheckoutFunnels\CheckoutFunnels::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/checkout-funnels');
        CRUD::setEntityNameStrings('Checkout Funnel', 'Checkout Funnels');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // columns
        $this->crud->addClause('whereClientId', backpack_user()->client()->first()->id);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CheckoutFunnelsRequest::class);

        $this->crud->addField([
            'name' => 'shop_id',
            'type' => 'select2',
            'label' => 'Select a Shop',
            'entity' => 'shop',
            'attribute' => 'name',
            'tab' => 'Funnel Info'
        ]);

        CRUD::field('funnel_name')->type('text')->attributes(['required' => 'required'])
            ->tab('Funnel Info');

        CRUD::field('default')->type('boolean')->label('Make this Funnel the Primary Funnel you See on dashboards and various places?')
            ->tab('Funnel Info');

        CRUD::field('active')->type('boolean')->tab('Funnel Info');

        // Will return products (or nothing) depending on the shop selected
        CRUD::field('attributes[product_items]')->type('view')->view('shops.inventory-select')
            ->label('Select Product(s)')->tab('Configuration');

        // Will Return Shopify or Web Themes Available to the Client Depending on the Shop Selected
        CRUD::field('attributes[blade_template]')->type('view')->view('shops.inventory-select')
            ->label('Select Checkout Experience')->tab('Configuration');

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
