<?php

namespace App\Services\DocuSign;

use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Configuration;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\Signer;

class SigningViaEmailService
{

    protected ApiClient $clientService;

    public function __construct($args) {
        $config = new Configuration();
        $config->setHost($args['base_path']);
        $config->addDefaultHeader('Authorization', 'Bearer ' . $args['ds_access_token']);
        $this->clientService = new ApiClient($config);
    }

    public function getEnvelopeApi(): EnvelopesApi
    {
        return new EnvelopesApi($this->clientService);
    }

    /**
     *
     * @param $args array
     * @param $pathFile
     * @return array ['redirect_url']
     */
    public function signingViaEmail(array $args, $pathFile): array
    {
        # Create the envelope request object
        $envelope_definition = $this->make_envelope($args["envelope_args"], $pathFile);
        $envelope_api = $this->getEnvelopeApi();

        $envelopeResponse = $envelope_api->createEnvelope($args['account_id'], $envelope_definition);
        return ['envelope_id' => $envelopeResponse->getEnvelopeId()];
    }

    /**
     * Creates envelope definition
     *
     * @param  $args array
     * @param $clientService
     * @param $demoDocsPath
     * @return EnvelopeDefinition -- returns an envelope definition
     */
     public function make_envelope(array $args, $pathFile): EnvelopeDefinition
    {
        # create the envelope definition
        $envelope_definition = new EnvelopeDefinition([
           'email_subject' => __('C-MORE - Sign this document')
        ]);

        if (file_exists($pathFile)) {
            $content_bytes = file_get_contents($pathFile);
            $file_b64 = base64_encode($content_bytes);
        }

        # Create the document model
        $document = new Document([  # create the DocuSign document object
            'document_base64' => $file_b64 ?? '',
            'name' => 'C-More Files',
            'file_extension' => 'pdf',
            'document_id' => '3'
        ]);
        $envelope_definition->setDocuments([$document]);

        # Create the signer recipient model
        $signer = new Signer([
            'email' => $args['signer_email'],
            'name' => $args['signer_name'],
            'recipient_id' => "1",
            'routing_order' => "1"
        ]);

        return $this->addSignersToTheDelivery($signer, $envelope_definition, $args);
    }

    public function addSignersToTheDelivery($signer, $envelope_definition, $args)
    {
        $recipients = new Recipients(['signers' => [$signer]]);
        $envelope_definition->setRecipients($recipients);
        $envelope_definition->setStatus($args["status"]);

        return $envelope_definition;
    }

}
