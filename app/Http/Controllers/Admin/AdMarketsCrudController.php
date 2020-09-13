<?php

namespace AnchorCMS\Http\Controllers\Admin;

use Bouncer;
use AnchorCMS\Clients;
//use AnchorCMS\Repositories\ClientServiceRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AnchorCMS\Http\Requests\StandardStoreRequest as StoreRequest;
use AnchorCMS\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class AdBudgetsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AdMarketsCrudController extends CrudController
{
    protected $ad_svc, $clients, $client_svc_repo;

    public function __construct(Clients $clients)//, ClientServiceRepository $c_repo)
    {
        parent::__construct();
        $this->clients = $clients;
        //$this->client_svc_repo = $c_repo;
    }

    public function setup()
    {
        $this->data['page'] = 'crud-ad-markets';

        if(Bouncer::is(backpack_user())->a('god') || backpack_user()->can('view-ad-markets'))
        {
            // @todo - connect to the client's Shipyard package to get the location names.
            // @todo - restore remaining dynamic functionality including creating and saving
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setModel('AnchorCMS\AdMarkets');
            $this->crud->setRoute(config('backpack.base.route_prefix') . '/crud-ad-markets');
            $this->crud->setEntityNameStrings('Ad Market', 'Ad Markets');

            if(session()->has('active_client'))
            {
                $this->crud->addClause('where', 'client_id', '=', session()->get('active_client'));

                $market = [
                    'type' => 'text',
                    'label' => 'Market Name',
                    'name' => 'market_name',
                    'priority' => '1'
                ];

                $client = [
                    'name' => 'client.name',
                    'label' => 'Client',
                    'type' => 'text',
                    'priority' => '3'
                ];

                $active = [
                    'name' => 'active', // the db column name (attribute name)
                    'label' => "Active", // the human-readable label for it
                    'type' => 'boolean', // the kind of column to show
                    'priority' => '2'
                ];

                $active_edit = [
                    'name' => 'active', // the db column name (attribute name)
                    'label' => "Active", // the human-readable label for it
                    'type' => 'checkbox' // the kind of column to show
                ];

                $add_role_client_select = [
                    'name' => 'client_id',
                    'label' => 'Assign a Client',
                    'type' => 'select2_from_array',
                    'options' => Clients::getAllClientsDropList()
                ];

                $route = \Route::current()->uri();
                $mode = 'edit';
                if(strpos($route,'create') !== false)
                {
                    $mode = 'create';
                }

                if($mode == 'edit')
                {
                    $add_role_client_select['attributes'] = [];
                    $add_role_client_select['attributes']['disabled'] = 'disabled';
                }

                $this->crud->addColumns([$client, $market, $active]);

                $edit_create_defs = [$market, $active_edit];
                $this->crud->addFields($edit_create_defs, 'both');

                $create_defs = [$add_role_client_select];
                $this->crud->addFields($create_defs, 'create');
                $this->crud->addFields($create_defs, 'edit');

                // add asterisk for fields that are required in AdBudgetsRequest
                $this->crud->setRequiredFields(StoreRequest::class, 'create');
                $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
                $this->data['can_report'] = true;
            }
            else
            {
                $this->crud->hasAccessOrFail('big-derpz');
            }

            // get dependency data
            $this->data['client_id'] = session()->get('active_client');
        }
        else
        {
            $this->crud->hasAccessOrFail('big-derpz');
        }
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
