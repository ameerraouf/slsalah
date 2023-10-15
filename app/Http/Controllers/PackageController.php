<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
class PackageController extends BaseController
{
    public function show($package)
    {
        $package = SubscriptionPlan::query()->find($package);
        $super_settings = Setting::query()->first();

        return view('frontend.package.show', compact('package', 'super_settings'));
    }

    public function showUserPackage()
    {
        $plans = SubscriptionPlan::withCount(['workspace'])->get();
        $settings = Setting::query()->first();
        $subscribes = Subscribe::query()->where('user_id', 1)->get();

        return view('package.show', compact('plans', 'settings', 'subscribes'));
    }
}
