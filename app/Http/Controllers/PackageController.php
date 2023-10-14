<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function show($package)
    {
        $package = SubscriptionPlan::query()->find($package);
        $super_settings = Setting::query()->first();

        return view('frontend.package.show', compact('package', 'super_settings'));
    }
}
