<?php

namespace App\Events\CustomerMaster;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InsertCustomerMasterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $customermaster_data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customermaster_data)
    {
        $this->customermaster_data = $customermaster_data;
        // dd($this->customermaster_data);
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
}
