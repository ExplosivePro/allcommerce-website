<?php

namespace AllCommerce\Jobs\OneClick;

use AllCommerce\LeadAttributes;
use AllCommerce\OrderAttributes;
use AllCommerce\Services\SMSTransmissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FireOneClickSMSMsg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code_entity, $lead_attribute_id;

    public function __construct(string $lead_attribute_id, string $entity)
    {
        $this->code_entity = $entity;
        $this->lead_attribute_id = $lead_attribute_id;
    }

    public function handle(SMSTransmissionService $sms,
                           OrderAttributes $o_sms_code,
                           LeadAttributes $l_sms_code)
    {
        // Use the passed in lead/order attribute to get the lead record
        $code_record = $this->code_entity == 'lead'
            ? $l_sms_code->find($this->lead_attribute_id)
            : $o_sms_code->find($this->lead_attribute_id);

        if(!is_null($code_record))
        {
            $client = $code_record->client()->first();

            if(!is_null($client))
            {
                // Get the client's SMS Profiles and attributes in features
                $sms_profile = $client->features()->whereName('SMS Profiles')
                    ->with('feature_attributes')->first();

                if(!is_null($sms_profile))
                {
                    if(count($attrs = $sms_profile->feature_attributes) > 0)
                    {
                        $shop = $code_record->shop()->first();

                        if(!is_null($shop))
                        {
                            $enabled_shop = $attrs->where('attribute', '=', 'Enable Shop')
                                ->where('attribute_desc', $shop->id)->first();

                            if(!is_null($enabled_shop))
                            {
                                $provider_record = $attrs->where('attribute', '=', 'SMS Provider')
                                    ->first();

                                if(!is_null($provider_record))
                                {
                                    $provider_id = $provider_record->attribute_desc;

                                    if($sms->setProvider($provider_id))
                                    {
                                        $shop_assigned_sms_profile = $attrs->where('attribute', '=', 'SMS Profile')
                                            ->first();

                                        if(!is_null($shop_assigned_sms_profile))
                                        {
                                            //Pull deets, either via house type or custom type
                                            if(array_key_exists('configType', $shop_assigned_sms_profile->attribute_desc_misc))
                                            {
                                                $configType = $shop_assigned_sms_profile->attribute_desc_misc['configType'];
                                                if($configType == 'house')
                                                {
                                                    $this->housePath($sms, $code_record, $shop->name);
                                                }
                                                elseif($configType == 'custom')
                                                {
                                                    $this->customPath($sms, $code_record, $shop->name);
                                                }
                                                else
                                                {
                                                    // @todo - log the error
                                                }
                                            }
                                            else
                                            {
                                                // @todo - log the error
                                            }
                                        }
                                        else
                                        {
                                            // @todo - log the error
                                        }
                                    }
                                    else
                                    {
                                        // @todo - log the error
                                    }
                                }
                                else
                                {
                                    // @todo - log the error
                                }
                            }
                            else
                            {
                                // @todo - log the error
                            }
                        }
                        else
                        {
                            // @todo - log the error
                        }
                    }
                    else
                    {
                        // @todo - log the error
                    }
                }
                else
                {
                    // @todo - log the error
                }
            }
            else
            {
                // @todo - log the error
            }
        }
        else
        {
            // @todo - log the error
        }
    }

    private function housePath(SMSTransmissionService $sms, $code, $shop_name)
    {
        if($sms->setHouseCredentials())
        {
            // Use the passed in lead attribute to generate the message.
            $message = "{$shop_name} - Your Instant Checkout Code is {$code->value}";
            $phone_number = false;
            if($this->code_entity == 'lead')
            {
                $lead = $code->lead()->first();
                $phone_number = $lead->phone;
            }
            elseif($this->code_entity == 'order')
            {
                $order = $code->order()->first();
                $phone_number = $order->phone;
            }

            // Fire the message to the gateway.
            $response = false;
            if($phone_number)
            {
                $response = $sms->sendMessage($phone_number, $message);
            }

            if($response)
            {
                /**
                 * STEPS
                 * @todo - create a message histories table
                 * @todo - create a client_activity_charges table
                 * 7. Use spatie's event sourcing to add the charge of the text to the charges table
                 * 8. Store the message response in the message histories table
                 *
                 */
            }
        }
        else
        {
            // @todo - log the error
        }
    }

    private function customPath(SMSTransmissionService $sms, $code, $shop_name)
    {

    }
}
