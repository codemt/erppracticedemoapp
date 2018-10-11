<?php

namespace App\Events\SystemUser;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DesignationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $add_designation;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($add_designation)
    {
        $this->add_designation = $add_designation;
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
