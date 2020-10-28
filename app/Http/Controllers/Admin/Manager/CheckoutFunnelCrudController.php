<?php

namespace AllCommerce\Http\Controllers\Admin\Manager;

use AllCommerce\Clients;
use AllCommerce\Merchants;
use AllCommerce\Shops;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use AllCommerce\Http\Requests\StandardStoreRequest as StoreRequest;
use AllCommerce\Http\Requests\StandardUpdateRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class CheckoutFunnelCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CheckoutFunnelCrudController extends CrudController
{
    public function setup()
    {
        $this->data['page'] = 'crud-checkout-funnels';
        $this->crud->setModel('AllCommerce\Models\CheckoutFunnels\CheckoutFunnels');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/shop/checkout-funnels');

        if(session()->has('active_shop'))
        {
            $shop = session()->get('active_shop');
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Basic Information
            |--------------------------------------------------------------------------
            */
            $this->crud->setEntityNameStrings('Funnel', $shop->name.' Checkout Funnels');
            /*
            |--------------------------------------------------------------------------
            | CrudPanel Configuration
            |--------------------------------------------------------------------------
            */
            $this->setupShopListOperation($shop->id);
            $this->setupShopUpdateOperation($shop);
            $this->setupShopCreateOperation($shop);
            $this->setupShopPreviewOperation();

            // add asterisk for fields that are required in CheckoutFunnelRequest
            $this->crud->setRequiredFields(StoreRequest::class, 'create');
            $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        }
        else
        {
            if(session()->has('active_merchant'))
            {
                $merchant = session()->get('active_merchant');
                $merchant = Merchants::find($merchant);
                $this->crud->setEntityNameStrings('Funnel', $merchant->name.' Checkout Funnels');

                $shop_ids = [];
                $shops = $merchant->shops()->get();
                foreach ($shops as $shop)
                {
                    $shop_ids[] = $shop->id;
                }

                $this->setupMerchantListOperation($shop_ids);
                $this->setupMerchantUpdateOperation($shops);
                $this->setupMerchantCreateOperation($shops);
                $this->setupMerchantPreviewOperation();

                // add asterisk for fields that are required in CheckoutFunnelRequest
                $this->crud->setRequiredFields(StoreRequest::class, 'create');
                $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
            }
            else
            {
                $client = Clients::whereId(session()->get('active_client'))
                    ->with('shops')->first();

                if(!is_null($client))
                {
                    $this->crud->setEntityNameStrings('Funnel', $client->name.' Checkout Funnels');

                    $shop_ids = [];

                    $shops = $client->shops;
                    foreach ($shops as $shop)
                    {
                        $shop_ids[] = $shop->id;
                    }

                    $this->setupMerchantListOperation($shop_ids);
                    $this->setupMerchantUpdateOperation($shops);
                    $this->setupMerchantCreateOperation($shops);
                    $this->setupMerchantPreviewOperation();

                    // add asterisk for fields that are required in CheckoutFunnelRequest
                    $this->crud->setRequiredFields(StoreRequest::class, 'create');
                    $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
                }
                else
                {
                    $this->crud->hasAccessOrFail('');
                }
            }
        }
    }

    /**
     * List & Search Operators
     */
    /**
     * @param array $shop_ids
     */
    protected function setupMerchantListOperation(array $shop_ids) : void
    {
        $this->crud->addClause('whereIn', 'shop_id', $shop_ids);

        $this->crud->addColumn(['label' => 'Shop', 'name' => 'shop.name', 'type' => 'text']);
        $this->crud->addColumn(['label' => 'Funnel Name', 'name' => 'funnel_name', 'type' => 'text']);
        $this->crud->addColumn(['label' => 'Is Active', 'name' => 'active', 'type' => 'boolean']);
        $this->crud->addColumn(['label' => 'View Funnel', 'name' => 'url', 'type' => 'closure', 'function' => function ($entry) {
            $results = 'None';
            switch($entry->shop_platform)
            {
                case 'shopify':
                    $install = $entry->shopify_install()->first();

                    if(!is_null($install))
                    {
                        if(env('APP_ENV') == 'local')
                        {
                            $results = '<a href="'.env('APP_URL').'/shopify/sales-channel/sales/secure/checkout/'.$entry->id.'" target="_blank">Here</a>';
                        }
                        else
                        {
                            $results = '<a href="https://'.$install->shopify_store_url.env('SHOPIFY_PROXY_URI', '/a/sales').'/secure/checkout/'.$entry->id.'" target="_blank">Here</a>';
                        }
                    }
                    break;
            }

            return $results;
        }]);
    }
    /**
     * @param string $shop_id
     */
    protected function setupShopListOperation(string $shop_id) : void
    {
        $this->crud->addClause('where', 'shop_id', '=', $shop_id);

        $this->crud->addColumn(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text']);
        $this->crud->addColumn(['label' => 'Active', 'name' => 'active', 'type' => 'boolean']);
        $this->crud->addColumn(['label' => 'URL', 'name' => 'url', 'type' => 'closure',
            'function' => function ($entry) {
                $results = 'None';
                switch($entry->shop_platform)
                {
                    case 'shopify':
                        $install = $entry->shopify_install()->first();

                        if(!is_null($install))
                        {
                            if(env('APP_ENV') == 'local')
                            {
                                $results = '<a href="'.env('APP_URL').'/shopify/sales-channel/sales/secure/checkout/'.$entry->id.'" target="_blank">Here</a>';
                            }
                            else
                            {
                                $results = '<a href="https://'.$install->shopify_store_url.env('SHOPIFY_PROXY_URI', '/a/sales').'/secure/checkout/'.$entry->id.'" target="_blank">Here</a>';
                            }
                        }
                    break;
                }

                return $results;
            }
        ]);
    }

    /**
     * Update Operators
     */
    /**
     * @param Shops $shop
     */
    protected function setupShopUpdateOperation(Shops $shop) : void
    {
        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'select2_from_array',
            'options' => $this->getShopSelectOptions([$shop]), 'allows_null' => false,
            'default' => $shop->id,
            'attributes' => [
                'required' => true,
                'disabled' => true
            ]
        ], 'update');
        $this->crud->addField(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'update');
        $this->crud->addField(['label' => 'Active', 'name' => 'active', 'type' => 'boolean'], 'update');
    }
    protected function setupMerchantUpdateOperation($shops) : void
    {
        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'select2_from_array',
            'options' => $this->getShopSelectOptions($shops), 'allows_null' => false,
            'attributes' => ['required' => true]
        ],'update');

        $this->crud->addField(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'update');
        $this->crud->addField(['label' => 'Active', 'name' => 'active', 'type' => 'boolean'], 'update');
    }
    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    /**
     * Create Operators
     */
    /**
     * @param Shops $shop
     */
    protected function setupShopCreateOperation(Shops $shop) : void
    {
        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'select2_from_array',
            'options' => $this->getShopSelectOptions([$shop]), 'allows_null' => false,
            'default' => $shop->id,
            'attributes' => [
                'required' => true,
                'disabled' => true
            ]
        ], 'create');

        $this->crud->addField(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'create');
        $this->crud->addField(['label' => 'Active', 'name' => 'active', 'type' => 'boolean'], 'create');


    }
    protected function setupMerchantCreateOperation($shops): void
    {
        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'select2_from_array',
            'options' => $this->getShopSelectOptions($shops), 'allows_null' => false,
            'attributes' => ['required' => true]
        ],'create');

        $this->crud->addField(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'create');
        $this->crud->addField(['label' => 'Active', 'name' => 'active', 'type' => 'boolean'], 'create');
    }
    public function store(StoreRequest $request, Shops $shops)
    {
        // @todo - locate the shop_install_id or fail ""
        $data = $request->all();
        $shop = $shops->whereId($data['shop_id'])
            ->with('shoptype')->first();

        switch($shop->shoptype->name)
        {
            case 'Shopify':
                $data['shop_platform'] = 'shopify';
                $platform = $shop->shopify_install()->first();

                if(!is_null($platform))
                {
                    // your additional operations before save here
                    $request->request->add([
                        'shop_platform'=> 'shopify',
                        'shop_install_id'=> $platform->id
                    ]);
                    $redirect_location = parent::storeCrud($request);
                    // your additional operations after save here
                    // use $this->data['entry'] or $this->crud->entry
                }
                else
                {
                    \Alert::error('You must link a platform before creating funnels!')->flash();
                    $redirect_location = redirect()->back()->withInput();
                }
                break;

            default:
            \Alert::error('This store does not have checkout funnels enabled!')->flash();
            $redirect_location = redirect()->back()->withInput();
        }
        return $redirect_location;
    }

    /**
     * Preview Operators
     */
    protected function setupShopPreviewOperation(): void
    {

    }
    protected function setupMerchantPreviewOperation(): void
    {

    }


    /**
     * Helpers
     */
    /**
     * @param $shops
     * @return string[]
     */
    private function getShopSelectOptions($shops) : array
    {
        $results = ['' => 'Select a Shop'];

        foreach ($shops as $shop) {
            $results[$shop->id] = $shop->name;
        }

        return $results;
    }
}
