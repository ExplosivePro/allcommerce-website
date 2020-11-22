<?php

namespace App\Http\Controllers\Admin;

use App\Aggregates\Clients\ClientAccountAggregate;
use App\Http\Requests\MerchantsRequest;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MerchantsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MerchantsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Merchant::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/merchants');
        CRUD::setEntityNameStrings('merchants', 'merchants');

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
        CRUD::setFromDb(); // columns

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
        $aggy = ClientAccountAggregate::retrieve(backpack_user()->client_id);

        if($aggy->merchantCount() == 0)
        {
            $this->data['widgets']['before_content'][] = [
                'type'         => 'alert',
                'class'        => 'alert alert-success mb-2 col-md-8',
                'heading'      => 'Let\'s Get Started!',
                'content'      => 'We are now all registered and ready to go! Lets start things off by creating a new Merchant now! After that, you will be free to explore every where!',
                'close_button' => false, // show close button or not
            ];
        }
        CRUD::setValidation(MerchantsRequest::class);

        CRUD::field('name')->type('text')
            ->attributes(['required' => true]); // fields

        $client_id_def = [
            'name' => 'client_id',
            'label' => 'Client',
            'type' => 'select2',
            'entity' => 'client',
            'attribute' => 'name',
            'attributes' => [
                'required' => true
            ],
        ];

        if(!is_null(backpack_user()->client_id))
        {
            $client_id_def['name'] = 'fake_client_id';
            $client_id_def['attributes']['disabled'] = true;
            $client_id_def['default'] = backpack_user()->client_id;

            CRUD::field('client_id')->type('hidden')->value(backpack_user()->client_id);
        }
        CRUD::addField($client_id_def);

        CRUD::field('active')->type('boolean');
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
