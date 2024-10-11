<?php

namespace App\Modules\Document\Mappers;

use App\Models\Document;
use App\Models\DocumentSignature;

class DocumentSignatureMapper
{
    /**
     * @param \App\Models\Document $document
     * @param array<int> $assigneeIds
     *
     * @return array<\App\Models\DocumentSignature>
     */
    public function makeCollection(Document $document, array $assigneeIds): array
    {
        return array_map(
            static function(int $assigneesId) use ($document) {
                return new DocumentSignature([
                    'sender_id' => $document->user_id,
                    'signer_id' => $assigneesId,
                    'status' => DocumentSignature::STATUS_PENDING,
                ]);
            },
            $assigneeIds
        );
    }
}
