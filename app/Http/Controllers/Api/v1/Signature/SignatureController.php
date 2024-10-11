<?php

namespace App\Http\Controllers\Api\v1\Signature;

use App\Http\Controllers\Controller;
use App\Http\Requests\Signature\StoreSignatureRequest;
use App\Http\Requests\Signature\UpdateSignatureRequest;
use App\Http\Resources\SignatureResource;
use App\Models\Signature;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignatureController extends Controller
{
    public function __construct(
        private Gate $gate
    ) {}

    public function index(Request $request): JsonResource
    {
        /**
         * @var \App\Models\User $currentUser
         */
        $currentUser = $request->user();

        return SignatureResource::collection(
            $currentUser->signatures()->paginate(),
        );
    }

    public function store(StoreSignatureRequest $request): JsonResource
    {
        /**
         * @var \App\Models\User $currentUser
         */
        $currentUser = $request->user();
        $data = $request->getData();

        $newSignature = $currentUser->signatures()->create($data);

        return SignatureResource::make($newSignature);
    }

    public function update(UpdateSignatureRequest $request, Signature $signature): JsonResource
    {
        $this->gate->authorize('update', $signature);

        $data = $request->validationData();

        $signature->update($data);

        return SignatureResource::make($signature->fresh());
    }

    public function destroy(Signature $signature): JsonResponse
    {
        $this->gate->authorize('destroy', $signature);

        return response()->json(status: 204);
    }
}
