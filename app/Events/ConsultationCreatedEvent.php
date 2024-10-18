<?php

namespace App\Events;

use App\Domains\Consultation\Entities\Consultation;
use App\Domains\Consultation\Entities\Contract;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConsultationCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Consultation $consultation
    ) {}
}
