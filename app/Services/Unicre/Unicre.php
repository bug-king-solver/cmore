<?php

namespace App\Services\Unicre;

use App\Models\Payment;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class Unicre
{

    protected string|null $merchantId;
    protected string|null $apiKey;
    protected string|null $url;

    /**
     * TO DO :: Create an enum
     */
    const STAT_ERROR = 'error';
    const STAT_SUCCESS = 'ok';
    const STATUS_PAYMENT = [
        1 => "in_progress",
        2 => "approved",
        3 => "not_approved",
        4 => "expired",
        5 => "cancelled",
    ];

    public function __construct()
    {
        $this->merchantId = config('app.unicre.merchant_id');
        $this->apiKey = config('app.unicre.api_key');
        $this->url = config('app.unicre.url');
    }

    public function makeRequest($operation, $data = [])
    {
        if (!isset($this->merchantId) || !isset($this->apiKey) || !isset($this->url)) {
            return false;
        }
        $data['merchantId'] = $this->merchantId;
        $data['apiKey'] = $this->apiKey;
        $data['operation'] = $operation;
        
        return Http::withBody(json_encode($data), 'application/json')->get($this->url);
    }

    private function checkResponse(Response $response)
    {
        $success = false;
        $msg = $response->body();
        if ($response->successful()) {
            $data = $response->body();
            $json_data = json_decode($data);
            if (isset($json_data)) {
                if ($json_data->retStat === self::STAT_SUCCESS) {
                    $success = true;
                    if (isset($json_data->token) && isset($json_data->url)) {
                        $msg = [
                            'token' => $json_data->token,
                            'url' => $json_data->url,
                        ];
                    } else if (isset($json_data->status)) {
                        $msg = self::STATUS_PAYMENT[$json_data->status];
                    }
                } else {
                    $msg = [
                        'code' => $json_data->retCode,
                        'info' => $json_data->retMsg,
                    ];
                }
            }
        } else {
            $msg = [
                'code' => $response->status(),
                'text' => $response->reason(),
            ];
        }
        return [
            'success' => $success,
            'message' => $msg,
        ];
    }

    public function savePaymentAdditionalInfo(Response $request, Payment $payment)
    {
        $payment->response = $request->json() ?? $request->body();
        $response = $this->checkResponse($request);
        if ($response['success']) {
            $data = $response['message'];
            $payment->token = $data['token'];
            $payment->url = $data['url'];
            $payment->status = 'in_progress';
        }
    }

    public function cancelPaymentByToken($token)
    {
        $payment = Payment::where('token', $token)->firstOrFail();
        return $this->cancelPayment($payment);
    }

    public function cancelPaymentById($id)
    {
        $payment = Payment::findOrFail($id);
        return $this->cancelPayment($payment);
    }

    private function cancelPayment(Payment $payment)
    {
        if ($payment->token) {
            $request = $this->makeRequest('cancel', [
                'token' => $payment->token
            ]);
            if ($request) {
                $response = $this->checkResponse($request);
                if ($response['success']) {
                    $payment->status = self::STATUS_PAYMENT[5];
                    $payment->save();
                }
            }
        }

        return $payment;
    }

    public function checkPaymentByToken($token)
    {
        $payment = Payment::where('token', $token)->firstOrFail();
        return $this->checkPayment($payment);
    }

    public function checkPaymentById($id)
    {
        $payment = Payment::findOrFail($id);
        return $this->checkPayment($payment);
    }

    private function checkPayment(Payment $payment)
    {
        if ($payment->token) {
            $request = $this->makeRequest('result', [
                'token' => $payment->token
            ]);

            if ($request) {
                $response = $this->checkResponse($request);

                if ($response['success']) {
                    $payment->status = $response['message'];
                    $payment->save();
                }
            }
        }

        return $payment;
    }
}
