<?php

namespace App\Modules\Document;

use App\Models\Document;
use App\Models\DocumentSignature;
use App\Models\Signature;
use App\Models\User;
use App\Modules\Document\Mappers\DocumentSignatureMapper;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentService implements DocumentServiceInterface
{
    protected const DOCUMENT_DIR_PATH = 'documents';

    public function __construct(
        private DocumentSignatureMapper $documentSignatureMapper,
    ) { }

    /**
     * @inheritDoc
     */
    public function getUserDocuments(User $user, array $with = [], int $offset = 1): Paginator
    {
        return Document::whereBelongsTo($user)
            ->orWhereHas('documentSignatures', function (Builder $query) use ($user) {
                $query->where('signer_id', '=', $user->id);
            })
            ->with($with)
            ->paginate(page: $offset);
    }

    /**
     * @inheritDoc
     */
    public function storeDocument(User $user, array $documentData): Document
    {
        if (isset($documentData['document_file']) && $documentData['document_file'] instanceof UploadedFile) {
            $documentData['file_path'] = $documentData['document_file']->storeAs(
                static::DOCUMENT_DIR_PATH,
                Str::uuid7(),
            );
            unset($documentData['document_file']);
        }

        $documentData['status'] = Document::STATUS_UNSIGNED;

        return $user->documents()->create($documentData);
    }

    /**
     * @inheritDoc
     */
    public function updateDocument(Document $document, array $documentData): Document
    {
        $document->updateOrFail($documentData);

        return $document->fresh();
    }

    /**
     * @inheritDoc
     */
    public function destroyDocument(Document $document): void
    {
        $document->deleteOrFail();
    }

    /**
     * @inheritDoc
     */
    public function assignDocumentToUsers(Document $document, array $assigneeIds): void
    {
        $newDocumentSignatures = $this->documentSignatureMapper->makeCollection($document, $assigneeIds);

        DB::transaction(function() use ($document, $newDocumentSignatures) {
            $document->documentSignatures()
                ->saveManyQuietly($newDocumentSignatures);
            $document->status = Document::STATUS_PENDING;
            $document->saveOrFail();
        });
    }

    /**
     * @inheritDoc
     */
    public function findDocumentSignatureByDocumentAndAssignee(Document $document, User $assignee): ?DocumentSignature
    {
        return $document->documentSignatures()
            ->where('signer_id', $assignee->id)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function signDocumentSignature(DocumentSignature $documentSignature, Signature $signature): void
    {
        $documentSignature->updateOrFail([
            'signature_id' => $signature->id,
            'status' => DocumentSignature::STATUS_SIGNED,
            'signed_at' => now(),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rejectDocumentSignature(DocumentSignature $documentSignature): void
    {
        $documentSignature->updateOrFail([
            'status' => DocumentSignature::STATUS_REJECTED,
            'rejected_at' => now(),
        ]);
    }
}
