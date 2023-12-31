<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use App\Models\Workspace;
use App\Notifications\SubscriptionActiveByAdminNotitication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends SuperAdminController
{
    //


    public function index(){
        $workspaces = Workspace::with(['user', 'plan'])->get();

        return view('super-admin.subscription.list', compact('workspaces'));
    }

    public function showAll(Request $request)
    {
        $workspaces = Subscribe::query();

        if ($request->has('is_subscription_end') && !empty($request->input('is_subscription_end'))) {
            if($request->input('is_subscription_end') == 'false'){
                $workspaces->where('is_subscription_end', 0);
            }else{
                $workspaces->where('is_subscription_end', 1);
            }
        }

        if ($request->has('subscription_plan_id') && !empty($request->input('subscription_plan_id'))) {
            $workspaces->where('subscription_plan_id', $request->input('subscription_plan_id'));
        }


        if($request->has('subscription_type') && !empty($request->input('subscription_type'))){
            $subscriptionType = $request->input('subscription_type');
            $workspaces->where('subscription_type', 'LIKE', "%{$subscriptionType}%");
        }

        $workspaces = $workspaces->latest()->get();

        $plans = SubscriptionPlan::query()->latest()->get();

        return view('super-admin.subscription.index', compact('workspaces', 'plans'));
    }

    public function activeSubscription($subscription)
    {
        $subscription = Subscribe::query()->find($subscription);

        $data = [
            'subscribe' => $subscription,
            'plan' => SubscriptionPlan::query()->find($subscription->subscription_plan_id),
            'user' => auth()->user(),
            'type' => 'الموافقة علي الاشتراك',
            'notification_type' => 'subscription',
            'video' => null,
        ];

        $subscription->user->notify(new SubscriptionActiveByAdminNotitication($data));
        $endAt =   $subscription->subscription_type == 'monthly' ? Carbon::parse(now())->addMonth() : Carbon::parse(now())->addYear();
        $subscription->update(['is_active' => 1, 'subscription_date_start' => now(), 'subscription_date_end' => $endAt]);

        return back();
  }

  public function show($subscription)
  {
      $plan = Subscribe::query()->find($subscription);
      $selected_navigation = 'show_details_of_subscription';

      return view('super-admin.subscription.show', compact('plan', 'selected_navigation'));
  }
}
