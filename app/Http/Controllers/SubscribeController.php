<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\PaymentGateway;
use App\Models\SubscriptionPlan;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Stripe\Stripe;

class SubscribeController extends BaseController
{
    public function subscribe(Request $request)
    {
        $request->validate([
            "id" => "required|integer",
            "term" => "required|string",
        ]);

        $plan = SubscriptionPlan::find($request->id);

        if ($plan) {

            if($request->query('term') === 'free_plan')
            {
                if($plan->price_monthly && $plan->price_yearly == 0)
                {
                    $workspace = Workspace::find($this->user->workspace_id);

                    $workspace->subscribed = 1;
                    $workspace->subscription_start_date = date("Y-m-d");
                    $workspace->price = 0;
                    $workspace->trial = 0;
                    $workspace->plan_id = $plan->id;
                    $workspace->save();

                    return redirect("/billing")->with(
                        "status",
                        "Subscribed successfully!"
                    );
                }
            }


            $payment_gateways = PaymentGateway::get()->keyBy('api_name')->all();

            if (empty($payment_gateways)) {
                return response("No payment gateway is configured");
            }

            $amount = 0;

            if ($request->term === "monthly") {
                $amount = $plan->monthly;
            } elseif ($request->term === "yearly") {
                $amount = $plan->yearly;
            } else {
                abort(401, "Price is not set!");
            }

            $amount = (float) $amount;

            return \view("settings.subscribe", [
                "selected_navigation" => "billing",
                "payment_gateways" => $payment_gateways,
                "plan" => $plan,
                "amount" => $amount,
                "term" => $request->term,
            ]);
        }
    }

    public function cancelSubscription(Request $request)
    {
        $request->validate([
            "id" => "required|integer",

        ]);

        $plan = SubscriptionPlan::find($request->id);

        if ($plan) {

            $workspace = Workspace::find($this->user->workspace_id);

            $workspace->subscribed = 0;
            $workspace->plan_id = 0;
            $workspace->save();

            return redirect("/billing")->with(
                "status",
                "Unsubscribed successfully!"
            );}

    }

    public function paymentStripe(Request $request)
    {
        $request->validate([
            "plan_id" => "required|integer",
            "term" => "required|string",
            "token_id" => "required",
        ]);

        $plan = SubscriptionPlan::find($request->plan_id);

        if ($plan) {
            $next_renewal_date = date("Y-m-d");
            if ($request->term === "monthly") {
                $amount = $plan->price_monthly;
                $next_renewal_date = date("Y-m-d", strtotime("+1 month"));
            } elseif ($request->term === "yearly") {
                $amount = $plan->price_yearly;
                $next_renewal_date = date("Y-m-d", strtotime("+1 year"));
            } else {
                abort(401);
            }

            $gateway = PaymentGateway::where("api_name", "stripe")->first();

            if (!$gateway) {
                abort(401);
            }

            $token = $request->token_id;

            try {
                // Set your secret key: remember to change this to your live secret key in production
                // See your keys here: https://dashboard.stripe.com/account/apikeys
                Stripe::setApiKey($gateway->private_key);

                // Create a Customer:

                $customer_data = [];

                $customer_data["source"] = $token;
                $customer_data["email"] = $this->user->email;
                $customer_data["name"] =
                    $this->user->first_name . " " . $this->user->last_name;

                $customer = \Stripe\Customer::create($customer_data);

                $card = new CreditCard();
                $card->gateway_id = $gateway->id;
                $card->user_id = $this->user->id;
                $card->token = $customer->id;
                $card->save();

                $amount_x_100 = (int) $amount * 100;
                // Charge the Customer instead of the card:
                $charge = \Stripe\Charge::create([
                    "amount" => $amount_x_100,
                    "currency" => config("app.currency"),
                    "customer" => $customer->id,
                    "description" => $plan->name,
                    "statement_descriptor" => substr(config("app.name"), 0, 22), // Maximum 22 character
                ]);

                $workspace = Workspace::find($this->user->workspace_id);

                $workspace->subscribed = 1;
                $workspace->term = $request->term;
                $workspace->subscription_start_date = date("Y-m-d");
                $workspace->next_renewal_date = $next_renewal_date;
                $workspace->price = $amount;
                $workspace->trial = 0;
                $workspace->plan_id = $plan->id;
                $workspace->save();

                return redirect("/billing")->with(
                    "status",
                    "Subscribed successfully!"
                );
            } catch (\Exception $e) {
                return response(
                    [
                        "success" => false,
                        "errors" => [
                            "system" =>
                                "An error occurred! " . $e->getMessage(),
                        ],
                    ],
                    422
                );
            }
        }
    }
}
