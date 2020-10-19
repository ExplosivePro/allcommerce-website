<?php

namespace AllCommerce\Events\SMS;

use AllCommerce\Leads;
use AllCommerce\BillingAddresses;
use AllCommerce\Phones;
use AllCommerce\ShippingAddresses;
use AllCommerce\Shops;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneNumberBlockedForShop extends ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $phone, $shop;
    /**
     * Create a new event instance.
     * @param Phones $phone
     * @param Shops $shop
     * @return void
     */
    public function __construct(Phones $phone, Shops $shop)
    {
        $this->phone = $phone;
        $this->shop = $shop;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getShop()
    {
        return $this->shop;
    }
}
