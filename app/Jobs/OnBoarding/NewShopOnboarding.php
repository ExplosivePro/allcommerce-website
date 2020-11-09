<?php

namespace AllCommerce\Jobs\OnBoarding;

use AllCommerce\Aggregates\Clients\ClientConfigAggregate;
use AllCommerce\Aggregates\Merchants\MerchantConfigAggregate;
use AllCommerce\Aggregates\Shops\ShopConfigAggregate;
use AllCommerce\Services\OnBoarding\ShopOnBoardingService;
use AllCommerce\Shops;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewShopOnboarding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $shop;

    /**
     * Create a new job instance.
     * @param Shops $shop
     * @return void
     */
    public function __construct(Shops $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     * @param ShopOnBoardingService $service
     * @return void
     */
    public function handle(ShopOnBoardingService $service)
    {
        // retrieve client_aggy - the ClientConfigAggregator
        // Add shop to aggregator list of shops.
        $client_aggy = ClientConfigAggregate::retrieve($this->shop->client_id)
            ->setNewShop($this->shop);

        // retrieve merchant_aggy - the MerchantConfigAggregator
        // Add shop to aggregator list of shops.
        $merchant_aggy = MerchantConfigAggregate::retrieve($this->shop->merchant_id)
            ->setNewShop($this->shop);

        // retrieve shop_aggy - the ShopConfigAggregator
        $shop_aggy = ShopConfigAggregate::retrieve($this->shop->id)
            ->setShop($this->shop);

        // Set the Shop API Token
        if($token = $service->createNewShopApiToken($this->shop->client_id, $this->shop->id))
        {
            // add the api_token to merchant_aggy
            $client_aggy->setNewShopApiToken($token, $this->shop->id);

            // add the api_token to client_aggy
            $merchant_aggy->setNewShopApiToken($token, $this->shop->id);

            // add the api_token to shop_aggy
            $shop_aggy->setNewShopApiToken($token);
        }

        // If the client has SMS Profiles active,
        if($client_aggy->hasSMSEnabled())
        {
            /**
             * STEPS
             * 2.  Get SMS Profile attribute, and enable Shop
             * 3. In addition, if 1-Click Checkouts active, cut a new enable shop record
             */
            if($client_aggy->hasOneClickSMSEnabled())
            {

                /**
                 * STEPS
                 * 2. If the client has SMS Profiles active, Get SMS Profile attribute, and enable SHop
                 * 3. In addition, if 1-Click Checkouts active, cut a new enable shop record
                 */
            }

        }

        if($assigned = $service->createNewShopAssignedPaymentProvider($this->shop->client_id, $this->shop->merchant_id, $this->shop->id))
        {
            // Assign the Dry Run Gateway as the shops assigned payment gateway
            $shop_aggy = $shop_aggy->setAssignedPaymentProvider($assigned);
        }

        $client_aggy->persist();
        $merchant_aggy->persist();
        $shop_aggy->persist();

    }
}
