<?php

namespace App\Models\Invoicing;

use App\Models\Crm\Deal;
use App\Models\Payment;
use App\Services\Magnifinance\Document as MagnifinanceDocument;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{

    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invoicing_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'document_id',
        'status',
        'body_request',
        'response',
        'data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'body_request' => 'json',
        'response' => 'array',
        'data' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (!$document->document_id && !$document->response) {
                $products = [];
                foreach ($document->body_request->Document->Lines as $item) {
                    // onlyfor back office
                    if (isset($item->fields)) {
                        $products[] = $item->fields;
                    }
                }
                if (!empty($products)) {
                    $document->body_request->Document->Lines = $products;
                    $document->forceFill(['body_request->Document->Lines' => $products]);
                }

                $magnifinanceDocument = new MagnifinanceDocument();
                $request = $magnifinanceDocument->makeRequestPost($document->body_request);
                $magnifinanceDocument->saveDocumentAdditionalInfo($request, $document);
            }
        });

        static::created(function ($document) {
            if (!$document->data) {
                $magnifinanceDocument = new MagnifinanceDocument();
                $magnifinanceDocument->getDocumentById($document->id);
            }
        });
    }

    /**
     * Check if is a event (each user can see only what he created)
     */
    public function bodyRequest(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
        );
    }

    /**
     * A document can belongs to a tenant
     */
    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'));
    }

    /**
     * A document can belongs to tenant
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * A document can belongs to tenant
     */
    public function transaction()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * A documentcan have multiple payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoicing_document_id');
    }
}
