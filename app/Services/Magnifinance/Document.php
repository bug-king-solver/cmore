<?php

namespace App\Services\Magnifinance;

use App\Models\Invoicing\Document as InvoiceDocument;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Document extends Base
{

    public function __construct()
    {
        parent::__construct();
        $this->endpoint = $this->url . 'document';
    }

    public function makeRequestPost($data)
    {
        return Http::withBody(json_encode($data), 'application/json')
            ->withHeaders([
                'email' => $this->email,
                'token' => $this->token,
            ])
            ->post($this->endpoint);
    }

    /**
     * This method will used to create a document in Magnifinance
     * @param string $email Email of user
     * @param string $name User/Company name
     * @param string $date Document date in format yyyy-mm-dd.
     * @param string $dueDate Date in which payment is due in format yyyy-mm-dd.
     * @param array $products List of products to include in the invoice
     * @param bool $isToClose Close document or created as draft
     * @param string $countryCode CountryCode is a two-character ISO 3166-1 alpha-2 code corresponding to the country of the invoice customer.
     * @param string $nif Client Tax ID
     */
    public function createDocument($email, $name, $date, $dueDate, $products, $isToClose = false, $countryCode = 'PT', $nif = '999999990')
    {
        $data = [
            'IsToClose' => $isToClose,
            'SendTo' => $email,
            'Client' => [
                'NIF' => $nif,
                'CountryCode' => $countryCode,
                'Name' => $name,
            ],
            'Document' => [
                'Type' => 'T',
                'Date' => $date,
                'DueDate' => $dueDate,
                'Lines' => $products,
            ]
        ];

        $request = $this->makeRequestPost($data);

        if (!$request) {
            return null;
        }

        $document = new InvoiceDocument();

        $document->body_request = $data;
        $document->tenant_id = tenant('id');
        $this->saveDocumentAdditionalInfo($request, $document);
        $document->save();

        return $document;
    }

    public function saveDocumentAdditionalInfo(Response $request, InvoiceDocument $document)
    {
        $document->response = $request->json() ?? $request->body();
        $response = $this->checkResponse($request);
        if ($response['success']) {
            $data = $response['message'];
            $document->document_id = $data['documentId'];
        }
    }

    public function getDocumentByDocumentId($document_id)
    {
        $document = InvoiceDocument::where('document_id', $document_id)->firstOrFail();
        return $this->getDocument($document);
    }

    public function getDocumentById($id)
    {
        $document = InvoiceDocument::findOrFail($id);
        return $this->getDocument($document);
    }

    private function getDocument(InvoiceDocument $document)
    {
        if ($document->document_id) {
            $request = Http::withHeaders([
                'email' => $this->email,
                'token' => $this->token,
            ])
                ->get($this->endpoint . "?documentId=$document->document_id");

            if ($request) {
                $response = $this->checkResponse($request);
                if ($response['success']) {
                    // $document->save();
                } else if (isset($response['message']['object'])) {
                    $document->status = $response['message']['object']['Details']['Document']['DocumentStatusId'];
                    $document->data = $response['message']['object'];
                }
                $document->save();
            }
        }

        return $document;
    }

    public function cancelDocumentByDocumentId($document_id)
    {
        $document = InvoiceDocument::where('document_id', $document_id)->firstOrFail();
        return $this->cancelDocument($document);
    }

    public function cancelDocumentById($id)
    {
        $document = InvoiceDocument::findOrFail($id);
        return $this->cancelDocument($document);
    }

    private function cancelDocument(InvoiceDocument $document)
    {
        if ($document->document_id) {
            $request = Http::withHeaders([
                'email' => $this->email,
                'token' => $this->token,
            ])
                ->patch($this->endpoint . "?documentId=$document->document_id");

            if ($request) {
                $response = $this->checkResponse($request);
                if ($response['success']) {
                    // $document->save();
                } else if (isset($response['message']['object'])) {
                    $document->data = $response['message']['object'];
                }
                $document->save();
            }
        }

        return $document;
    }
}
