<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'name'   => 'required|string',
            'phone'  => 'required|string',
            'email'  => 'required|email',
        ]);

        $payment = $this->ipaymu->createDirectPayment(
            $request->amount,
            $request->name,
            $request->phone,
            $request->email
        );

        return response()->json($payment);
    }

    public function notify(Request $request)
    {
        // \Log::info('iPaymu Notify', $request->all());
        return response()->json(['status' => 'ok']);
    }
}
