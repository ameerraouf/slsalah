<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\Projects;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends BaseController
{
    //

    public function addInvestor(Request $request)
    {

        if ($this->modules && !in_array("investors", $this->modules)) {
        abort(401);
    }
        $investor = false;

        if ($request->id) {
            $investor = Investor::find($request->id);
        }

        $products = Projects::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();

        $selected_navigation = 'invested_capital_planning';
        return \view("investors.add", [
            "selected_navigation" => "investors",
            "investor" =>  $investor,
            "products" =>  $products,
            "selected_navigation" => $selected_navigation
        ]);
    }

    public function investorList()
    {
        if ($this->modules && !in_array("investors", $this->modules)) {
            abort(401);
        }
        $investors = Investor::where("workspace_id", $this->user->workspace_id)->get();

        $workspace = Workspace::find($this->user->workspace_id);

        $fixedSum = DB::table('fixed_invested_capitals')
            ->select('investing_price')
            ->where(['workspace_id' => $this->user->workspace_id])->sum('investing_price');
        $workingSum = DB::table('working_invested_capitals')
            ->select('investing_annual_cost')
            ->where(['workspace_id' => $this->user->workspace_id])->sum('investing_annual_cost');

        return \view("investors.list", [
            "selected_navigation" => "invested_capital_planning",
            "investors" =>  $investors,
            'workspace' => $workspace,
            "investingSum" =>  ($fixedSum + $workingSum),

        ]);
    }

    public function show(){
        $investors = Investor::where("workspace_id", $this->user->workspace_id)->get();
        $selected_navigation = "invested_capital_planning";
        $data = [];
        foreach ($investors as $item){
            $data[] = ['y' => $item->amount, 'label' => $item->first_name . ' ' . $item->last_name . " : ".  __('Ryal_in_english'). "$item->amount"];
        }

        return view('investors.show', compact('data', 'selected_navigation'));
    }


    public function investorPost(Request $request)
    {
        if ($this->modules && !in_array("investors", $this->modules)) {
            abort(401);
        }

        $request->validate([
            "first_name" => "required|string|max:100",
            "last_name" => "required|string|max:100",

            "email" => "required|email|unique:investors,email",
            "phone_number" => "nullable|string|max:50|unique:investors,phone_number",
            "amount" => "nullable|gt:0",
            "id" => "nullable|integer",
        ] , [
            "first_name.required" => 'الحقل الاسم الأول مطلوب.',
            "last_name.required" => 'الحقل الأخير الأول مطلوب.',
            "email.required" => 'الحقل اسم المستخدم / البريد الالكترونى مطلوب.'
        ]);


        $inventorsSum = DB::table('investors')
            ->select('amount')
            ->where(['workspace_id' => $this->user->workspace_id])->sum('amount');
        $investmentSum = Investor::getTotalInvestment($this->user->workspace_id);

        if (($inventorsSum+$request->amount) > $investmentSum){
            return redirect()->back()->with('errors', ['المبلغ المقدر للاستثمار لكل المستثمرين يجب الا يتعدى اجمالي راس مال المستثمر']);
        }

        $investor = false;

        if ($request->id) {

            $investor = Investor::where("workspace_id", $this->user->workspace_id)
                    ->where("id", $request->id)
                    ->first();
            }

            if($investor)
            {
                if( $investor->email !== $request->email)
                {
                    $exist = Investor::where('email',$request->email)->first();
                    if($exist)
                    {
                        return redirect()->back()->with([
                            'errors' => [
                                'user_exist' => __('Investor already exist with this email id.')
                            ]
                        ]);
                    }
                }
            }


        if (! $investor) {
            $investor = new Investor();
            $investor->workspace_id = $this->user->workspace_id;
        }

        $investor->first_name = $request->first_name;
        $investor->last_name = $request->last_name;
        $investor->email = $request->email;
        $investor->amount = $request->amount;
        $investor->source = $request->source;
        $investor->notes = clean($request->notes);
        $investor->phone_number = $request->phone_number;
        $investor->twitter = $request->twitter;
        $investor->product_id = $request->product_id;
        $investor->status = $request->status;

        $investor->facebook = $request->facebook;
        $investor->linkedin = $request->linkedin;
//        $user->address_1 = $request->address_1;
//        $user->address_2 = $request->address_2;
//        $user->city = $request->city;
//        $user->state = $request->state;
//        $user->country = $request->country;

//        $user->zip = $request->zip;
        $investor->save();


        return redirect("/investors");
    }


    public function investorView(Request $request)
    {
        if ($this->modules && !in_array("investors", $this->modules)) {
            abort(401);
        }

        $investor = false;

        if ($request->id) {
            $investor = Investor::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->id)
                ->first();
        }

        $products = Projects::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->keyBy("id")
            ->all();



            $selected_navigation = 'invested_capital_planning';

        return \view("investors.view", [
            "selected_navigation" => "investors",
            "investor" => $investor,
            "products" => $products,
            "selected_navigation" => $selected_navigation


        ]);
    }
}
