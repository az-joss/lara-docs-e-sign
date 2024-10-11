<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $file_name
 * @property string $file_path
 * @property string $status
 */
class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory, SoftDeletes;

    public const STATUS_UNSIGNED = 'unsigned';

    public const STATUS_PENDING = 'pending';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_SIGNED = 'signed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'file_name',
        'file_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentSignatures(): HasMany
    {
        return $this->hasMany(
            DocumentSignature::class,
        );
    }

    public function signatures(): HasManyThrough
    {
//        return $this->hasManyThrough(
//            Signature::class,
//            DocumentSignature::class,
//            firstKey: 'document_id',
//            secondKey: 'id',
////            localKey: 'id',
////            secondLocalKey: 'signature_id'
//        );

        return $this
            ->through('documentSignatures')
            ->has('signature');
    }
}
