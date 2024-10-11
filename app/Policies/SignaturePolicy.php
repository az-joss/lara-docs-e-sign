<?php

namespace App\Policies;

use App\Models\Signature;
use App\Models\User;

class SignaturePolicy
{
    public function update(User $user, Signature $signature): bool
    {
        if ($signature->user_id !== $user->id) {
            return false;
        }

        if ($signature->documentSignatures()->count() > 0) {
            return false;
        }

        return true;
    }

    public function destroy(User $user, Signature $signature): bool
    {
        if ($signature->user_id !== $user->id) {
            return false;
        }

        if ($signature->documentSignatures()->count() > 0) {
            return false;
        }

        return true;
    }
}
