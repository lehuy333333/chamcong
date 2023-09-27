<?php

namespace App\Http\Controllers\task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\workdates;
use App\Models\tasks;
use App\Models\task_employee;
use Illuminate\Support\Facades\DB;
use App\Imports\ImportTask;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class taskController extends Controller
{
    //
    public function index(Request $request)
    {
        $year_actived = Carbon::parse($request->session()->get('yearActived') . '-01-01');
        $workdates = workdates::whereBetween('workdate', [$year_actived->copy()->startOfYear(), $year_actived->copy()->endOfYear()])->get();

        return view("pages.task.index", compact('workdates'));
    }

    public function tasksByDay($selectDate)
    {
        $workdate = workdates::where('workdate', $selectDate)->first();

        if (Auth::user()->level_id < 3) {
            $tasks = tasks::where('added_on', $selectDate)->get();
            return view("pages.task.tasksByDay")->with(compact('selectDate', 'tasks'));
        } else {
            $tasks = tasks::where('added_on', $selectDate)
                ->where('department_id', Auth::user()->department_id)->get();
            return view("pages.task.tasksByDay")->with(compact('selectDate', 'tasks'));
        }
    }

    public function addTask(Request $request)
    {
        $taskTotal = $request->txtTaskTotal;
        $selectDate = $request->selectDate;

        for ($i = 1; $i <= $taskTotal; $i++) {
            $tasks[] = [
                'added_on' => $selectDate,
                'name' => 'Công việc ' . $i,
                'device_name' => 'Thiết bị',
                'department_id' => Auth::user()->department_id
            ];
        }
        tasks::insert($tasks);

        $tasks = tasks::where('added_on', $selectDate);

        return redirect('task/' . $selectDate);
    }

    public function updateTask(Request $request)
    {
        $data = $request->get('data');

        foreach ($data as $value) {
            $tasks = tasks::updateOrCreate(
                ['id' =>  $value['id']],
                [
                    'name' => $value['name'],
                    'device_name' => $value['device_name'],
                    'remedies' => $value['remedies'],
                    'started_at' => $value['started_at'],
                    'ended_at' => $value['ended_at'],
                    'interruption_time' => $value['interruption_time'],
                    'interruption_cause' => $value['interruption_cause'],
                    'type_repair' => $value['type_repair'],
                    'result' => $value['result']
                ]
            );

            if (isset($value['employees'])) {
                foreach ($value['employees'] as $employee) {
                    $tasks->employees()->attach($employee);
                }
            }
        }

        return $data;
    }

    public function deleteTask(Request $request)
    {
        $task_id = $request['task_id'];
        $task = tasks::find($task_id)->delete();

        return back();
    }

    public function import(Request $request)
    {
        $path = $request->file('taskImport');
        Excel::import(new ImportTask, $path);
        $message = 'Import hạng mục công việc thành công!';
        return back()->with(compact('message'));
    }
}
