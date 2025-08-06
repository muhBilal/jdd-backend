<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpaymuService
{
    protected $apiKey;
    protected $va;
    protected $url;

    public function __construct()
    {
        $this->apiKey = env('IPAYMU_API_KEY');
        $this->va     = env('IPAYMU_VA');
        $this->url    = env('IPAYMU_URL');
    }

    public function createDirectPayment($amount, $name, $phone, $email , $referenceId)
    {
        $endpoint = '/payment/direct';
        $fullUrl  = rtrim($this->url, '/') . $endpoint;

        $body = [
            'name'          => trim($name),
            'phone'         => trim($phone),
            'email'         => trim($email),
            'amount'        => floatval($amount),
            'notifyUrl'     => url('/api/payment/notify'),
            // 'referenceId'   => uniqid('order_'),
            'referenceId'   => $referenceId,
            'paymentMethod' => 'qris',
            'paymentChannel'=> 'bca',  
        ];

        $jsonBody    = json_encode($body, JSON_UNESCAPED_SLASHES);
        $requestBody = strtolower(hash('sha256', $jsonBody));
        $stringToSign= strtoupper('POST') . ':' . $this->va . ':' . $requestBody . ':' . $this->apiKey;
        $signature   = hash_hmac('sha256', $stringToSign, $this->apiKey);
        $timestamp   = now()->format('YmdHis');

        $response = Http::withHeaders([
            'Accept'     => 'application/json',
            'Content-Type' => 'application/json',
            'va'        => $this->va,
            'signature' => $signature,
            'timestamp' => $timestamp,
        ])->post($fullUrl, $body);

        return $response->json();
    }
}
