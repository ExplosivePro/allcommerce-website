<?php

namespace AllCommerce\Http\Controllers\Admin\Manager;

use AllCommerce\Clients;
use AllCommerce\InventoryVariants;
use AllCommerce\MerchantInventory;
use AllCommerce\Merchants;
use AllCommerce\Models\CheckoutFunnels\CheckoutFunnelAttributes;
use AllCommerce\Models\CheckoutThemes\CheckoutThemes;
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
            $action = $this->crud->getActionMethod();
            switch($action)
            {
                case 'index':
                case 'search':
                    $this->setupShopListOperation($shop->id);
                    break;

                case 'edit':
                case 'update':
                    $this->setupShopUpdateOperation($shop);
                    break;

                case 'store':
                case 'storeCrud':
                case 'create':
                    $this->setupShopCreateOperation($shop);
                    break;

                default:
                    $this->setupShopPreviewOperation();
            }

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

                $action = $this->crud->getActionMethod();
                switch($action)
                {
                    case 'index':
                    case 'search':
                        $shop_ids = [];
                        foreach ($shops as $shop)
                        {
                            $shop_ids[] = $shop->id;
                        }
                        $this->setupMerchantListOperation($shop_ids);
                        break;

                    case 'edit':
                    case 'update':
                        $this->setupMerchantUpdateOperation($shops);
                        break;

                    case 'store':
                    case 'storeCrud':
                    case 'create':
                        $this->setupMerchantCreateOperation($shops);
                        break;

                    default:
                        $this->setupMerchantPreviewOperation();
                }

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

                    $shops = $client->shops;

                    $action = $this->crud->getActionMethod();
                    switch($action)
                    {
                        case 'index':
                        case 'search':
                            $shop_ids = [];
                            foreach ($shops as $shop)
                            {
                                $shop_ids[] = $shop->id;
                            }
                            $this->setupMerchantListOperation($shop_ids);
                        break;

                        case 'edit':
                        case 'update':
                            $this->setupMerchantUpdateOperation($shops);
                            break;

                        case 'store':
                        case 'storeCrud':
                        case 'create':
                            $this->setupMerchantCreateOperation($shops);
                            break;

                        default:
                            $this->setupMerchantPreviewOperation();
                    }

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
            ],
            'tab' => 'Basic Info'
        ], 'update');
        $this->crud->addField(['label' => 'Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'update');
        $this->crud->addField(['label' => 'Active', 'name' => 'active', 'type' => 'boolean'], 'update');
    }
    protected function setupMerchantUpdateOperation($shops) : void
    {
        $this->crud->addField(['label' => 'Funnel Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'update');

        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'funnel_shop_select2',
            'options' => $this->getShopSelectOptions($shops), 'allows_null' => false,
            'attributes' => ['required' => true],
            'tab' => 'Basic Info'
        ],'update');

        $this->crud->addField(['label' => 'Make Live', 'name' => 'active', 'type' => 'boolean', 'tab' => 'Basic Info'], 'update');

        $this->crud->addField([
            'label' => 'Select a Checkout Theme', 'name' => 'assigned_theme.funnel_value', 'type' => 'select2_from_array',
            'options' => $this->getCheckoutThemeSelectOptions(), 'allows_null' => false,
            'attributes' => ['required' => true],
            'default' => $this->getPrefilledCheckoutThemeValue($this->crud->getCurrentEntryId()),
            'tab' => 'Checkout Theme'
        ], 'update');

        $this->crud->addField([
            'label' => 'Dupe', 'name' => 'dupe', 'type' => 'text',
            'tab' => 'Products'
        ], 'update');
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
        $this->crud->addField(['label' => 'Funnel Name', 'name' => 'funnel_name', 'type' => 'text', 'attributes' => ['required' => true]], 'create');

        $this->crud->addField([
            'label' => 'Select a Shop', 'name' => 'shop_id', 'type' => 'funnel_shop_select2',
            'options' => $this->getShopSelectOptions($shops), 'allows_null' => false,
            'attributes' => ['required' => true],
            'tab' => 'Basic Info'
        ],'create');

        $this->crud->addField(['label' => 'Make Live', 'name' => 'active', 'type' => 'boolean', 'tab' => 'Basic Info'], 'create');

        $this->crud->addField([
            'label' => 'Select a Checkout Theme', 'name' => 'assigned_theme.funnel_value', 'type' => 'select2_from_array',
            'options' => $this->getCheckoutThemeSelectOptions(), 'allows_null' => false,
            'attributes' => ['required' => true],
            'default' => $this->getPrefilledCheckoutThemeValue($this->crud->getCurrentEntryId()),
            'tab' => 'Checkout Theme'
        ], 'create');

        $this->crud->addField([
            'label' => 'Add Products to Funnel', 'name' => 'products', 'type' => 'funnel_shop_products_select2',
            'attributes' => ['required' => true],
            'tab' => 'Products'
        ], 'create');
    }
    public function store(StoreRequest $request,
                          Shops $shops,
                          CheckoutFunnelAttributes $attrs,
                          MerchantInventory $inventory,
                          InventoryVariants $variants
    )
    {
        // locate the shop or fail ""
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
                    if($this->crud->entry->id === 0)
                    {
                        //Re-retrieve the new entry or skip (blah)
                        $entry = $this->crud->entry->whereShopId($this->crud->entry->shop_id)
                            ->whereShopInstallId($this->crud->entry->shop_install_id)
                            ->whereFunnelName($this->crud->entry->funnel_name)
                            ->whereActive($this->crud->entry->active)
                            ->whereCreatedAt($this->crud->entry->created_at)
                            ->first();
                    }
                    else
                    {
                        $entry = $this->crud->entry;
                    }

                    if(!is_null($entry))
                    {
                        // insert a record for the blade-template/assigned_theme_funnel_value or fail flow;
                        if((!is_null($data['assigned_theme_funnel_value'])) && (!empty($data['assigned_theme_funnel_value'])))
                        {
                            $template_payload = [
                                'funnel_uuid' => $entry->id,
                                'funnel_attribute' => 'blade-template',
                                'funnel_value' => $data['assigned_theme_funnel_value'],
                                'funnel_misc_json' => [],
                                'active' => 1
                            ];
                            $blade_record = $attrs->insert($template_payload);

                            if($blade_record)
                            {
                                if(count($data['products']) > 0)
                                {
                                    $items = [];
                                    $item_count = 1;
                                    //foreach product, check the variants or skip
                                    foreach ($data['products'] as $merchant_inventory_id)
                                    {
                                        $product = $inventory->find($merchant_inventory_id);
                                        $shopify_id = $product->platform_id;
                                        if(array_key_exists($merchant_inventory_id, $data['variant']) && (count($data['variant'][$merchant_inventory_id]) > 0))
                                        {
                                            $options_json = ['option1' => null, 'option2' => null, 'option3' => null];
                                            $count = 1;
                                            //$attempted_selections = $variants->whereInventoryId($shopify_id)->get();

                                            // locate the inventory_variant_record using the variant array with the product key
                                            foreach($data['variant'][$merchant_inventory_id] as $variant_name => $variant_selection)
                                            {
                                                $options_json["option{$count}"] = $variant_selection;
                                                //$attempted_selections = $attempted_selections->where('options->option'.$count, '=', $variant_selection);
                                                $count++;
                                            }

                                            $attempted_selections = $variants->whereInventoryId($shopify_id)
                                                ->whereOptions(json_encode($options_json))
                                                ->first();

                                            if(!is_null($attempted_selections))
                                            {
                                                $items["item-".$item_count] = [
                                                    'qty' => 1, // @todo - make this dynamic
                                                    'variant' => $attempted_selections->id,
                                                    'options' => ['id' => $merchant_inventory_id]
                                                ];

                                                $item_count++;
                                            }
                                        }
                                    }

                                    if(count($items) > 0)
                                    {
                                        // insert a record for the item (incremented)
                                        foreach($items as $name => $saveable_option)
                                        {
                                            $id = $saveable_option['options']['id'];
                                            unset($saveable_option['options']['id']);
                                            $payload = [
                                                'funnel_uuid' => $entry->id,
                                                'funnel_attribute' => $name,
                                                'funnel_value' => $id,
                                                'funnel_misc_json' => $saveable_option,
                                                'active' => 1
                                            ];

                                            $saved = $attrs->insert($payload);
                                        }
                                    }
                                    else
                                    {
                                        // if no variants left, fail flow
                                        $entry->active = 0;
                                        $entry->save();
                                        \Alert::warn('Issue - Could not save variants selection. Please re-submit. Live was un-toggled.')->flash();
                                    }
                                }
                                else
                                {
                                    $entry->active = 0;
                                    $entry->save();
                                    \Alert::warn('Could not save variants selection. Please re-submit. Live was un-toggled.')->flash();
                                }
                            }
                            else
                            {
                                $entry->active = 0;
                                $entry->save();
                                \Alert::warn('An issue occurred - Could not store theme or variants. Please re-submit. Live was un-toggled.')->flash();
                        }
                        }
                        else
                        {
                            $entry->active = 0;
                            $entry->save();
                            \Alert::warn('Could not store theme or variants. Please re-submit. Live was un-toggled.')->flash();
                        }
                    }

                    // @todo - create checkout funnel attributes here.
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

    private function getCheckoutThemeSelectOptions() : array
    {
        $results = ['' => 'Select a Theme'];

        $themes = CheckoutThemes::whereActive(1)->get();

        if(count($themes) > 0)
        {
            foreach ($themes as $theme)
            {
                $results[$theme->blade_template] = $theme->theme_name;
            }
        }

        return $results;
    }
    private function getPrefilledCheckoutThemeValue($uuid = null)
    {
        $results = '';

        if((!is_null($uuid) && ($uuid)))
        {
            $model = $this->crud->getModel()->whereId($this->crud->getCurrentEntryId())
                ->with('assigned_theme')->first();

            if((!is_null($model)) && !is_null($model->assigned_theme))
            {
                $results = $model->assigned_theme->funnel_value;
            }
        }

        return $results;
    }
}
