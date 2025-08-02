<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\IpaymuService;

class PaymentController extends Controller
{
    protected $ipaymu;

    public function __construct(IpaymuService $ipaymu)
    {
        $this->ipaymu = $ipaymu;
    }

    public function create(Request $request)
    {
        // dd('This method is deprecated. Use createQris instead.');
        $validated = $request->validate([
            'product' => 'required|string',
            'qty'     => 'required|integer|min:1',
            'price'   => 'required|numeric|min:1000',
        ]);

        $data = [
            'product'     => $validated['product'],
            'qty'         => $validated['qty'],
            'price'       => $validated['price'],
            'returnUrl'   => url('/api/payment/success'),
            'notifyUrl'   => url('/api/payment/notify'),
            'cancelUrl'   => url('/api/payment/cancel'),
            'referenceId' => uniqid(),
        ];

        try {
            $response = $this->ipaymu->createPayment($data);

            if (isset($response['Data']['Url'])) {
                return response()->json([
                    'success'      => true,
                    'payment_url'  => $response['Data']['Url'],
                    'reference_id' => $data['referenceId'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['Message'] ?? 'Terjadi kesalahan',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function success(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil',
            'data'    => $request->all(),
        ]);
    }

    public function cancel()
    {
        return response()->json([
            'success' => false,
            'message' => 'Pembayaran dibatalkan pengguna',
        ]);
    }

    public function notify(Request $request)
    {
        // Simpan data callback ke log / database

        return response()->json(['success' => true]);
    }
}
