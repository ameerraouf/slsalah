<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Notifications\NewSubscriptionNotification;
use Illuminate\Http\Request;
class TransferBankController extends BaseController
{
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'number_of_transfer' => 'required',
            'bank_name' => 'required',
            'image_bank_transfer' => 'required|image',
            'subscription_type' => 'required',
        ]);

        // Store the uploaded file
        $imagePath = '';

        if ($request->hasFile('image_bank_transfer')) {
            $image = $request->file('image_bank_transfer');

            $imagePath = $image->store("media", "uploads");
        }

        $plan = SubscriptionPlan::query()->find($request->input('plan_id'));

        $subscription_type = $request->input('subscription_type');

        $price = $request->input('subscription_type') == 'monthly' ? $plan->price_monthly : $plan->price_yearly;
        $bankName = $request->input('bank_name');
        $transferNumber = $request->input('number_of_transfer');

        userSubscribe($this->user->id,$plan->id,$subscription_type,$price,'حوالة بنكية',$bankName,$imagePath,$transferNumber,0);
        $subscription = Subscribe::query()->where('subscription_plan_id', $plan->id)->where('user_id', auth()->id())->first();

        $admins = User::query()->where('super_admin', 1)->get();

        $data = [
            'type' => 'أشتراك معلق',
            'subscribe' => $subscription,
            'plan' => $plan,
            'user' => auth()->user(),
        ];

        foreach ($admins as $admin)
        {
            $admin->notify(new NewSubscriptionNotification($data));
        }

        return redirect(route('user.package'));
        
    }
}
