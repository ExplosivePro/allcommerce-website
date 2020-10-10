<?php

namespace AnchorCMS\Events\SMS;

use AnchorCMS\Leads;
use AnchorCMS\BillingAddresses;
use AnchorCMS\Phones;
use AnchorCMS\ShippingAddresses;
use AnchorCMS\Shops;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhoneNumberTexted extends ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $phone, $shop;
    /**
     * Create a new event instance.
     * @param $phone
     * @param $shop
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
