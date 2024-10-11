<?php

namespace App\Events;

use App\Models\DocumentSignature;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentSignatureSavedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public DocumentSignature $documentSignature
    ) { }
}
