<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientsRequest;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Backpack\ReviseOperation\ReviseOperation;
use App\Aggregates\Clients\ClientAccountAggregate;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ClientsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClientsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { create as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
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
        CRUD::setModel(\App\Models\Client::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/clients');
        CRUD::setEntityNameStrings('Client', 'Clients');

        $this->crud->allowAccess('revise');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('')->type('closure')->function(function ($entry) {
            $icon = $entry->assigned_icon()->first();
            return '<i class="'.$icon->icon.'" title="'.$entry->name.'"></i>';
        });
        CRUD::column('name')->type('text');
        CRUD::column('active')->type('boolean');
        CRUD::column('logo')->type('image');
        CRUD::column('account_owner')->type('closure')->function(function($entry) {
            $results = 'No One';

            if(!is_null($owner = $entry->account_owner_user()->first()))
            {
                $results = $owner->name;
            }

            return $results;
        });

        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            $this->crud->hasAccessOrFail('nope');
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
        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            $this->crud->hasAccessOrFail('nope');
        }
        else
        {
            CRUD::setValidation(ClientsRequest::class);

            CRUD::field('name')->type('text');
            //CRUD::field('icon')->type('text');
            CRUD::field('icon')->type('select2-icons')->label('Side Bar Icon')
                ->entity('assigned_icon')->model('App\Models\Utility\IconsSet')->attribute('icon')
                //->options(function ($query) {
                //    return $query->orderBy('name', 'ASC')->get();
                //})
                ->hint('Choose from any Icons from <a href="https://fontawesome.com/icons" target="_blank">Font-Awesome</a> (w/ Pro) or <a href="https://icons8.com/line-awesome" target="_blank">Line Awesome</a>');

            CRUD::field('active')->type('boolean');

            CRUD::addField([
                'type' => "relationship",
                'name' => 'iconsset', // the method on your model that defines the relationship
                'ajax' => true,
                'wrapper' => ['class' => 'inline-icons'],
                'inline_create' => [
                    'create_route' =>  route('iconsset-inline-create')
                ], // assumes the URL will be "/admin/category/inline/create"
            ]);
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        if(!Bouncer::is(backpack_user())->an('admin'))
        {
            if($this->crud->getCurrentEntryId() != backpack_user()->client_id)
            {
                $this->crud->hasAccessOrFail('nope');
            }

        }

        $entry = $this->crud->getModel()->find($this->crud->getCurrentEntryId());

        CRUD::setValidation(ClientsRequest::class);
        $entry = $this->crud->getModel()->find($this->crud->getCurrentEntryId());
        CRUD::field('name')->type('text');

        if(Bouncer::is(backpack_user())->an('admin'))
        {
            CRUD::field('icon')->type('select2-icons')->label('Side Bar Icon')
                ->entity('assigned_icon')->model('App\Models\Utility\IconsSet')->attribute('icon')
                ->options(function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                })->default($entry->icon)
                ->hint('Choose from any Icons from <a href="https://fontawesome.com/icons" target="_blank">Font-Awesome</a> (w/ Pro) or <a href="https://icons8.com/line-awesome" target="_blank">Line Awesome</a>');

            CRUD::addField([
                'type' => "relationship",
                'name' => 'iconsset', // the method on your model that defines the relationship
                'ajax' => true,
                'wrapper' => ['class' => 'inline-icons'],
                'inline_create' => [
                    'create_route' =>  route('iconsset-inline-create')
                ], // assumes the URL will be "/admin/category/inline/create"
            ]);
        }

        CRUD::field('logo')->type('text')->hint('Paste the URL of the Image You Want To Use.');

        CRUD::field('account_owner')->type('select2')->label('Account Owner')
            ->entity('account_owner_user')->model('App\Models\User')->attribute('name')
            ->options(function ($query) use ($entry){
                return $query->whereClientId($entry->id)
                    ->orderBy('name', 'ASC')->get();
            })->default($entry->client_id)
            ->hint('This person is the \'Admin\' for this client.');

        CRUD::field('active')->type('boolean');

        $is_owner = $entry->account_owner == backpack_user()->id;
        if($is_owner)
        {
            $this->crud->denyAccess('list');
            $this->crud->setHeading($entry->name);
            $this->crud->setSubheading('Update Company Info');

            $aggy = ClientAccountAggregate::retrieve($entry->id);

            if(!$aggy->getReadyStatus())
            {
                $this->data['widgets']['before_content'][] = [
                    'type'        => 'alert',
                    'class' => 'alert alert-warning col-sm-6 col-md-8 text-dark' ,
                    'heading'     => 'Just One More thing, '.backpack_user()->first_name->first()->value.'!',
                    'content'     => 'Verify your company information below! Upload a logo, update your business\'s location info and you will be all set!',
                    'close_button' => false,
                ];
            }

            $company_name = $entry->company_name()->first();
            CRUD::addField([
                'name' => 'details[company_name]',
                'label' => 'Official Company Name',
                'type'  => 'text',
                'value' => (is_null($company_name)) ? '' : $company_name->value,
                'tab' => 'Company Details',
            ]);

            $address1 = $entry->address1()->first();
            CRUD::addField([
                'name' => 'details[address1]',
                'label' => 'Company Address',
                'type'  => 'text',
                'value' => (is_null($address1)) ? '' : $address1->value,
                'tab' => 'Company Details',
            ]);

            $address2 = $entry->address2()->first();
            CRUD::addField([
                'name' => 'details[address2]',
                'label' => 'Company Address 2',
                'type'  => 'text',
                'value' => (is_null($address2)) ? '' : $address2->value,
                'tab' => 'Company Details',
            ]);

            $city = $entry->city()->first();
            CRUD::addField([
                'name' => 'details[city]',
                'label' => 'Company City',
                'type'  => 'text',
                'value' => (is_null($city)) ? '' : $city->value,
                'tab' => 'Company Details',
            ]);

            $state = $entry->state()->first();
            CRUD::addField([
                'name' => 'details[state]',
                'label' => 'Company State',
                'type'  => 'select2_from_array',
                'options' => \App\Services\USStatesArray::arrayStates(),
                'default' => (is_null($state)) ? '' : $state->value,
                'tab' => 'Company Details',
            ]);

            $zip = $entry->zip()->first();
            CRUD::addField([
                'name' => 'details[zip]',
                'label' => 'Company Zip Code',
                'type'  => 'text',
                'value' => (is_null($zip)) ? '' : $zip->value,
                'tab' => 'Company Details',
            ]);

            $phone = $entry->phone()->first();
            CRUD::addField([
                'name' => 'details[phone]',
                'label' => 'Company Phone #',
                'type'  => 'text',
                'value' => (is_null($phone)) ? '' : $phone->value,
                'tab' => 'Misc Details',
            ]);
            $website = $entry->website()->first();
            CRUD::addField([
                'name' => 'details[website]',
                'label' => 'Company Website',
                'type'  => 'text',
                'value' => (is_null($website)) ? '' : $website->value,
                'tab' => 'Misc Details',
            ]);
            $email = $entry->email()->first();
            CRUD::addField([
                'name' => 'details[email]',
                'label' => 'Company Email',
                'type'  => 'text',
                'value' => (is_null($email)) ? '' : $email->value,
                'tab' => 'Misc Details',
            ]);
        }

    }

    public function update($id)
    {
        $data = $this->crud->getRequest()->request->all();

        $response = $this->traitUpdate();

        if(array_key_exists('details', $data))
        {
            $aggy = ClientAccountAggregate::retrieve($id);

            $was_ready = $aggy->getReadyStatus();

            $aggy->updateAccountDetails($data['details'])
                ->persist();

            if(!$was_ready)
            {
                return redirect('/access/dashboard');
            }
        }

        return $response;
    }
}
