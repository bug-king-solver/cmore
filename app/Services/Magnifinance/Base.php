<?php

namespace App\Services\Magnifinance;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Base
{

    protected string|null $url;
    protected string|null $email;
    protected string|null $token;
    protected string|null $endpoint;

    public function __construct()
    {
        $this->url = config('app.magnifinance.url');
        $this->email = config('app.magnifinance.email');
        $this->token = config('app.magnifinance.token');
    }

    protected function checkResponse(Response $response)
    {
        $success = false;
        $msg = $response->body();
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data)) {
                if ($data['IsSuccess']) {
                    $success = true;
                    $msg = [
                        'documentId' => $data['Object']['DocumentId'],
                    ];
                } else {
                    $msg = [
                        'code' => $data['ErrorValue']['Value'],
                        'name' => $data['ErrorValue']['Name'],
                        'error' => $data['ErrorHumanReadable'],
                    ];
                    if (isset($data['Object'])) {
                        $msg['object'] = $data['Object'];
                    }
                }
            }
        } else {
            $msg = [
                'code' => $response->status(),
                'text' => $response->reason()
            ];
        }
        return [
            'success' => $success,
            'message' => $msg,
        ];
    }

}
