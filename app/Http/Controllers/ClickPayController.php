<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\NewSubscriptionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClickPayController extends Controller
{
    public function pay(Request $request)
    {
        $plan = $request->input('package');
        $package = SubscriptionPlan::query()->find($plan);
        $price = $request->query('type') == 'monthly' ? $package->price_monthly : $package->price_yearly;
        $type = $request->query('type');
        $user = $request->query('u');

        $data = [
            'profile_id' => env('CLICK_PAY_PROFILE_ID'),
            'tran_type' => "sale",
            'tran_class' => "ecom",
            'cart_id' => "4244b9fd-c7e9-4f16-8d3c-4fe7bf6c48ca",
            "cart_description" => "Subscribe in plan",
            "cart_currency" => "SAR",
            "cart_amount" => $price,
            "callback" => route('click_pay.fail',  ['plan' => $plan, 'type' => $type, 'price' => $price]),
            "return" => route('click_pay.success',  ['plan' => $plan, 'type' => $type, 'price' => $price,'u'=> $user]),
        ];

        $response = Http::withHeaders([
            'Authorization' => env('CLICK_PAY_SERVER_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('CLICK_PAY_ENDPOINT'), $data);

        $response = json_decode($response->body());


        if(!is_null($response->redirect_url))
        {
            return redirect()->away($response->redirect_url);
        }

        session()->flash('error_message', 'يوجد مشكله في الدفع جرب مره اخري ف وقت لاحق');

        return redirect()
            ->route('packages.details', $plan)
            ->with('error', $response['message'] ?? 'Something went wrong.');
    }


    public function clickPaySuccess(Request $request)
    {
        $planId = $request->input('plan');
        $subscriptionType = $request->input('type');
        $price = $request->input('price');

        $user = User::find($request->input('u'));

        $subscribeId = userSubscribe($user->id,$planId,$subscriptionType,$price,'click pay', null,null,'',1);

        $data = [
            'subscribe' => Subscribe::query()->find($subscribeId),
            'plan' => SubscriptionPlan::query()->find($planId),
            'user' => $user,
            'type' => 'اشتراك جديد',
            'notification_type' => 'subscription',
            'video' => null,
        ];

        $user->notify(new NewSubscriptionNotification($data));

        $admins = User::query()->where('super_admin', 1)->get();

        foreach ($admins as $admin)
        {
            $admin->notify(new NewSubscriptionNotification($data));
        }

        session()->flash('success', 'تم الاشتراك في الباقة بنجاح.');

        return redirect()->route('payment_successfully');
    }

    public function clickPayFail(Request $request)
    {
        session()->flash('error_message', 'يوجد مشكله في الدفع جرب مره اخري ف وقت لاحق');
        dd('ok');
//
//        return redirect()
//            ->route('packages.details', $request->plan)
//            ->with('error', $response['message'] ?? 'Something went wrong.');
    }
}
