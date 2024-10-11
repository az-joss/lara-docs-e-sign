<?php

namespace App\Modules\Document;

use App\Models\Document;
use App\Models\DocumentSignature;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

interface DocumentServiceInterface
{
    /**
     * @param \App\Models\User $user
     * @param array $with
     * @param int $offset
     *
     * @return \Illuminate\Contracts\Pagination\Paginator<\App\Models\Document>
     */
    public function getUserDocuments(User $user, array $with = [], int $offset = 1): Paginator;

    /**
     * @param \App\Models\User $user
     * @param array<string, mixed> $documentData
     *
     * @return \App\Models\Document
     */
    public function storeDocument(User $user, array $documentData): Document;

    /**
     * @param \App\Models\Document $document
     * @param array<string, mixed> $documentData
     *
     * @return \App\Models\Document
     */
    public function updateDocument(Document $document, array $documentData): Document;

    /**
     * @param \App\Models\Document $document
     *
     * @return void
     */
    public function destroyDocument(Document $document): void;

    /**
     * @param \App\Models\Document $document
     * @param array<int> $assigneeIds
     *
     * @return void
     */
    public function assignDocumentToUsers(Document $document, array $assigneeIds): void;

    /**
     * @param \App\Models\Document $document
     * @param \App\Models\User $assignee
     *
     * @return \App\Models\DocumentSignature|null
     */
    public function findDocumentSignatureByDocumentAndAssignee(Document $document, User $assignee): ?DocumentSignature;

    /**
     * @param \App\Models\DocumentSignature $documentSignature
     * @param \App\Models\Signature $signature
     *
     * @return void
     */
    public function signDocumentSignature(DocumentSignature $documentSignature, Signature $signature): void;

    /**
     * @param \App\Models\DocumentSignature $documentSignature
     *
     * @return void
     */
    public function rejectDocumentSignature(DocumentSignature $documentSignature): void;
}
