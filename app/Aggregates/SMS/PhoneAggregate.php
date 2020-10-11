<?php

namespace AnchorCMS\Aggregates\SMS;

use AnchorCMS\Clients;
use AnchorCMS\Events\SMS\PhoneNumberBlockedForClient;
use AnchorCMS\Events\SMS\PhoneNumberBlockedForShop;
use AnchorCMS\Events\SMS\PhoneNumberBlockedForTheDay;
use AnchorCMS\Events\SMS\PhoneNumberLogged;
use AnchorCMS\Events\SMS\PhoneNumberTexted;
use AnchorCMS\Exceptions\SMS\CouldNotSendSMS;
use AnchorCMS\Phones;
use AnchorCMS\Shops;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class PhoneAggregate extends AggregateRoot
{
    const SHOP_LIMIT = 5;
    const CLIENT_LIMIT = 10;
    const DAY_LIMIT = 12;

    private $total_attempts = 0;
    private $attempts_in_24hrs = 0;

    private $phone_number, $referring_shop, $referring_client;
    private $times_attempted = [];
    private $shop_attempts = [];
    private $client_attempts = [];

    private $limit_type;

    public function logPhoneRecord($phone, $shop)
    {
        $logged = date('Y-m-d H:i:s', strtotime('now'));
        $this->recordThat(new PhoneNumberLogged($phone, $shop, $logged));

        return $this;
    }

    private function phoneNumberHasDailyAttemptsLeft()
    {
        $results = false;

        // 24 hr attempts < the constant or Fail, set type as daily
        if($this->attempts_in_24hrs < self::DAY_LIMIT)
        {
            // daily client attempts < its constant or Fail, set type as client
            $client_attempts = $this->client_attempts[$this->referring_client];
            if($client_attempts < self::CLIENT_LIMIT)
            {
                // daily shop attempts < its constant or fail, set type as shop
                $shop_attempts = $this->shop_attempts[$this->referring_shop];
                if($shop_attempts < self::SHOP_LIMIT)
                {
                    $this->limit_type = '';
                    $results = true;
                }
                else
                {
                    $this->limit_type = 'shop';
                }
            }
            else
            {
                $this->limit_type = 'client';
            }
        }
        else
        {
            $this->limit_type = 'daily';
        }

        return $results;
    }

    /**
     * @param $phone
     * @param $shop
     * @return $this
     * @throws CouldNotSendSMS
     */
    public function addTextAttempt(Phones $phone, Shops $shop)
    {
        // see if we've reached a shop, client or 24_hour limit
        if($this->phoneNumberHasDailyAttemptsLeft())
        {
            $logged = date('Y-m-d H:i:s', strtotime('now'));
            $this->recordThat(new PhoneNumberTexted($phone, $shop, $logged));
            $this->persist();
        }
        else
        {
            switch($this->limit_type)
            {
                case 'shop':
                    $this->recordThat(new PhoneNumberBlockedForShop($phone, $shop));
                    break;

                case 'client':
                    $client = Clients::find($this->referring_client);
                    $this->recordThat(new PhoneNumberBlockedForClient($phone, $client));
                    break;

                case 'daily':
                default:
                $this->recordThat(new PhoneNumberBlockedForTheDay($phone));
            }

            $this->persist();

            throw CouldNotSendSMS::phoneNumberBlocked($phone, $this->limit_type);
            // throw Error to be caught
            // alternatively = return false;
        }

        return $this;
    }

    public function applyPhoneNumberLogged(PhoneNumberLogged $event)
    {
        $this->referring_shop = $event->getShop()->id;
        $this->referring_client = $event->getShop()->client_id;
        $this->phone_number = $event->getPhone()->phone;

        if(!array_key_exists($this->referring_shop, $this->shop_attempts))
        {
            $this->shop_attempts[$this->referring_shop] = 0;
        }

        if(!array_key_exists($this->referring_client, $this->client_attempts))
        {
            $this->client_attempts[$this->referring_client] = 0;
        }
    }

    public function applyPhoneNumberTexted(PhoneNumberTexted $event)
    {
        $now_minus_24 = date('Y-m-d H:i:s', strtotime('now -24 HOUR'));
        $now = date('Y-m-d H:i:s', strtotime('now'));

        $this->total_attempts++;

        $shop = Shops::find($event->getShop()['id']);

        $this->times_attempted[] = [
            'time' => $event->getTimeLogged(),
            'shop' => $shop->id,
            'client' => $shop->client_id
        ];

        $times = collect($this->times_attempted);
        $times_in_last_twenty_four_hrs = $times->whereBetween('time', [$now_minus_24, $now]);
        $this->attempts_in_24hrs = count($times_in_last_twenty_four_hrs);

        // tally up shop_attempts, and client_attempts as well
        $shop_attempts = $times->whereBetween('time', [$now_minus_24, $now])
            ->where('shop', '=', $shop->id);

        if(!array_key_exists($shop->id, $this->shop_attempts))
        {
            $this->shop_attempts[$shop->id] = 0;
        }

        $this->shop_attempts[$shop->id] = count($shop_attempts);

        $client_attempts = $times->whereBetween('time', [$now_minus_24, $now])
            ->where('client', '=', $shop->client_id);

        if(!array_key_exists($shop->client_id, $this->client_attempts))
        {
            $this->client_attempts[$shop->client_id] = 0;
        }

        $this->client_attempts[$shop->client_id] = count($client_attempts);
    }
}
