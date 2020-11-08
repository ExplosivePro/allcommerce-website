<?php

namespace AllCommerce\Jobs\OnBoarding;

use AllCommerce\Aggregates\Clients\ClientConfigAggregate;
use AllCommerce\Aggregates\Merchants\MerchantConfigAggregate;
use AllCommerce\Clients;
use AllCommerce\Merchants;
use AllCommerce\Services\OnBoarding\ClientOnBoardingService;
use AllCommerce\Services\OnBoarding\MerchantOnBoardingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateMerchantOnboarding implements ShouldQueue
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
     * @param ClientOnBoardingService $service
     * @return void
     */
    public function handle(MerchantOnBoardingService $service)
    {
        // retrieve merchant_aggy - the MerchantConfigAggregator
        $merchant_aggy = MerchantConfigAggregate::retrieve($this->merchant->id);

        if(!$merchant_aggy->getMerchant())
        {
            $merchant_aggy = $merchant_aggy->setMerchant($this->merchant);
        }
        else
        {
            $merchant_aggy = $merchant_aggy->updateMerchant($this->merchant);
        }

        if(!$merchant_aggy->getMerchantApiToken())
        {
            if($token = $service->createNewMerchantApiToken($this->merchant->client_id, $this->merchant->id))
            {
                // add the api_token to merchant_aggy
                ClientConfigAggregate::retrieve($this->merchant->client_id)
                    ->setNewMerchantApiToken($token);

                // add the api_token to client_aggy
                $merchant_aggy->setNewMerchantApiToken($token);
            }
        }

        $merchant_aggy->persist();
    }
}
