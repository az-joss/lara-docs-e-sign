<?php

namespace App\Listeners;

use App\Events\DocumentSignatureSavedEvent;
use App\Models\Document;
use App\Models\DocumentSignature;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;

class DocumentStatusUpdateSubscriber
{
    /**
     * @param \App\Events\DocumentSignatureSavedEvent $event
     *
     * @return void
     */
    public function onSaved(DocumentSignatureSavedEvent $event): void
    {
        $document = $event->documentSignature->document()->first();

        $documentSignatureStatuses = $document->documentSignatures()
            ->get()
            ->pluck('status')
            ->unique();

        if ($documentSignatureStatuses->count() == 1
            && $documentSignatureStatuses->first() === DocumentSignature::STATUS_SIGNED
        ) {
            $document->status = Document::STATUS_SIGNED;
        } elseif ($documentSignatureStatuses->contains(DocumentSignature::STATUS_REJECTED)) {
            $document->status = Document::STATUS_REJECTED;
        } elseif ($documentSignatureStatuses->contains(DocumentSignature::STATUS_PENDING)) {
            $document->status = Document::STATUS_PENDING;
        }

        if ($document->isDirty()) {
            $document->save();
        }
    }

    public function subscribe(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            DocumentSignatureSavedEvent::class,
            [self::class, 'onSaved']
        );
    }
}
