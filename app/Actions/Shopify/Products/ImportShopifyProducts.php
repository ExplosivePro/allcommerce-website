<?php

namespace App\Actions\Shopify\Products;

use App\Aggregates\Shops\ShopConfigAggregate;
use App\Jobs\Shops\ImportShopifyInventory;
use App\Models\Inventory\MerchantInventory;
use App\Models\Shops\Shop;
use Lorisleiva\Actions\Action;

class ImportShopifyProducts extends Action
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
     * @param $uuid
     * @param Shop $shops
     * @return mixed
     */
    public function handle($uuid, Shop $shops)
    {
        $results = ['success' => false, 'reason' => 'Could not Import New Products From Shopify'];
        // Execute the action.
        $shop = $shops->find($uuid);
        $install = $shop->shopify_install()->first();

        $action = new ImportShopifyListings(['install' => $install]);
        $new_products = $action->run();
        if($new_products && array_key_exists('listings', $new_products))
        {
            ImportShopifyInventory::dispatch($new_products['listings'], $install)->onQueue('aco-'.env('APP_ENV').'-events');
            $results = ['success' => true, 'reason' => 'Import Started! You will Be notified When it is completed'];
        }
        else
        {
            $results['reason'] = 'No new products to import!';
        }

        return $results;
    }

    /**
     * Assign an SMS Provider to an Array of Shops
     * @param $result
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response($result, $request)
    {
        if($result['success'])
        {
            \Alert::success($result['reason'])->flash();
        }
        else
        {
            \Alert::error($result['reason'])->flash();
        }
        // Return to the referral URL
        return redirect()->back();
    }
}
