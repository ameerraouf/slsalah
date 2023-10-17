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

    public function showAll()
    {
        $workspaces = Subscribe::query()->latest()->get();
        $plans = SubscriptionPlan::query()->get();

        return view('super-admin.subscription.index', compact('workspaces', 'plans'));
    }

    public function activeSubscription($subscription)
    {
        $subscription = Subscribe::query()->find($subscription);

        $data = [
            'type' => 'الموافقة علي الاشتراك',
            'subscription' => $subscription,
            'plan' => SubscriptionPlan::query()->find($subscription->subscription_plan_id),
            'notification_type' => 'subscription',
        ];

        $subscription->user->notify(new SubscriptionActiveByAdminNotitication($data));
        $endAt =   $subscription->subscription_type == 'monthly' ? Carbon::parse(now())->addMonth() : Carbon::parse(now())->addYear();
        $subscription->update(['is_active' => 1, 'subscription_date_start' => now(), 'subscription_date_end' => $endAt]);

        return back();
  }

  public function show($subscription)
  {
      $plan = Subscribe::query()->find($subscription);

      return view('super-admin.subscription.show', compact('plan'));
  }
}
