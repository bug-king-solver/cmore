<?php

namespace App\Services\DocuSign;

use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Client\ApiException;

class RequestSignService
{

    protected string|null $account_url;
    protected string|null $rest_api;
    protected string|null $integration_key;
    protected string|null $user_id;
    protected string|null $private_key;
    protected string|null $scopes;
    protected int $time_token;

    public function __construct() {
        $this->scopes = 'signature impersonation';
        $this->time_token = 60;
        $this->configureData();
    }

    private function configureData()
    {
        $this->account_url = config('docusign.account_url');
        $this->integration_key = config('docusign.client_id');
        $this->user_id = config('docusign.user_id');
        $this->private_key = config('docusign.private_key_path');
    }

    public function sendFileToSign($email, $name, $filePath)
    {
        $args = $this->getAuthToken();
        $args['base_path'] = $this->rest_api;
        $args['envelope_args']['signer_email'] = $email;
        $args['envelope_args']['signer_name'] = $name;
        $args['envelope_args']['status'] = "sent";

        $callAPI = new SigningViaEmailService($args);
        return $callAPI->signingViaEmail($args, $filePath);
    }

    private function getAuthToken()
    {
        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setOAuthBasePath($this->account_url);
        $response = $apiClient->requestJWTUserToken($this->integration_key, $this->user_id, $this->private_key, $this->scopes, $this->time_token);
        $access_token = $response[0]->getAccessToken();
        $info = $apiClient->getUserInfo($access_token);
        $account = $info[0]->getAccounts()[0];
        $this->rest_api = $account->getBaseUri() . '/restapi';
        return [
            'account_id' => $account->getAccountId(),
            'ds_access_token' => $access_token
        ];
    }

    /**
     *
     * @param ApiException $e
     * @return void
     */
    public function formatErrorData(ApiException $e): array
    {
        $data = [
            'error_code' => $e->getCode(),
            'error_message' => $e->getMessage(),
        ];

        return $data;
    }

}
