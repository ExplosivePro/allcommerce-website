<?php

namespace AllCommerce\Jobs\OneClick;

use AllCommerce\LeadAttributes;
use AllCommerce\Leads;
use AllCommerce\OrderAttributes;
use AllCommerce\Orders;
use AllCommerce\Phones;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitOneClickSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $click_data;

    public function __construct(array $data)
    {
        $this->click_data = $data;
    }

    /**
     * Execute the job.
     * @param Leads $lead
     * @param LeadAttributes $lattrs
     * @param Orders $orders
     * @param OrderAttributes $oattrs
     * @return void
     */
    public function handle(Leads $lead, LeadAttributes $lattrs, Orders $orders, OrderAttributes $oattrs)
    {
        if($this->click_data['entity'] == 'lead')
        {
            $lead = $lead->find($this->click_data['id']);

            if(!is_null($lead))
            {
                // Check if any previous active one-click code attributes exist or skip
                $oneClicks = $lead->lead_attributes()->whereName('OneClick Code')
                    ->whereActive(1)->get();

                // Deactivate if any show up
                if(count($oneClicks) > 0)
                {
                    foreach ($oneClicks as $record)
                    {
                        $record->active = 0;
                        $record->save();
                    }
                }

                // Create a new lead_attribute record
                $digits = 4;
                $code = rand(pow(10, $digits-1), pow(10, $digits)-1);

                $attr = new $lattrs;
                $attr->lead_uuid = $lead->id;
                $attr->name = 'OneClick Code';
                $attr->value = $code;
                $attr->misc = [];
                $attr->shop_uuid = $lead->shop_uuid;
                $attr->merchant_uuid = $lead->merchant_uuid;
                $attr->client_uuid = $lead->client_uuid;

                if($attr->save())
                {
                    // Fire off the FireOneClickMsgToTwilio Job
                    FireOneClickSMSMsg::dispatch($attr->id, 'lead')->onQueue('allcommerce-'.env('APP_ENV').'-emails');
                }
                else
                {
                    // @todo - log the error
                }
            }
            else
            {
                // @todo - log this error
            }
        }
        elseif($this->click_data->entity == 'order')
        {
            // @todo - do this after finishing the checkout
        }
        else {
            // @todo - log an unsupported/invalid entity
        }
    }
}
