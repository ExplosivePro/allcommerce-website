<?php

namespace App\Actions\Shopify;

use App\Actions\Shopify\Products\ImportShopifyListings;
use App\Aggregates\Shops\ShopConfigAggregate;
use App\Jobs\CheckoutFunnels\GenerateFirstCheckoutFunnel;
use App\Jobs\Shops\ImportShopifyInventory;
use App\Models\Shopify\ShopifyInstalls;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use Lorisleiva\Actions\Action;

class ConfirmShopInstall extends Action
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

        $install_model = ShopifyInstalls::whereNonce($data['state'])->first();
        $install_model->auth_code = $data['code'];

        $payload = [
            'client_id' => env('SHOPIFY_SALES_CHANNEL_API_KEY'),
            'client_secret' => env('SHOPIFY_SALES_CHANNEL_SECRET'),
            'code' => $data['code']
        ];

        $response  = Curl::to('https://'.$data['shop'].'/admin/oauth/access_token')
            ->withData($payload)
            ->asJson(true)
            ->post();

        Log::info("Response from {$data['shop']} - ", [$response]);

        if((!is_null($response)) && array_key_exists('access_token', $response))
        {
            $install_model->access_token = $response['access_token'];
            $install_model->scopes = $response['scope'];
            $install_model->installed = 1;
            $install_model->save();

            $stats = $install_model->toArray();
            unset($stats['id']);
            unset($stats['deleted_at']);


            // Call Aggy the ShopConfigAggregate with the ShopifyInstall Record
            ShopConfigAggregate::retrieve($install_model->shop_uuid)
                ->completeShopifyInstall($install_model)
                ->persist();

            // queue the inventory import job
            $action = new ImportShopifyListings(['install' => $install_model]);
            $listings = $action->run();

            ImportShopifyInventory::dispatch($listings['listings'], $install_model)->chain([
                  new GenerateFirstCheckoutFunnel($install_model)
            ])->onQueue('aco-'.env('APP_ENV').'-events');

            $results = ['success' => true, 'stats' => $stats];
        }
        else
        {
            $results['reason'] = 'Could not communicate with Shopify';
        }

        return $results;
    }
}
