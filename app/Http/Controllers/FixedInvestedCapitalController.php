<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Investor;
use App\Models\Projects;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Models\FixedInvestedCapital;
use App\Http\Controllers\BaseController;

class FixedInvestedCapitalController extends BaseController
{
    protected $defaults = [
        [
            'description' => 'اثاث وتجهيزات',
            'price' => 0
        ],
        [
            'description' => 'معدات كهربائية',
            'price' => 0
        ],
        [
            'description' => 'مصاريف ما قبل التشغيل ',
            'price' => 0
        ],
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //
        if ($this->modules && !in_array("invested_capital_plan", $this->modules)) {
            abort(401);
        }
        $this->createDefaultFixedInvested();
        $fixedInvested = FixedInvestedCapital::where("workspace_id", $this->user->workspace_id)->get();

        $workspace = Workspace::find($this->user->workspace_id);


        $user = User::where('super_admin' , 1)->first();
        $settings_mod = Setting::where('workspace_id' , $user->workspace_id)->get()->keyBy('key');
        if (isset($settings_mod['currency'])) {
            $currency = $settings_mod['currency']->value;
        } else {
            $currency = config('app.currency');
        }
        return \view("Fixedinvested.list", [
            "selected_navigation" => "fixed_capital_planning",
            "fixedInvested" =>  $fixedInvested,
            'workspace' => $workspace,
            'currency' => $currency

        ]);
    }

    private function createDefaultFixedInvested(){
        $workspaceId = $this->user->workspace_id;
        $countFixedInvested = FixedInvestedCapital::where('workspace_id', $workspaceId)->count();
        if ($countFixedInvested == 0){
            foreach ($this->defaults as $fixedInvested){
                FixedInvestedCapital::create([
                    'workspace_id' => $workspaceId,
                    'investing_description' => $fixedInvested['description'],
                    'investing_price' => $fixedInvested['price']
                ]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if ($this->modules && !in_array("invested_capital_plan", $this->modules)) {
            abort(401);
        }
        $investor = null;
        if ($request->id){
            $investor = FixedInvestedCapital::findOrFail($request->id);
        }

        return \view("Fixedinvested.add", [
            "selected_navigation" => "fixed_capital_planning",
            "investor" => $investor,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if ($this->modules && !in_array("investors", $this->modules)) {
            abort(401);
        }
        $request->validate([
            "investing_description" => "required|string|max:100",
            "investing_price" => "required|numeric|gt:0",
            "id" => "nullable|integer",
        ]);


        $investor = false;

        if ($request->id) {

            $investor = FixedInvestedCapital::where("workspace_id", $this->user->workspace_id)
                ->where("id", $request->id)
                ->first();
        }


        if (! $investor) {
            $investor = new FixedInvestedCapital();
            $investor->workspace_id = $this->user->workspace_id;
        }

        $investor->investing_description = $request->investing_description;
        $investor->investing_price = $request->investing_price;
        $investor->save();


        return redirect()->route('fixedInvested.index')->with('success', __('item_updated_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FixedInvestedCapital  $fixedInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $fixedInvested = FixedInvestedCapital::where('workspace_id', $this->user->workspace_id)->get();
        $data = [];
        foreach ($fixedInvested as $key =>$item){
            $data[] = ['y' => $item->investing_price, 'label' => $key+1 . ' - '. $item->investing_description . ' : ' . __('Ryal_in_english'). $item->investing_price];
        }
        $selected_navigation = "fixed_capital_planning";

      return view('Fixedinvested.show', compact('data', 'selected_navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FixedInvestedCapital  $fixedInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function edit(FixedInvestedCapital $fixedInvestedCapital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FixedInvestedCapital  $fixedInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FixedInvestedCapital $fixedInvestedCapital)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FixedInvestedCapital  $fixedInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if ($request->id){
            $investor = FixedInvestedCapital::findOrFail($request->id);
            $investor->delete();
        }
        return redirect()->route('fixedInvested.index')->with('success', __('item_deleted_successfully'));

    }
    public function destroyCost(Request $request)
    {
        //
        if ($request->id){
            $investor = FixedInvestedCapital::findOrFail($request->id);
            $investor->investing_price = 0;
            $investor->save();
        }
        return redirect()->route('fixedInvested.index')->with('success', __('item_cost_deleted_successfully'));

    }
}
