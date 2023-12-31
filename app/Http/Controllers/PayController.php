<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PayController extends BaseController
{
    public function payOnline($package)
    {
        $package = SubscriptionPlan::query()->find($package);
        return view('frontend.package.pay_online', compact('package'));
    }

    public function payWithBank($package)
    {
        $package = SubscriptionPlan::query()->find($package);
        return view('frontend.package.pay_bank', compact('package'));
    }

}
