<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskGoal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaskController extends BaseController
{

    public function gantt()
    {
//        if ($this->modules && !in_array("notes", $this->modules)) {
//            abort(401);
//        }

        $tasks = Task::where("workspace_id", $this->user->workspace_id)
            ->whereNotNull('start_date')
            ->whereNotNull('due_date')
            ->get();
//            ->groupBy("status")
//            ->all();
//        $min_date = DB::select('select MIN(start_date) as min_start_date from `tasks` where `workspace_id` = '.$this->user->workspace_id.' and start_date is not null and due_date is not null group by start_date order by start_date asc limit 1;');
//        $max_date = DB::select('select Max(due_date) as max_due_date from `tasks` where `workspace_id` = '.$this->user->workspace_id.' and start_date is not null and due_date is not null group by due_date order by start_date desc limit 1;');
        $min_date = Task::select(DB::raw('MIN(start_date) as min_start_date'))
            ->where('workspace_id',$this->user->workspace_id)
            ->whereNotNull('start_date')
            ->whereNotNull('due_date')
            ->get()->pluck('min_start_date');
//        dd($min_date);
        $max_date = Task::select(DB::raw('Max(due_date) as max_due_date'))
            ->where('workspace_id',$this->user->workspace_id)
            ->whereNotNull('start_date')
            ->whereNotNull('due_date')
            ->get()->pluck('max_due_date');
//        dd($max_date[0]);
        $max_date_string = !empty($max_date) ? $max_date[0] : Carbon::now()->format('Y-m-d');
        $min_date_string = !empty($min_date) ? $min_date[0] : Carbon::parse($max_date_string)->subDays(4)->format('Y-m-d');
//        dd($tasks->toArray(),$min_date_string,$max_date_string);
        $date_list = $this->get3DatesBetweenBoundaries($min_date_string,$max_date_string);
        $users = User::where(
            "workspace_id",
            $this->user->workspace_id
        )->get()->keyBy("id")
            ->all();

//        dd($date_list,$this->getTaskLocations($tasks,$date_list));
        return \view("tasks.gantt", [
            "selected_navigation" => "todos",
            "tasks" => $tasks,
            "tasksLocations" => array_values($this->getTaskLocations($tasks,$date_list)),
            "users" => $users,
            "date_list" => $date_list,
        ]);
    }
    public function getTaskLocations($tasks,$date_list){
        $tasks_object = [];
        foreach ($tasks as $key => $task){
            $start_date = $task->start_date;
            $due_date = $task->due_date;
            if(empty($start_date) || empty($due_date) || $start_date == $due_date){
                unset($tasks[$key]);
                continue;
            }
            $tasks_object[$task->id] = [
                'id'=>$task->id
            ];
            $first_date_before_boundary = '';
            $first_date_after_boundary = '';
            $due_date_before_boundary = '';
            $due_date_after_boundary = '';
            $boundary_dates_Between = [];
            foreach ($date_list as $date_boundary){
                if(Carbon::parse(Carbon::parse($start_date)->toDateString())->eq(Carbon::parse($date_boundary))){
                    $first_date_before_boundary = $date_boundary;
                    $first_date_after_boundary = $date_boundary;
                }
                if(Carbon::parse(Carbon::parse($start_date)->toDateString())->gt(Carbon::parse($date_boundary))){
                    $first_date_before_boundary = $date_boundary;
                }
                if(Carbon::parse(Carbon::parse($start_date)->toDateString())->lt(Carbon::parse($date_boundary)) && $first_date_after_boundary == ''){
                    $first_date_after_boundary = $date_boundary;
                }
            }
            foreach ($date_list as $due_date_key => $date_boundary){
                if(Carbon::parse(Carbon::parse($due_date)->toDateString())->eq(Carbon::parse($date_boundary))){
                    $due_date_before_boundary = $date_boundary;
                    $due_date_after_boundary = $date_boundary;
                }
                if(Carbon::parse(Carbon::parse($due_date)->toDateString())->gt(Carbon::parse($date_boundary))){
                    $due_date_before_boundary = $date_boundary;
                }
                if(Carbon::parse(Carbon::parse($due_date)->toDateString())->lt(Carbon::parse($date_boundary)) && $due_date_after_boundary == ''){
                    $due_date_after_boundary = $date_boundary;
                }
//                if($date_boundary == '2023-05-06' && $task->id == 2) {
//                    dd($first_date_after_boundary, $date_boundary, Carbon::parse($first_date_after_boundary)->lt(Carbon::parse($date_boundary)), empty($due_date_after_boundary));
//                }
                if(Carbon::parse($first_date_after_boundary)->lte(Carbon::parse($date_boundary)) && empty($due_date_after_boundary)){
                    array_push($boundary_dates_Between,$date_boundary);
                }
                if($due_date_key == (count($date_list)-1)){
                    if(Carbon::parse($due_date_before_boundary)->eq(Carbon::parse($due_date_after_boundary))){
                        $tasks_object[$task->id]['due_date_position_pixels_percent'] = (Carbon::parse($due_date)->diffInDays(Carbon::parse($due_date_before_boundary))) / (Carbon::parse($date_list[$due_date_key-1])->diffInDays(Carbon::parse($date_boundary)));
                    }else {
//                        if((Carbon::parse($due_date_before_boundary)->diffInDays(Carbon::parse($due_date_after_boundary))) == 0) {
//                            dd($date_list,$task->toArray());
//                        }
                        $tasks_object[$task->id]['due_date_position_pixels_percent'] = (Carbon::parse($due_date)->diffInDays(Carbon::parse($due_date_before_boundary))) / (Carbon::parse($due_date_before_boundary)->diffInDays(Carbon::parse($due_date_after_boundary)));
                    }
                }
            }
            $tasks_object[$task->id]['first_date_boundaries'] = [$first_date_before_boundary,$first_date_after_boundary];
            if(Carbon::parse($first_date_before_boundary)->eq(Carbon::parse($first_date_after_boundary))){
                $tasks_object[$task->id]['first_date_position_pixels_percent'] = 0;
            }else {
                $tasks_object[$task->id]['first_date_position_pixels_percent'] = (Carbon::parse($start_date)->diffInDays(Carbon::parse($first_date_before_boundary))) / (Carbon::parse($first_date_after_boundary)->diffInDays(Carbon::parse($first_date_before_boundary)));
            }
            $tasks_object[$task->id]['due_date_boundaries'] = [$due_date_before_boundary,$due_date_after_boundary];
            $tasks_object[$task->id]['task_name'] = $task->subject;
            $tasks_object[$task->id]['task_description'] = $task->description;
            $tasks_object[$task->id]['task_status'] = $task->status;
            $tasks_object[$task->id]['start_date'] = Carbon::parse($start_date)->toDateString();
            $tasks_object[$task->id]['due_date'] = Carbon::parse($due_date)->toDateString();
            $tasks_object[$task->id]['between'] = $boundary_dates_Between;
            if(($first_date_before_boundary == $due_date_before_boundary) || ($first_date_after_boundary == $due_date_after_boundary)){
                $tasks_object[$task->id]['in_same_div'] = true;
            }else{
                $tasks_object[$task->id]['in_same_div'] = false;
            }
        }
        return $tasks_object;
//        dd($tasks_object);
    }
    public function get3DatesBetweenBoundaries($start_date,$end_date){
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);
        $diff = $start_date->diffInDays($end_date);
        $start_date->format('Y-m-d');
        $end_date->format('Y-m-d');
        $second_date_position = (int)($diff/2);
        $first_and_third_date_position = (int)($second_date_position/2);
        $second_date = Carbon::parse($start_date)->addDays($second_date_position);
        $third_date = Carbon::parse($end_date)->subDays($first_and_third_date_position);
        $first_date = Carbon::parse($start_date)->addDays($first_and_third_date_position);
//        dd($diff,$start_date->format('Y-m-d'),$first_date->format('Y-m-d'),$second_date->format('Y-m-d'),$third_date->format('Y-m-d'),$end_date->format('Y-m-d'));
        return [$start_date->format('Y-m-d'),$first_date->format('Y-m-d'),$second_date->format('Y-m-d'),$third_date->format('Y-m-d'),$end_date->format('Y-m-d')];
    }
    public function kanban()
    {
//        if ($this->modules && !in_array("notes", $this->modules)) {
//            abort(401);
//        }

        $tasks = Task::where("workspace_id", $this->user->workspace_id)
            ->get()
            ->groupBy("status")
            ->all();


        $users = User::where(
            "workspace_id",
            $this->user->workspace_id
        )->get()->keyBy("id")
            ->all();


        return \view("tasks.kanban", [
            "selected_navigation" => "todos",
            "tasks" => $tasks,
            "users" => $users,
        ]);
    }
    public function setStatus(Request $request)
    {
//        if ($this->modules && !in_array("notes", $this->modules)) {
//            abort(401);
//        }

        $request->validate([
            "id" => "required|integer",
        ]);

        $task = Task::where("workspace_id", $this->user->workspace_id)
            ->where("id", $request->id)
            ->first();

        if ($task) {
            $task->status = $request->status;
            $task->save();
        }

    }
    public function tasksAction($action, Request $request)
    {
        switch ($action) {
            case "list":
                if ($this->modules && !in_array("to_dos", $this->modules)) {
                    abort(401);
                }

                $task = false;

                $view_type = $request->view_type ?? "list";

                if ($request->id) {
                    $task = Task::where(
                        "workspace_id",
                        $this->user->workspace_id
                    )
                        ->where("id", $request->id)
                        ->first();
                }

                $tasks = Task::where(
                    "workspace_id",
                    $this->user->workspace_id
                )->get();
                $tasksGoal = TaskGoal::where(
                    "workspace_id",
                    $this->user->workspace_id
                )->first();
                $users = User::where(
                    "workspace_id",
                    $this->user->workspace_id
                )->get()->keyBy("id")
                    ->all();

                return \view("tasks.list", [
                    "selected_navigation" => "todos",
                    "tasks" => $tasks,
                    "task" => $task,
                    "users" => $users,
                    "view_type" => $view_type,
                    "tasksGoal" => $tasksGoal,
                ]);

                break;

            case "task.json":
                $request->validate([
                    "id" => "required|integer",
                ]);
                if ($request->id) {
                    $task = Task::where(
                        "workspace_id",
                        $this->user->workspace_id
                    )
                        ->where("id", $request->id)
                        ->first();
                }

                if ($task) {
                    return response($task);
                }

                break;
        }
    }

    public function tasksSave($action, Request $request)
    {
        switch ($action) {
            case "task":
                if ($this->modules && !in_array("to_dos", $this->modules)) {
                    abort(401);
                }

                $request->validate([
                    "subject" => "required|max:150",
                    "contact_id" => "nullable|integer",
                ]);

                $task = false;

                if ($request->task_id) {
                    $task = Task::where(
                        "workspace_id",
                        $this->user->workspace_id
                    )
                        ->where("id", $request->task_id)
                        ->first();
                }

                if (!$task) {
                    $task = new Task();
                    $task->uuid = Str::uuid();
                    $task->workspace_id = $this->user->workspace_id;
                    $task->status = "todo";
                }

                $task->subject = $request->subject;
                $task->contact_id = $request->contact_id;
                $task->due_date = $request->due_date;
                $task->start_date = $request->start_date;
                $task->description = $request->description;

                $task->save();

                break;

            case "change-status":
                ray($request->all());

                $request->validate([
                    "id" => "required|integer",
                ]);

                $task = Task::where("workspace_id", $this->user->workspace_id)
                    ->where("id", $request->id)
                    ->first();

                if ($task) {
                    $task->status = $request->status;
                    $task->save();
                }

                break;
        }
    }

    public function tasksGoalSave(Request $request)
    {
        if ($this->modules && !in_array("to_dos", $this->modules)) {
            abort(401);
        }

        $request->validate([
            "main_goal" => "required|string",
        ]);

        $task = false;
        if ($request->task_goal_id) {
            $task = TaskGoal::where(
                "workspace_id",
                $this->user->workspace_id
            )
                ->where("id", $request->task_goal_id)
                ->first();
        }

        if (!$task) {
            $task = new TaskGoal();
            $task->uuid = Str::uuid();
            $task->workspace_id = $this->user->workspace_id;
        }
        $task->description = $request->main_goal;

        $task->save();
    }

    public function show(){
        $tasks = Task::where(
            "workspace_id",
            $this->user->workspace_id
        )->get();
        $taskGoal = TaskGoal::where(
            "workspace_id",
            $this->user->workspace_id
        )->first();
        $selected_navigation = "todos";

        return view('tasks.show', compact('taskGoal', 'tasks', 'selected_navigation'));

    }
}
