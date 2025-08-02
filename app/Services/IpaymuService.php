<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IpaymuService
{
    protected $va;
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->va     = env('IPAYMU_VA');
        $this->apiKey = env('IPAYMU_API_KEY');
        $this->url    = env('IPAYMU_URL', 'https://sandbox.ipaymu.com/api/v2/payment');
    }

    public function createPayment(array $data)
    {
        if (!$this->url) {
            throw new \Exception("IPAYMU_URL tidak ditemukan. Pastikan sudah ada di .env");
        }

        $body = [
            'account'     => 100000,
            'product'     => $data['product'],
            'qty'         => $data['qty'],
            'price'       => $data['price'],
            'returnUrl'   => $data['returnUrl'],
            'notifyUrl'   => $data['notifyUrl'],
            'cancelUrl'   => $data['cancelUrl'],
            'referenceId' => $data['referenceId'],
        ];

        $jsonBody  = json_encode($body, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $jsonBody, $this->apiKey);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'signature'    => $signature,
        ])->post($this->url, $body);

        return $response->json();
    }
}
