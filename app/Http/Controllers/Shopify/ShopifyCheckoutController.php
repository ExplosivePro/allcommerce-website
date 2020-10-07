<?php

namespace AnchorCMS\Http\Controllers\Shopify;

use AnchorCMS\Shops;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use AnchorCMS\CheckoutFunnels;
use Illuminate\Support\Facades\Cookie;
use AnchorCMS\CheckoutFunnelAttributes;
use AnchorCMS\Http\Controllers\Controller;
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
