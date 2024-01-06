<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectPlanning\newProjectRevenuePlanningRequest;
use App\Http\Requests\ProjectPlanning\updateProjectRevenuePlanningRequest;
use App\Models\ProjectRevenuePlanning;
use App\Models\ProjectRevenueSource;
use App\Models\Setting;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectRevenuePlanningController extends BaseController
{
    public function index()
    {
        if ($this->modules && !in_array("project_revenue_planning", $this->modules)) {
            abort(403);
        }
        $yearlyIncrease = ProjectRevenuePlanning::where(['workspace_id' => $this->user->workspace_id])->first();
        if ($yearlyIncrease) {
            $yearlyIncrease = $yearlyIncrease->yearly_increasing_percentage;
        } else {
            $yearlyIncrease = 0;
        }

        return \view("project_revenue_planning.index", [
            "selected_navigation" => "project_revenue_planning",
            "yearlyIncrease" => $yearlyIncrease
        ]);
    }
    public function getProjectRevenuePlanningData(Request $request)
    {
        $columns = array(
            array('db' => 'id',      'dt' => 0),
            array('db' => 'name',      'dt' => 1),
            array('db' => 'yearly_increasing_percentage',      'dt' => 2),
            array('db' => 'sourcesCount',      'dt' => 3),
            array('db' => 'tools',    'dt' => 4)
        );

        $draw = (int)$request->draw;
        //        dd($draw,$request->order[0]["dir"]);
        $start = (int)$request->start;
        $length = (int)$request->length;
        $order = (isset($request->order[0]["column"]) && !empty($request->order[0]["column"])) ? $request->order[0]["column"] : 1;
        $direction = (isset($request->order[0]["dir"]) && !empty($request->order[0]["dir"]) && $draw != 1) ? $request->order[0]["dir"] : 'desc';
        $search = isset($request->search) && !empty($request->search) ? trim($request->search["value"]) : '';


        $value = array();

        if (!empty($search)) {
            $count = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' => $this->user->workspace_id])->search($search)
                ->count();
            $suppliers = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' => $this->user->workspace_id])->search($search)
                ->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        } else {
            $count = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' => $this->user->workspace_id])->count();
            $suppliers = ProjectRevenuePlanning::with(['sources'])->where(['workspace_id' => $this->user->workspace_id])->limit($length)->offset($start)->orderBy($columns[$order]["db"], $direction)
                ->get();
        }
        foreach ($suppliers as $index => $item) {
            ProjectRevenuePlanning::$row_id = $index + 1;
            array_push($value, $item->project_revenue_planning_display_data);
        }
        //        dd($value);
        return [
            "draw" => $draw,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => (array)$value,
            "order" => $columns[$order]["db"]
        ];
    }

    public function revenueForecast()
    {
        $projectRevenues = ProjectRevenuePlanning::with(['sources'])
            ->where('workspace_id', $this->user->workspace_id)
            ->get();
        $selected_navigation = "project_revenue_planning";
        $user = User::where('super_admin' , 1)->first();
        $settings_mod = Setting::where('workspace_id' , $user->workspace_id)->get()->keyBy('key');
        if (isset($settings_mod['currency'])) {
            $currency = $settings_mod['currency']->value;
        } else {
            $currency = config('app.currency');
        }
        return view('project_revenue_planning.revenue_forecast', compact('projectRevenues', 'selected_navigation' , 'currency'));
    }

    public function create()
    {
        $projectRevenuePlanning = new ProjectRevenuePlanning();
        return view('project_revenue_planning.create', compact('projectRevenuePlanning'));
    }
    public function store(newProjectRevenuePlanningRequest $request)
    {
        if (!isset($request->source_name)) {

            $validator = Validator::make($request->all(), [
                'main_unit' => 'required|numeric|gt:0',
                'main_unit_price' => 'required|numeric|gt:0'
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => '', 'errors' => $validator->errors()], 400);
            }

            $projectRevenuePlanning = ProjectRevenuePlanning::create([
                'name' => $request->name,
                'main_unit' => $request->main_unit,
                'main_unit_price' => $request->main_unit_price,
                'yearly_increasing_percentage' => $request->yearly_increasing_percentage ?? 0,
                'workspace_id' => $this->user->workspace_id
            ]);
            ProjectRevenueSource::create([
                'name' => $request->name,
                'project_revenue_planning_id' => $projectRevenuePlanning->id,
                'unit' => $request->main_unit,
                'unit_price' => $request->main_unit_price
            ]);
        } else {
            $projectRevenuePlanning = ProjectRevenuePlanning::create([
                'name' => $request->name,
                'yearly_increasing_percentage' => $request->yearly_increasing_percentage ?? 0,
                'workspace_id' => $this->user->workspace_id
            ]);
            foreach ($request->source_name as $key => $source_name) {
                ProjectRevenueSource::create([
                    'name' => $source_name,
                    'project_revenue_planning_id' => $projectRevenuePlanning->id,
                    'unit' => $request->unit[$key],
                    'unit_price' => $request->unit_price[$key]
                ]);
            }
        }
        return response()->json(['msg' => __('projectRevenuePlanningStored'), 'type' => 'success']);
    }
    public function saveYearlyIncreasingPercentage(Request $request)
    {
        $validator = Validator($request->all(), [
            'yearly_increasing_percentage' => 'required|gt:0'
        ]);
        if ($validator->fails()) {
            return redirect()->route('project-revenue-planning.index')->with('error', $validator->getMessageBag()->first());
        }
        ProjectRevenuePlanning::where(['workspace_id' => $this->user->workspace_id])->update(['yearly_increasing_percentage' => $request->yearly_increasing_percentage ?? 0]);
        return redirect()->back()->with('success', __('item_updated_successfully'));
    }
    public function addNewRevenueSource(Request $request)
    {
        $validator = Validator($request->all(), [
            'source_name' => 'required|string|max:255',
            'unit' => 'required|numeric|max:255|min:0',
            'unit_price' => 'required|numeric|max:255|min:0',
        ]);
        if ($validator->fails()) {
            return redirect()->route('revenueForecast')->with('error', $validator->getMessageBag()->first());
        }
        ProjectRevenueSource::create([
            'name' => $request->get('source_name'),
            'unit' => $request->get('unit'),
            'project_revenue_planning_id' => $request->get('ProjectRevenuePlanning'),
            'unit_price' => $request->get('unit_price'),
        ]);
        return redirect()->route('revenueForecast')->with('success', __('projectRevenuePlanningStored'));
    }

    public function edit(ProjectRevenuePlanning $projectRevenuePlanning)
    {
        $projectRevenuePlanning->load('sources');
        //        dd($projectRevenuePlanning->toArray());
        return view('project_revenue_planning.update', compact('projectRevenuePlanning'));
    }

    public function update(updateProjectRevenuePlanningRequest $request, ProjectRevenuePlanning $projectRevenuePlanning)
    {
        if (isset($request->source_name) && !empty($request->source_name)) {
            $projectRevenuePlanning->update([
                'name' => $request->name,
                'main_unit' => 0,
                'main_unit_price' => 0,
            ]);
            $projectRevenuePlanning->sources()->delete();
            foreach ($request->source_name as $key => $source_name) {
                ProjectRevenueSource::create([
                    'name' => $source_name,
                    'project_revenue_planning_id' => $projectRevenuePlanning->id,
                    'unit' => $request->unit[$key],
                    'unit_price' => $request->unit_price[$key]
                ]);
            }
        } else {
            $projectRevenuePlanning->update([
                'name' => $request->name,
                'main_unit' => $request->main_unit,
                'main_unit_price' => $request->main_unit_price,
            ]);
            $projectRevenuePlanning->sources()->delete();
            ProjectRevenueSource::create([
                'name' => $request->name,
                'project_revenue_planning_id' => $projectRevenuePlanning->id,
                'unit' => $request->main_unit,
                'unit_price' => $request->main_unit_price
            ]);
        }
        //        dd(collect($request->all())->flatten(1)->groupBy('y')->toArray());
        return response()->json(['msg' => __('projectRevenuePlanningUpdated'), 'type' => 'success']);
    }
    public function destroy(ProjectRevenuePlanning $projectRevenuePlanning)
    {
        $projectRevenuePlanning->delete();
        return response()->json(['msg' => __('projectRevenuePlanningDeleted'), 'type' => 'success']);
    }
    public function deleteRevenueSource(ProjectRevenueSource $projectRevenueSource)
    {
        $projectRevenueSource->delete();
        return response()->json(['msg' => __('projectRevenueSourceDeleted'), 'type' => 'success']);
    }
}
