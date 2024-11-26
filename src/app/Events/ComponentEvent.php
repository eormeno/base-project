<?php

namespace App\Events;

use App\Models\Component;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ComponentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $component;
    public $payload;

    /**
     * Create a new event instance.
     */
    public function __construct(Component $component, array $payload = [])
    {
        $this->component = $component;
        $this->payload = $payload;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
