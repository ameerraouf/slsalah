<?php
use Akaunting\Money\Money;
use App\Models\Subscribe;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Carbon;

function formatCurrency($amount, $isoCode)
{
    if (!$amount) {
        return $amount;
    }
    $decimalPoint = currency($isoCode)->getDecimalMark();

    if ($decimalPoint == ",") {
        $amount = str_replace(".", ",", $amount);
    }

    return Money::$isoCode($amount, true)->formatLocale('en');
}
function getWorkspaceCurrency($settings)
{
    return $settings['currency'] ?? config('app.currency');
}

function getClientIP()
{
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $client_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

        return $client_ips[0];
    }

    if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        return $_SERVER['HTTP_X_FORWARDED'];
    }

    if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    }

    if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_FORWARDED_FOR'];
    }

    if (isset($_SERVER['HTTP_FORWARDED'])) {
        return $_SERVER['HTTP_FORWARDED'];
    }

    if (isset($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return 'UNKNOWN';
}
function userSubscribe($userId, $planId, $subscriptionType, $price,$paymentType,$bankName =null, $bankTransferImage =null, $transferNumber = null, $isActive = 1)
{
    $subscription_date_end = $subscriptionType == 'monthly' ? Carbon::parse(now())->addMonth() : Carbon::parse(now())->addYear();

    $subscribe = Subscribe::query()->where('user_id' , $userId)->where('subscription_plan_id', $planId)->first();
    if($subscribe)
    {
        $subscribe->delete();
    }
    $subscribe = Subscribe::query()->create([
        'user_id' => $userId,
        'subscription_plan_id' => $planId,
        'subscription_type' => $subscriptionType,
        'price' => $price,
        'payment_type' => $paymentType,
        'subscription_date_start' => now(),
        'subscription_date_end' => $subscription_date_end,
        'is_subscription_end' => 0,
        'bank_name' => $bankName,
        'image_bank_transfer' => $bankTransferImage,
        'number_of_transfer' => $transferNumber,
        'is_active' => $isActive,
    ]);

    return $subscribe->id;
}

function isUserSubscribeInPlan($userId, $planId)
{
    return Subscribe::query()->where('user_id', $userId)->where('subscription_plan_id', $planId)->exists();
}

function numberOfSubscriptionInPlan($planId)
{
    return Subscribe::query()->where('subscription_plan_id', $planId)->count();
}

function isSubscriptionIsValid($planId)
{
    $subscribe = Subscribe::find($planId);

    if($subscribe){

        return Carbon::parse($subscribe->subscription_date_end)->format('Y-m-d');
    }

    return false;
}

function checkSubscribeIsExpire($subscribe)
{
    $now = Carbon::now();
    $package = Subscribe::find($subscribe);
    $date = $package->subscription_date_end;

    $diffInDays = $now->diffInDays($date, false);
    if($diffInDays < 0)
    {
        return true;
    }
    return false;
}