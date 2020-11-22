<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Requests\Features\GatewayProvidersRequest;
use App\Models\PaymentGateways\PaymentProviders;
use App\Models\PaymentGateways\PaymentProviderTypes;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Backpack\ReviseOperation\ReviseOperation;
use Silber\Bouncer\BouncerFacade as Bouncer;

/**
 * Class GatewayProvidersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GatewayProvidersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\PaymentGateways\PaymentProviders::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/payment-gateways');
        CRUD::setEntityNameStrings('Payment Gateway', 'Payment Gateways');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->data['widgets']['before_content'][] = [
            'type' => 'alert',
            'class' => 'alert alert-danger mb-2',
            'heading' => '',
            'content' => '<small><i>Payment Gateway Providers are a fundamental component of eCommerce and allows you to collect payments from the customer for purchasing your products! We have payment integrations to cover several ways to pay - Credit Card, Express Pay and Installment Pay! With a wide range of providers and adding more regularly, you can find and utilize a suite of payment providers that suite your shop(s)\' needs!</i></small>'
        ];

        CRUD::column('name')->type('text');
        CRUD::column('payment_type.provider_type')->type('text')->label('Type');

        $this->crud->addFilter([
            'name' => 'provider_type',
            'type' => 'dropdown',
            'label'=> 'Gateway Type'
        ],
        function () {
            $type = PaymentProviderTypes::whereActive(1)->get();
            $results = [];
            foreach($type as $t)
            {
                $results[$t->id] = $t->provider_type;
            }

            return $results;
        });

        if(Bouncer::is(backpack_user())->an('admin'))
        {
            CRUD::column('active')->type('boolean');
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('delete');

            $this->crud->addButtonFromView('top', 'create-payment-gateway', 'create-payment-gateway', 'beginning');
            $this->crud->addButtonFromView('line', 'edit-payment-gateway', 'edit-payment-gateway', 'beginning');
            $this->crud->addButtonFromView('line', 'delete-payment-gateway', 'delete-payment-gateway', 'end');
        }
        else
        {
            $client = backpack_user()->client()->first();
            CRUD::column('status')->type('closure')->function(function($entry) use ($client) {
                $results = 'Not Assignable';

                if($entry->active)
                {
                    $results = 'Can Be Enabled';


                    $gateway = $client->enabled_gateways()
                        ->whereProviderId($entry->id)
                        ->first();

                    if(!is_null($gateway))
                    {
                        $results = 'Available';
                    }
                }

                return $results;
            });
            $this->crud->denyAccess('show');
            $this->crud->denyAccess('update');
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('delete');
            $this->crud->denyAccess('revise');

            CRUD::addButtonFromView('line', 'Manage', 'manage-client-gateway', 'beginning');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(GatewayProvidersRequest::class);

        CRUD::setFromDb(); // fields

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
