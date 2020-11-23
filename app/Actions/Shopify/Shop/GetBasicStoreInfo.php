<?php

namespace App\Actions\Shopify\Shop;

use App\Models\CheckoutFunnels\CheckoutFunnels;
use Lorisleiva\Actions\Action;
use App\Models\Shopify\ShopifyInstalls;

class GetBasicStoreInfo extends Action
{
    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        // Execute the action.
    }

    public static function run(array $data)
    {
        $results = false;

        $status = ShopifyInstalls::whereShopifyStoreUrl('https://'.$data['shop'])->first();
        $funnels = new CheckoutFunnels();

        if(!is_null($status))
        {
            $install_info = $status->toArray();
            //unset($install_info['id']);
            unset($install_info['nonce']);
            unset($install_info['auth_code']);
            unset($install_info['deleted_at']);

            $response = [
                'url' => $data['shop'],
                'status' => $status->toArray(),
            ];

            // If merchant is linked, send merchant info or []
            $shop = $status->shop()->with('merchant')
                ->first();

            if(!is_null($shop))
            {
                $response['allcommerce_shop'] = $shop->toArray();
                $response['allcommerce_merchant'] = $shop->merchant->toArray();
            }


            if($funnel = $funnels->getDefaultFunnelByShop('shopify', $install_info['id']))
            {
                $fun = [
                    'name' => $funnel->funnel_name,
                    'url' => 'https://'.$data['shop'].env('SHOPIFY_PROXY_URI', '/a/sales').'/secure/checkout/'.$funnel->id
                ];

                $response['funnel'] = $fun;
            }

            $results = ['success' => true, 'shop' => $response];
        }

        if($results && array_key_exists('success', $results))
        {
            if($results['success'])
            {
                $payload = [
                    'session_data' => $data,
                    'shop_data' => $results['shop'],
                    'access_token' => $results['shop']['status']['access_token'],
                    'installed' => ($install_info['installed'] == 1),
                    'date_installed' => $install_info['created_at'],
                    'last_updated' => $install_info['updated_at'],
                    'ac_merchant' => $response['allcommerce_merchant'],
                    'ac_shop' => $response['allcommerce_shop']
                ];

                $results = $payload;
            }
        }

        return $results;
    }
}
