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
                "return_url" => route('paypalSuccessTransaction', ['package' => $package->id, 'type' => $type, 'price' => $price]),
                "cancel_url" => route('paypalCancelTransaction', $package->id),
            ],

            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "$price"
                    ]
                ]
            ],
            "payment_method"=> [
                "payee_preferred"=> "UNRESTRICTED"
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


        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $subscribeId = userSubscribe($this->user->id, $planId, $subscriptionType, $price,'paypal', null,null,'',1);

            $data = [
                'subscribe' => Subscribe::query()->find($subscribeId),
                'plan' => SubscriptionPlan::query()->find($planId),
                'user' => $this->user,
                'type' => 'اشتراك جديد',
                'notification_type' => 'subscription',
                'video' => null,
            ];

            $this->user->notify(new NewSubscriptionNotification($data));

            $admins = User::query()->where('super_admin', 1)->get();

            foreach ($admins as $admin)
            {
                $admin->notify(new NewSubscriptionNotification($data));
            }

            return redirect()
                ->route('payment_successfully')
                ;
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
    public function cancelTransaction(Request $request, $package)
    {
        return redirect()
            ->route('packages.details', $package)
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
