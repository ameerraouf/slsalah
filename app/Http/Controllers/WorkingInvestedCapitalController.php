<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Models\Workspace;
use Illuminate\Http\Request;
use App\Models\FixedInvestedCapital;
use App\Models\WorkingInvestedCapital;
use App\Http\Controllers\BaseController;

class WorkingInvestedCapitalController extends BaseController
{
    protected $defaults = [
        [
            'description' => 'استهلاك كهرباء',
        ],
        [
            'description' => 'استهلاك مياه',
        ],
        [
            'description' => 'اجمالى الرواتب والأجور ',
        ],
        [
            'description' => 'صيانه وتأمين',
        ],
        [
            'description' => 'رسوم واشترااكات',
        ],
        [
            'description' => 'اتعاب محاسب قانونى',
        ],
        [
            'description' => 'مطبوعات واحبار',
        ],
        [
            'description' => 'ضيافة',
        ],
        [
            'description' => 'التسويق',
        ],
        [
            'description' => 'ايجار مقر الشركه',
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
        $this->createDefaultWorkingInvested();
        $workingInvested = WorkingInvestedCapital::where("workspace_id", $this->user->workspace_id)->get();

        $workspace = Workspace::find($this->user->workspace_id);


        $user = User::where('super_admin' , 1)->first();
        $settings_mod = Setting::where('workspace_id' , $user->workspace_id)->get()->keyBy('key');
        if (isset($settings_mod['currency'])) {
            $currency = $settings_mod['currency']->value;
        } else {
            $currency = config('app.currency');
        }
        return \view("workingInvested.list", [
            "selected_navigation" => "working_capital_planning",
            "workingInvested" =>  $workingInvested,
            'workspace' => $workspace,
            'currency' => $currency

        ]);
    }
    private function createDefaultWorkingInvested(){
        $workspaceId = $this->user->workspace_id;
        $countFixedInvested = WorkingInvestedCapital::where('workspace_id', $workspaceId)->count();
        if ($countFixedInvested == 0){
            foreach ($this->defaults as $fixedInvested){
                WorkingInvestedCapital::create([
                    'workspace_id' => $workspaceId,
                    'investing_description' => $fixedInvested['description'],
                    'investing_monthly_cost' => 0,
                    'investing_annual_cost' => 0
                ]);
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        //
        if ($this->modules && !in_array("invested_capital_plan", $this->modules)) {
            abort(401);
        }
        $investor = null;
        if ($request->id){
            $investor = WorkingInvestedCapital::findOrFail($request->id);
        }

        return \view("workingInvested.add", [
            "selected_navigation" => "working_capital_planning",
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
            "investing_monthly_cost" => "required|numeric|gt:0",
            "id" => "nullable|integer",
        ]);


        $investor = false;

        if ($request->id) {

            $investor = WorkingInvestedCapital::where("workspace_id", $this->user->workspace_id)
                ->where("id", $request->id)
                ->first();
        }


        if (! $investor) {
            $investor = new WorkingInvestedCapital();
            $investor->workspace_id = $this->user->workspace_id;
        }

        $investor->investing_description = $request->investing_description;
        $investor->investing_monthly_cost = $request->investing_monthly_cost;
        $investor->investing_annual_cost = 12 * $request->investing_monthly_cost;
        $investor->save();


        return redirect()->route('workingInvested.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingInvestedCapital  $workingInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
        $workInvested = WorkingInvestedCapital::where('workspace_id', $this->user->workspace_id)->get();
        $data = [];
        $number = 1;

        foreach ($workInvested as $item){
            $data[] = ['y' => $item->investing_annual_cost,  'label' => $number++. ' - '. $item->investing_description . ' : '. __('Ryal_in_english') .$item->investing_annual_cost];
        }
        $selected_navigation = "working_capital_planning";
        return view('workingInvested.show', compact('data', 'selected_navigation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkingInvestedCapital  $workingInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkingInvestedCapital $workingInvestedCapital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingInvestedCapital  $workingInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingInvestedCapital $workingInvestedCapital)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingInvestedCapital  $workingInvestedCapital
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        if ($request->id){
            $investor = WorkingInvestedCapital::findOrFail($request->id);
            $investor->delete();
        }
        return redirect()->route('workingInvested.index');

    }
    public function destroyCost(Request $request)
    {
        //
        if ($request->id){
            $investor = WorkingInvestedCapital::findOrFail($request->id);
            $investor->investing_monthly_cost = 0;
            $investor->investing_annual_cost = 0;
            $investor->save();
        }
        return redirect()->route('workingInvested.index')->with('success', __('item_cost_deleted_successfully'));

    }
}
