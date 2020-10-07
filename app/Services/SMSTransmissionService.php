<?php

namespace AnchorCMS\Services;

use Twilio\Rest\Client as Twilio;
use AnchorCMS\Models\SMS\SmsProviders;

class SMSTransmissionService
{
    protected $sms_client, $sms_provider, $from_number, $number_type;

    public function __construct(SmsProviders $providers)
    {
        $this->sms_provider = $providers;
    }

    public function setProvider($uuid) : bool
    {
        $results = false;

        $record = $this->sms_provider->find($uuid);

        if(!is_null($record))
        {
            $this->sms_provider = $record;
            $results = true;
        }

        return $results;
    }

    public function setHouseCredentials() : bool
    {
        $results = false;

        if(!is_null($this->sms_provider->id))
        {
            if($this->getProviderName() == 'Twilio')
            {
                $this->provider_name = 'Twilio';
                $house_record = $this->sms_provider->provider_attributes()
                    ->whereName('house-deets')->whereActive(1)
                    ->first();

                if(!is_null($house_record))
                {
                    $details = $house_record->misc;
                    $sid = $details['accountSid'];
                    $token = $details['authToken'];
                    $this->sms_client = new Twilio($sid, $token);
                    $this->from_number = $details['number'];
                    $this->number_type = $details['type'];
                    $results = true;
                }
            }
        }

        return $results;
    }

    public function getSMSProvider()
    {
        return $this->sms_provider;
    }

    public function getProviderName()
    {
        $results = false;

        if(!is_null($this->sms_provider->id))
        {
            $results = $this->sms_provider->name;
        }

        return $results;
    }

    public function sendMessage($phone_number, $msg)
    {
        $results = false;

        if($provider = $this->getProviderName() && !is_null($this->sms_client))
        {
            if($provider == 'Twilio')
            {
                $message = false;
                if($this->number_type == 'phone')
                {
                    $payload = ['from' => $this->from_number, 'body' => $msg];
                    $message = $this->sms_client->messages
                        ->create($phone_number, $payload);
                }
                elseif($this->number_type == 'shortcode')
                {

                }

                if($message)
                {
                    $results = $message;
                }
            }
        }


        return $results;
    }
}
