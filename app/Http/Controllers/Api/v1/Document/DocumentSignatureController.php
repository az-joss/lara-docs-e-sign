<?php

namespace App\Http\Controllers\Api\v1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignDocumentRequest;
use App\Models\Document;
use App\Models\Signature;
use App\Modules\Document\DocumentServiceInterface;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentSignatureController extends Controller
{
    public function __construct(
        private Gate $gate,
        private DocumentServiceInterface $documentService,
    ) {}

    public function assign(AssignDocumentRequest $request, Document $document): JsonResponse
    {
        $this->gate->authorize('assign', $document);

        $this->documentService->assignDocumentToUsers(
            $document,
            $request->validated('assignees'),
        );

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function sign(Request $request, Document $document, Signature $signature): JsonResponse
    {
        $documentSignature = $this->documentService->findDocumentSignatureByDocumentAndAssignee(
            $document,
            $request->user(),
        );

        $this->gate->authorize('sign', [$document, $signature, $documentSignature]);

        $this->documentService->signDocumentSignature(
            $documentSignature,
            $signature,
        );

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function reject(Request $request, Document $document): JsonResponse
    {
        $documentSignature = $this->documentService->findDocumentSignatureByDocumentAndAssignee(
            $document,
            $request->user(),
        );

        $this->gate->authorize('reject', [$document, $documentSignature]);

        $this->documentService->rejectDocumentSignature(
            $documentSignature,
        );

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
