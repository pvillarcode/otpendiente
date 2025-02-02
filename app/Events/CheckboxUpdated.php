<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\CheckboxState;


class CheckboxUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $checkboxState;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CheckboxState $checkboxState)
    {
        $this->checkboxState = $checkboxState;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('checkbox-channel');
    }

    public function broadcastAs()
    {
        return 'checkbox-updated';
    }
}
