<?php

namespace AnchorCMS\Events\SMS;

use AnchorCMS\Clients;
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

class PhoneNumberBlockedForClient extends ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $phone, $client;
    /**
     * Create a new event instance.
     * @param Phones $phone
     * @param Clients $client
     * @return void
     */
    public function __construct(Phones $phone, Clients $client)
    {
        $this->phone = $phone;
        $this->client = $client;
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

    public function getClient()
    {
        return $this->client;
    }
}
