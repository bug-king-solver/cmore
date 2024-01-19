<?php

namespace App\Models;

use App\Models\Crm\Deal;
use App\Models\Invoicing\Document;
use App\Services\Unicre\Unicre;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Payment extends Model
{

    use SoftDeletes;

    protected $connection = 'central';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'token',
        'url',
        'status',
        'payment_data',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_data' => 'array',
        'response' => 'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($payment) {
            $paymentData = $payment->payment_data;
            $paymentData->paymentDeadline = Carbon::createFromDate($paymentData->paymentDeadline)->format('d-m-Y');
            $paymentData->notificationURL = URL::signedRoute('central.payment.update', ['id' => $payment->id]);

            $payment->payment_data = $paymentData;

            $unicre = new Unicre();
            $request = $unicre->makeRequest('request', ['paymentData' => $paymentData]);
            $unicre->savePaymentAdditionalInfo($request, $payment);

            $payment->save();
        });
    }

    /**
     * Check if is a event (each user can see only what he created)
     */
    public function paymentData(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
        );
    }


    public function tenant()
    {
        return $this->belongsTo(config('tenancy.tenant_model'));
    }

    /**
     * A payment can belong to a deal
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * A payment can belong to a transaction
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * A payment can belong to a invoicing document
     */
    public function invoicing_document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Update the payment status and mark the transaction as confirmed.
     *
     * TO DO :: Generate the receipt
     */
    public static function updateStatus($id, $token, $status)
    {
        $payment = Payment::
            where('id', $id)
            ->where('token', $token)
            ->firstOrFail();

        $payment->update(['status' => Unicre::STATUS_PAYMENT[$status]]);


        // If $status = approved
        /*
        if ($status == 2) {
            $transaction = $payment->transaction;
            if ($transaction && $transaction->exists) {
                $transaction->payable->confirm($transaction);
                // TO DO :: Create the receipt
            }
        }
        */

        $documentBodyRequest = $payment->invoicing_document->body_request;
        $documentBodyRequest->Document->Type = 'T';
        $documentBodyRequest->Document->DocumentReference = $payment->invoicing_document->document_id;

        $document = new Document();
        $document->parent_id = $payment->invoicing_document->id;
        $document->tenant_id = $payment->invoicing_document->tenant_id;
        $document->deal_id = $payment->invoicing_document->deal_id;
        $document->body_request = (array) $documentBodyRequest;


        /*

        array_merge(
            ,
            [
            'Document' => [
                'Type' => 'T',
                'DocumentReference' => $document->document_id,
            ]
        ]);
        */
        //$document->body_request->Document->Type = 'T';
        //$document->body_request['Document']['DocumentReference'] = $document->document_id;

        unset(
            $document->document_id,
            $document->status
        );

        $document->save();
    }
}
