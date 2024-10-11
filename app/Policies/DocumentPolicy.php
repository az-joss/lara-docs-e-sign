<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\DocumentSignature;
use App\Models\Signature;
use App\Models\User;

class DocumentPolicy
{
    public function update(User $user, Document $document): bool
    {
        if (in_array($document->status, [
            Document::STATUS_PENDING,
            Document::STATUS_REJECTED,
            Document::STATUS_SIGNED,
        ])) {
            return false;
        }

        return true;
    }

    public function destroy(User $user, Document $document): bool
    {
        if (in_array($document->status, [
            Document::STATUS_PENDING,
            Document::STATUS_REJECTED,
            Document::STATUS_SIGNED,
        ])) {
            return false;
        }

        return true;
    }

    public function assign(User $user, Document $document): bool
    {
        if ($document->user_id !== $user->id) {
            return false;
        }

        if (in_array($document->status, [
            Document::STATUS_PENDING,
            Document::STATUS_REJECTED,
            Document::STATUS_SIGNED,
        ])) {
            return false;
        }

        return true;
    }

    public function sign(
        User $user,
        Document $document,
        Signature $signature,
        ?DocumentSignature $documentSignature,
    ): bool {
        if (!$documentSignature || $documentSignature->status !== DocumentSignature::STATUS_PENDING) {
            return false;
        }

        if ($signature->user_id !== $user->id) {
            return false;
        }

        return true;
    }

    public function reject(
        User $user,
        Document $document,
        ?DocumentSignature $documentSignature,
    ): bool {
        if (!$documentSignature || $documentSignature->status !== DocumentSignature::STATUS_PENDING) {
            return false;
        }

        return true;
    }
}
