<?php

namespace App\Http\Controllers\Admin\Features;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\ReviseOperation\ReviseOperation;
use App\Http\Requests\Features\SMSProvidersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SMSProvidersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SMSProvidersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SMS\SmsProviders::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sms-providers');
        CRUD::setEntityNameStrings('SMS Provider', 'SMS Providers');
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
            'content' => '<small><i>Using an SMS Provider allows you to use amazing features, like the 1-Click Checkout for Returning and Abandoned Customers! There\'s no cost to enable SMS save for a small fee per message fired/received on selected providers (free for the rest! + their usage fee. Not partnered with a provider? No problem, get started right away with AllCommerce\'s built-in provider, powered by Twilio, for just $0.01/per message!</i></small>'
        ];

        CRUD::column('name')->type('text');


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
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


                    $gateway = $client->enabled_sms()
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

            CRUD::addButtonFromView('line', 'Manage', 'manage-client-sms', 'beginning');
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
        CRUD::setValidation(SMSProvidersRequest::class);

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
