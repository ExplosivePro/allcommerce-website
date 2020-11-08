<?php

namespace AllCommerce\Jobs\OnBoarding;

use AllCommerce\Aggregates\Clients\ClientConfigAggregate;
use AllCommerce\Aggregates\Merchants\MerchantConfigAggregate;
use AllCommerce\Merchants;
use AllCommerce\Services\OnBoarding\MerchantOnBoardingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewMerchantOnboarding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $merchant;

    /**
     * Create a new job instance.
     * @param Merchants $merchant
     * @return void
     */
    public function __construct(Merchants $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Execute the job.
     * @param MerchantOnBoardingService $service
     * @return void
     */
    public function handle(MerchantOnBoardingService $service)
    {
        // retrieve client_aggy - the ClientConfigAggregator
        // Add merchant to aggregator list of merchants.
        $client_aggy = ClientConfigAggregate::retrieve($this->merchant->client_id)
            ->setNewMerchant($this->merchant);

        // retrieve merchant_aggy - the MerchantConfigAggregator
        $merchant_aggy = MerchantConfigAggregate::retrieve($this->merchant->id)
            ->setMerchant($this->merchant);

        // Create new api_token with the MerchantOnBoarding Service
        if($token = $service->createNewMerchantApiToken($this->merchant->client_id, $this->merchant->id))
        {
            // add the api_token to merchant_aggy
            $client_aggy->setNewMerchantApiToken($token);

            // add the api_token to client_aggy
            $merchant_aggy->setNewMerchantApiToken($token);
        }

        $client_aggy->persist();
        $merchant_aggy->persist();
    }
}
