<?php

namespace App\Events;

use App\Models\MeritList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeritListPartiallyApproveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $merit_list;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MeritList $meritList)
    {
        $this->merit_list = $meritList;
        logger("event fired.".get_class($this));
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
