<?php

namespace App\Models;

use App\Events\DocumentSignatureSavedEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $document_id
 * @property int $sender_id
 * @property int $signer_id
 * @property int|null $signature_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $signed_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 */
class DocumentSignature extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentSignatureFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_SIGNED = 'signed';

    protected $fillable = [
        'document_id',
        'sender_id',
        'signer_id',
        'signature_id',
        'status',
        'signed_at',
        'rejected_at',
    ];

    protected $dates = [
        'signed_at',
        'rejected_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, string>
     */
    protected $dispatchesEvents = [
        'saved' => DocumentSignatureSavedEvent::class,
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id',
            'sender_id',
        );
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id',
            'signer_id',
        );
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(
            Document::class,
        );
    }

    public function signature(): BelongsTo
    {
        return $this->belongsTo(
            Signature::class,
        );
    }
}
