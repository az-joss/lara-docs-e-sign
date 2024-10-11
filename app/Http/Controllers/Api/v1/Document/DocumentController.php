<?php

namespace App\Http\Controllers\Api\v1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Http\Requests\Document\UpdateDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Modules\Document\DocumentServiceInterface;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentController extends Controller
{
    public function __construct(
        private Gate $gate,
        private DocumentServiceInterface $documentService,
    ) {}

    public function index(Request $request): JsonResource
    {
        $currentUser = $request->user();

        $documents = $this->documentService->getUserDocuments(
            $currentUser,
            $request->query('with', []),
            $request->query('page', 1),
        );

        return DocumentResource::collection($documents);
    }

    public function store(StoreDocumentRequest $request): JsonResource
    {
        $newDocument = $this->documentService->storeDocument(
            $request->user(),
            $request->validated(),
        );

        return DocumentResource::make($newDocument);
    }

    public function update(UpdateDocumentRequest $request, Document $document): JsonResource
    {
        $this->gate->authorize('update', $document);

        $newDocument = $this->documentService->updateDocument(
            $document,
            $request->validated(),
        );

        return DocumentResource::make($newDocument);
    }

    public function destroy(Document $document): JsonResponse
    {
        $this->gate->authorize('destroy', $document);

        $this->documentService->destroyDocument($document);

        return response()->json(status: 204);
    }
}
