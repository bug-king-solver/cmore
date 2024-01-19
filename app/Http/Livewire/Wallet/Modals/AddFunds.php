<?php

namespace App\Http\Livewire\Wallet\Modals;

use App\Models\Invoicing\Document;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

class AddFunds extends ModalComponent
{
    use AuthorizesRequests;

    protected Transaction|null $transaction = null;

    public $done = false;

    public $reference;

    public $amount;

    public $description;

    public $paymentUrl;

    protected function rules()
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * Mount data before open modal
     */
    public function mount(? Transaction $transaction = null)
    {
        $this->amount = 0;
        $this->description = __('Deposit');

        if ($transaction->exists) {
            $this->transaction = $transaction;
            $this->reference = $this->transaction->reference;
            $this->amount = $this->transaction->amountFloat;
            $this->description = $this->transaction->meta['description'] ?? '-';
            $this->paymentUrl = $this->transaction->meta['paymentUrl'] ?? '#';
            $this->done = true;
        }
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }

    public static function closeModalOnEscape(): bool
    {
        return false;
    }

    /**
     * Modal size
     */
    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    /**
     * Rander view
     */
    public function render()
    {
        return ! $this->done
            ? tenantview('livewire.tenant.wallet.addfunds-1')
            : tenantview('livewire.tenant.wallet.addfunds-2');
    }

    /**
     * The code below must be moved to a job
     */
    public function add()
    {
        $this->validate();
        if ($this->amount && is_numeric($this->amount)) {
            $this->done = true;
        }

        $user = auth()->user()->getModel();

        $this->transaction = tenant()->depositFloat(
            $this->amount,
            [
                'user_type' => $user->getMorphClass(),
                'user_id' => $user->getKey(),
                'description' => $this->description,
            ],
            false
        );

        $this->reference = \Illuminate\Support\Str::limit($this->transaction->uuid, 13, $end="");

        // Create invoice
        //
        // TO DO :: Fix this - Add it in a more correct place to be re-used
        // TO DO :: Create an object for document line, document and client
        $document = new Document();
        $document->tenant_id = tenant('id');
        $document->deal_id = null;
        $document->body_request = [
            'SendTo' => 'luis@cmore.pt',
            'Client' => [
                'Name' => 'Luis Coutinho',
                'CountryCode' => 'PT',
                'NIF' => '999999990',
            ],
            'Document' => [
                'Type' => 'I',
                'Date' => '2023-10-06',
                'DueDate' => '2023-10-30',
                'Lines' => [
                    [
                        'Code' => 'PSER001',
                        'Description' => 'Prestação de Serviços',
                        'UnitPrice' => $this->amount,
                        'Quantity' => 1,
                        'Unit' => 'UN',
                        'Type' => 'P',
                        'TaxValue' => 23,
                        'ProductDiscount' => 0,
                    ],
                ]
            ],
            'IsToClose' => false,
        ];

        $document->save();

        // Create payment
        //
        // TO DO :: Create a object for payment data
        $payment = new Payment();
        $payment->tenant_id = tenant('id');
        $payment->deal_id = null;
        $payment->invoicing_document_id = $document->id;
        $payment->transaction_id = $this->transaction->id;
        $payment->payment_data = [
            'firstName' => 'Luis',
            'lastName' => 'Coutinho',
            'email' => 'luis@cmore.pt',
            'addressLine1' => 'Rua Dr Alberto Souto',
            'zipCode' => '3800-000',
            'locality' => 'Aveiro',
            'country' => 'PT',
            'taxpayerNumber' => '999999990',
            'descriptive' => 'Prestação de Serviços',
            'referenceNumber' => $this->reference,
            'amount' => $this->amount,
            'paymentDeadline' => carbon()->addDays(30)->format('Y-m-d'),
            'language' => 'pt',
            'sendLinkByEmail' => true,
        ];
        $payment->save();

        $this->paymentUrl = $payment->response['url'] ?? null;

        // Update transaction url
        $this->transaction->meta = array_merge(
            $this->transaction->meta,
            ['paymentUrl' => $this->paymentUrl]
        );

        $this->transaction->save();
    }
}
