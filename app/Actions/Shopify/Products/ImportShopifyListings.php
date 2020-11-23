<?php

namespace App\Actions\Shopify\Products;

use App\Models\Shopify\ShopifyInstalls;
use Ixudra\Curl\Facades\Curl;
use Lorisleiva\Actions\Action;
use App\Models\Inventory\MerchantInventory;

class ImportShopifyListings extends Action
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
     * @param MerchantInventory $inventory
     * @return mixed
     */
    public function handle(MerchantInventory $inventory)
    {
        $results = [];
        $active_install = $this->get('install');

        $local_items = $inventory->getAllItemsByShopId($active_install->id, 'shopify');

        $headers = [
            'X-Shopify-Access-Token: '.$active_install->access_token
        ];

        // Call out to shopify for product listing or fail
        $response  = Curl::to($active_install->shopify_store_url.'/admin/api/2020-07/products.json')
            ->withHeaders($headers)
            ->asJson(true)
            ->get();

        if(is_array($response) && array_key_exists('products', $response))
        {
            if(count($response['products']) > 0)
            {
                $results['listings'] = $response['products'];
            }
        }

        return $results;
    }
}
