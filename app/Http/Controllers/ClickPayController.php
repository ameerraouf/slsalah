<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\NewSubscriptionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClickPayController extends BaseController
{
    public function pay(Request $request)
    {
        $plan = $request->input('package');
        $package = SubscriptionPlan::query()->find($plan);
        $price = $request->query('type') == 'monthly' ? $package->price_monthly : $package->price_yearly;
        $type = $request->query('type');

        $data = [
            'profile_id' => env('CLICK_PAY_PROFILE_ID'),
            'tran_type' => "sale",
            'tran_class' => "ecom",
            'cart_id' => "4244b9fd-c7e9-4f16-8d3c-4fe7bf6c48ca",
            "cart_description" => "Subscribe in plan",
            "cart_currency" => "SAR",
            "cart_amount" => $price,
            "callback" => route('click_pay.success',  ['plan' => $plan, 'type' => $type, 'price' => $price]),
            "return" => route('click_pay.success',  ['plan' => $plan, 'type' => $type, 'price' => $price]),
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
dd($request->all());
        $subscription= Subscribe::query()->where('subscription_plan_id', $planId)->first();

        $data = [
            'subscribe' => $subscription,
            'plan' => SubscriptionPlan::query()->find($planId),
            'user' => $this->user,
            'type' => 'اشتراك جديد'
        ];
        userSubscribe($planId,$subscriptionType,$price,'click pay', null,null,'',1);
        $admins = User::query()->where('super_admin', 1)->get();

        foreach ($admins as $admin)
        {
            $admin->notify(new NewSubscriptionNotification($data));
        }

        session()->flash('success', 'تم الاشتراك في الباقة بنجاح.');

        return redirect()->to('/user/package');
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
