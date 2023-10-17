<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\NewSubscriptionNotification;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends BaseController
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
    public function processTransaction($package, Request $request)
    {

        $package = SubscriptionPlan::query()->find($package);
        $price = $request->query('type') == 'monthly' ? $package->price_monthly : $package->price_yearly;
        $type = $request->query('type');

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypalSuccessTransaction', ['package' => $package, 'type' => $type, 'price' => $price]),
                "cancel_url" => route('paypalCancelTransaction'),
            ],

            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "$price"
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            session()->flash('error_message', 'يوجد مشكله في الدفع جرب مره اخري ف وقت لاحق');
            return redirect()
                ->route('packages.details', $package->id)
                ->with('error', 'Something went wrong.');
        } else {
            session()->flash('error_message', 'يوجد مشكله في الدفع جرب مره اخري ف وقت لاحق');

            return redirect()
                ->route('packages.details', $package->id)
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
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        $planId = $request->query('package');
        $subscriptionType = $request->query('type');
        $price = $request->query('price');

        userSubscribe($planId,$subscriptionType,$price,'حواله بنكية', null,null,'',0);
        $subscription= Subscribe::query()->where('subscription_plan_id', $planId)->first();

        $data = [
            'subscribe' => $subscription,
            'plan' => SubscriptionPlan::query()->find($planId),
            'user' => $this->user,
            'type' => 'اشتراك جديد'
        ];

        $admins = User::query()->where('super_admin', 1)->get();

        foreach ($admins as $admin)
        {
            $admin->notify(new NewSubscriptionNotification($data));
        }

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            userSubscribe($planId,$subscriptionType,$price,'paypal', null,null,'',1);

            return redirect()
                ->route('user.package')
                ->with('success', 'Transaction complete.');
        } else {

            return redirect()
                ->route('packages.details',$planId)
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
