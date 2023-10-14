<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
//use Srmklive\PayPal\Services\PayPal as PayPalClient;


class PaypalController extends Controller
{
    /**
     * create transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTransaction()
    {
        $super_settings = Setting::query()->first();
        return view('frontend.paypal', compact('super_settings'));
    }
    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {
        $package = SubscriptionPlan::query()->find($request->input('package'));

//        $provider = new PayPalClient;
//        $provider->setApiCredentials(config('paypal'));
//        $paypalToken = $provider->getAccessToken();
//
//        $response = $provider->createOrder([
//            "intent" => "CAPTURE",
//            "application_context" => [
//                "return_url" => route('paypalSuccessTransaction'),
//                "cancel_url" => route('paypalCancelTransaction'),
//            ],
//            "purchase_units" => [
//                0 => [
//                    "amount" => [
//                        "currency_code" => "USD",
//                        "value" => "1000.00"
//                    ]
//                ]
//            ]
//        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('paypal')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('paypal')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
//        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('paypal')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('paypal')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('paypal')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
