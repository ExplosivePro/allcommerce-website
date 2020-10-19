<?php

namespace AllCommerce\Http\Controllers\Shopify;

use AllCommerce\Models\PaymentGateways\ShopAssignedPaymentProviders;
use AllCommerce\Shops;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AllCommerce\CheckoutFunnels;
use Illuminate\Support\Facades\Cookie;
use AllCommerce\CheckoutFunnelAttributes;
use AllCommerce\Http\Controllers\Controller;
use AllCommerce\DepartmentStore\Library\Shopify\Shop\Storefront;

class ShopifyCheckoutController extends Controller
{
    protected $request, $shops_model;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkout($token,
                             CheckoutFunnels $funnels,
                             ShopAssignedPaymentProviders $gates,
                             CheckoutFunnelAttributes $funnel_attrs)
    {
        $args = [];
        $data = $this->request->all();

        $args['data'] = $data;

        // Lookup the Checkout Funnel with the token or 500.
        $funnel = $funnels::find($token);

        if(!is_null($funnel))
        {
            $shop = $funnel->shop()->first();
            $args['shop_uuid'] = $shop->id;
            $args['shop_name'] = $shop->name;

            $attrs = $funnel->funnel_attributes()->get();

            if(count($attrs) > 0)
            {
                $item_attrs = [];
                foreach ($attrs as $idx => $attr)
                {
                    if (strpos($attr->funnel_attribute, 'item-') !== false) {
                        $item_no = explode('-', $attr->funnel_attribute)[1];
                        $item_attrs[$item_no] = [
                            'inventory_uuid' => $attr->funnel_value,
                            'qty' => $attr->funnel_misc_json['qty'],
                            'variant_uuid' => $attr->funnel_misc_json['variant'],
                        ];

                        $item = $shop->inventory()->whereId($item_attrs[$item_no]['inventory_uuid'])->first();
                        $item_attrs[$item_no]['item'] = $item;
                        $item_attrs[$item_no]['variant'] = $item->variants()->whereId($item_attrs[$item_no]['variant_uuid'])->first();
                        $item_attrs[$item_no]['image'] = $item->images()->wherePlatformId($item->platform_id)->first();
                    }
                }

                $args['checkout_type'] = 'checkout_funnel';
                $args['checkout_id'] = $token;
                $args['items'] = $item_attrs;

                $blade = 'checkouts.default.experience';

                $blade_attr = $attrs->where('funnel_attribute', 'blade-template')->first();

                if(!is_null($blade_attr))
                {
                    $blade = $blade_attr->funnel_value;
                }
                // get the shipping zones and pass them in to the args;
                $token = $shop->oauth_api_token()->first();

                if(!is_null($token))
                {
                    $ac_shop = new Storefront($shop->shop_url);
                    $ac_shop->setShopUuid($shop->id);
                    $ac_shop->setAccessToken($token->token);
                    $shipping_methods = $ac_shop->getShopShippingRates();

                    $args['shipping_methods'] = [];
                    if($shipping_methods && is_array($shipping_methods) && (count($shipping_methods) > 0))
                    {
                        $args['shipping_methods'] = $shipping_methods;
                    }

                    // @todo - migrate this logic into the departmentStore and the OAuth API
                    // @todo - get the shop's assigned payment gateway(s)
                    // @todo for migration - get funnel overrides and replace them as the assigned payment gateway(s)
                    // Separate into the three categories
                    $args['payment_gateways'] = [
                        'credit' => [],
                        'express' => [],
                        'install' => [],
                    ];

                     // Get the shop's assigned gateways with providers and shoptypes
                    $shop_gateways = $gates->whereShopUuid($shop->id)
                        ->whereActive(1)
                        ->with('payment_provider')
                        ->get();

                    if(count($shop_gateways) > 0)
                    {
                        foreach ($shop_gateways as $idx => $gate)
                        {
                            switch($gate->payment_provider->payment_type->slug)
                            {
                                case 'credit':
                                    //Credit can only have 1 or 0 assigned
                                    if(count($args['payment_gateways']['credit']) < 1)
                                    {
                                        // @todo - get the module name from the
                                        $module = $gate->payment_provider->gateway_attributes()
                                            ->whereName('vuex-module')
                                            ->first();

                                        $gatey = $gate->toArray();
                                        $gatey['module'] = $module->misc['module'];

                                        $args['payment_gateways']['credit'][] = $gatey;
                                    }
                                    break;

                                case 'express':
                                    // Express can have 0 - 5 assigned
                                    if(count($args['payment_gateways']['express']) < 5)
                                    {
                                        $args['payment_gateways']['express'][] = $gate->toArray();
                                    }
                                    break;

                                case 'install':
                                    // Install Pay can only have 1 or 0 assigned
                                    if(count($args['payment_gateways']['install']) < 1)
                                    {
                                        $args['payment_gateways']['install'][] = $gate->toArray();
                                    }
                                    break;
                            }


                        }
                    }
                }

                return view($blade, $args);
            }
            else
            {
                return view('errors.501', $args);
            }
        }
        else
        {
            return view('errors.500', $args);
        }
    }
}
