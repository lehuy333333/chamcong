<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\timesheets;
use App\Models\workdates;
use App\Models\employees;
use App\Models\department;
use App\Models\reports;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\payroll_export;
use App\Exports\contact_export;
use App\Exports\total_export;
use Maatwebsite\Excel\Facades\Excel;


class ReportTimesheet extends Controller
{
    public function index()
    {
        $departments = department::all();
        return view('pages.Report.index1', compact('departments'));
    }

    public function delete()
    {
        $departments = department::all();
        return view('pages.Report.index', compact('departments'));
    }

    public function deleteTimesheet(Request $request)
    {
        $department = department::find($request->department);
        $workdate = workdates::where('workdate', $request->date)->first();
        foreach ($department->employees as $employee) {
            $timesheet = timesheets::where('employee_id', $employee->id)->where('workdate_id', $workdate->id)->delete();
        }
        $message = 'Đã xoá';

        $departments = department::all();
        return view('pages.Report.index', compact('message', 'departments'));
    }

    public function getReport($department_id, $month)
    {
        $month = Carbon::parse($month);
        $depart = department::find($department_id);

        $from = $month->copy()->startOfMonth();
        $to = $month->copy()->endOfMonth();

        $workdates = workdates::whereBetween('workdate', [$from, $to])
            ->orderBy('workdate', 'asc')
            ->get();

        $payroll_employees = employees::where('department_id', $department_id)->where('employee_type_id', 1)->orderBy('firstname')->get();
        $contact_employees = employees::where('department_id', $department_id)->where('employee_type_id', 2)->orderBy('firstname')->get();
        $departments = department::all();

        //1: loại chính thức
        //2: loại khoán việc
        $workdaysPayroll = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(1, $month);
        $workdaysContact = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(2, $month);

        // return view('pages.Report.index', compact('workdates', 'reports', 'workdaysPayroll', 'workdaysContact'));
        return view('pages.Report.index1', compact('workdates', 'payroll_employees', 'contact_employees', 'workdaysPayroll', 'workdaysContact', 'departments', 'month', 'depart'));
    }

    public function export($department_id, $month){
        
        return Excel::download(new total_export($department_id, $month), 'report.xlsx');
    }

    
    public function finalReport(Request $request)
    {
        
        $month = Carbon::parse($request->month);
        $fromMonth = $month->copy()->startOfMonth();
        $toMonth = $month->copy()->endOfMonth();

        $department = department::find($request->department);
        $workdates = workdates::whereBetween('workdate', [$fromMonth, $toMonth])->get();

        foreach ($department->employees as $employee) {

            $xHol_min = 3;
            $xHol_max = 3.9;

            $xWek_min = 2;
            $xWek_max = 2.7;

            $employee->timesheets()->whereBetween('workdate_id', [$workdates->first()->id, $workdates->last()->id])->update(['overtime'=>null]);
            $totalBaseWorkdate = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay($employee->employee_type->id, $month);
            $timesheets = $employee->timesheets()
            ->join('work_symbols', 'timesheets.work_symbol_id', '=', 'work_symbols.id')
            ->join('workdates', 'timesheets.workdate_id', '=', 'workdates.id')
            ->whereBetween('workdate_id', [$workdates->first()->id, $workdates->last()->id])
            ->get();
            
            if ($timesheets->sum('work_symbols_coefficient') < $totalBaseWorkdate) {
                $xWek_min = 1;
                $xWek_max = 1;
                // echo $employee->firstname.'Đủ công </br>';
            }

            $tasks = $employee->tasks()
                ->whereBetween('added_on', [$workdates->first()->workdate, $workdates->last()->workdate])
                ->orderBy('added_on')
                ->get();

            $totalOvertime = 0;

            foreach ($tasks as $key => $task) {

                $interruption_time = (isset($task->interruption_time)?$task->interruption_time:0)/60;


                $checkHol = workdates::where('workdate', $task->added_on)->first()->isHoliday;
                $checkWek = workdates::where('workdate', $task->added_on)->first()->isWeekend;

                $lowTime = Carbon::parse($task->added_on . ' 17:00:00');
                $highTime = Carbon::parse($task->added_on . ' 22:00:00');
                $maxTime = Carbon::parse($task->added_on . ' 06:00:00')->addDays(1);
                $started_at = Carbon::parse($task->started_at);
                $ended_at = Carbon::parse($task->ended_at);

                // if ($this->getWeeks($task, $employee)) {
                $OTByADay = 0;
                $flag = 0;
                $tempCheck = '';

                if ($checkHol) {
                    //  l - n - 22
                    if ($ended_at->floatDiffInHours($highTime, false) > 0) {
                        $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xHol_min;
                        $tempCheck = 'HOL: l - n - 22';
                    }

                    // 22 - l - n - 06
                    else if ($highTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                        $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xHol_max;
                        $tempCheck = 'HOL: 22 - l - n - 06';
                    }

                    //  l - 22 - n - 06
                    else if ($ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) > 0) {
                        $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xHol_min + $highTime->floatDiffInHours($ended_at, false) * $xHol_max;
                        $tempCheck = 'HOL: l - 22 - n - 06';
                    }

                    //  l - 22 - 06 - n
                    else if ($ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) <= 0) {
                        $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xHol_min + $xHol_max * 8;
                        $tempCheck = 'HOL: l - 22 - 06 - n';
                    }
                } else if ($checkWek) {
                    $chk = $employee->timesheets()
                        ->join('work_symbols', 'timesheets.work_symbol_id', '=', 'work_symbols.id')
                        ->join('workdates', 'timesheets.workdate_id', '=', 'workdates.id')
                        ->where('workdate', $task->added_on)
                        ->first();
                    
                    if (isset($chk->work_symbols_coefficient) && $chk->work_symbols_coefficient  > 0) {
                        //  17 - l - n - 22
                        if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($highTime, false) > 0) {
                            $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xWek_min;
                            $tempCheck = 'week 17 - l - n - 21.59<br>';
                        }

                        // 22 - l - n - 06
                        else if ($highTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                            $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xWek_max;
                            $tempCheck = 'week 22 - l - n - 06<br>';
                        }

                        //  17 - l - 22 - n - 06
                        else if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $started_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                            $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xWek_min + $highTime->floatDiffInHours($ended_at, false) * $xWek_max;
                            $tempCheck = 'week 17 - l - 22 - n - 06<br>';
                        }

                        //  17 - l - 22 - 06 - n
                        else if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $started_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) < 0) {
                            $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xWek_min + $xWek_max * 8;
                            $tempCheck = 'week 17 - l - 22 - 06 - n<br>';
                        }

                        // l - 17 - n - 22
                        else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($lowTime, false) <= 0 && $ended_at->floatDiffInHours($highTime, false) > 0) {
                            // $OTByADay = ($lowTime->floatDiffInHours($ended_at, false) - $interruption_time) * 2;
                            $OTByADay = ($lowTime->floatDiffInHours($ended_at, false)) * $xWek_min;
                            $tempCheck = 'week l - 17 - n - 22<br>';
                        }

                        // l - 17 - 22 - n - 06
                        else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                            $OTByADay = (5 - $interruption_time) * 2 + $highTime->floatDiffInHours($ended_at, false) * $xWek_max;
                            $tempCheck = 'week l - 17 - 22 - n - 06<br>';
                        }

                        // l - 17 - 22 - 06 - n
                        else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($maxTime, false) < 0) {
                            $OTByADay = (5 - $interruption_time) * $xWek_min + $xWek_max * 8;
                            $tempCheck = 'week l - 17 - 22 - 06 - n<br>';
                        }
                    }
                    else {
                        //  l - n - 22
                        if ($ended_at->floatDiffInHours($highTime, false) > 0) {
                            $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xWek_min;
                            $tempCheck = 'weekKK l - n - 21.59<br>';
                        }

                        // 22 - l - n - 06
                        else if ($highTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                            $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * $xWek_max;
                            $tempCheck = 'weekKK 22 - l - n - 06<br>' . ($started_at->floatDiffInHours($ended_at, false) - $interruption_time);
                        }

                        //  l - 22 - n - 06
                        else if ($started_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($highTime, false) < 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                            $OTByADay =($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xWek_min + $highTime->floatDiffInHours($ended_at, false) * $xWek_max;
                            $tempCheck = 'weekKK l - 22 - n - 06<br>';
                        }

                        //  l - 22 - 06 - n
                        else if ($ended_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($maxTime, false) < 0) {
                            $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * $xWek_min + $xWek_max * 8;
                            $tempCheck = 'weekKK l - 22 - 06 - n<br>';
                        }
                    }
                } else {
                    //  17 - l - n - 22
                    if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($highTime, false) >= 0) {
                        $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * 1.5;
                        //Cái này còn sai - chưa chỉnh
                        //$flag = 1;
                        $tempCheck = '17 - l - n - 22 day<br>';
                    }

                    // 22 - l - n - 06
                    else if ($highTime->floatDiffInHours($started_at, false) >= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                        //Cái này còn sai - chưa chỉnh
                        // if ($key > 0 && $flag == 1) {
                        //     $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * 2.1;
                        // } else {
                        //     $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * 2;
                        // }
                        $OTByADay = ($started_at->floatDiffInHours($ended_at, false) - $interruption_time) * 2;
                        $tempCheck = '22 - l - n - 06 day<br>';
                    }

                    //  17 - l - 22 - n - 06
                    else if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $started_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                        $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * 1.5 + $highTime->floatDiffInHours($ended_at, false) * 2.1;
                        $tempCheck = '17 - l - 22 - n - 06 day<br>';
                    }

                    //  17 - l - 22 - 06 - n
                    else if ($lowTime->floatDiffInHours($started_at, false) >= 0 && $started_at->floatDiffInHours($highTime, false) > 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) < 0) {
                        $OTByADay = ($started_at->floatDiffInHours($highTime, false) - $interruption_time) * 1.5 + 8 * 2.1;
                        $tempCheck = '17 - l - 22 - 06 - n day<br>';
                    }

                    // l - 17 - n - 22
                    else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($lowTime, false) < 0 && $ended_at->floatDiffInHours($highTime, false) >= 0) {
                        // $OTByADay = ($lowTime->floatDiffInHours($ended_at, false) - $interruption_time) * 1.5;
                        $OTByADay = ($lowTime->floatDiffInHours($ended_at, false)) * 1.5;
                        $tempCheck = 'l - 17 - n - 22 day<br>';
                    }

                    // l - 17 - 22 - n - 06
                    else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) >= 0) {
                        $OTByADay = (5 - $interruption_time) * 1.5 + $highTime->floatDiffInHours($ended_at, false) * 2.1;
                        $tempCheck = 'l - 17 - 22 - n - 06 day<br>';
                    }

                    // l - 17 - 22 - 06 - n
                    else if ($lowTime->floatDiffInHours($started_at, false) < 0 && $ended_at->floatDiffInHours($highTime, false) <= 0 && $ended_at->floatDiffInHours($maxTime, false) < 0) {
                        $OTByADay = (5 - $interruption_time) * 1.5 + 8 * 2.1;
                        $tempCheck = 'l - 17 - 22 - 06 - n day<br>';
                    }
                }

                //Lưu xuống timesheet -> overtime của NV ----- START
                $emp_timesheet = $employee->timesheets()->where('workdate_id', workdates::where('workdate', $task->added_on)->value('id'))->first();

                if(isset($emp_timesheet->overtime) && $OTByADay>0){
                    $emp_timesheet->overtime = $emp_timesheet->overtime + $OTByADay/8;
                }elseif(!isset($emp_timesheet->overtime) && $OTByADay>0){
                    $emp_timesheet->overtime = $OTByADay/8;
                }
                $emp_timesheet->save();
                //-----Lưu xuống timesheet -> overtime của NV-----END

                $totalOvertime = $totalOvertime + $OTByADay/8;
                
                // để test theo từng người
                // if ($employee->id == 305) {
                //     echo $employee->id . '///' . $task->started_at . '------' . $task->ended_at . ' --------> ' . $totalOvertime . '<br>';
                //     echo $interruption_time. '-----'.($OTByADay/8). '<br>';
                //     echo $tempCheck. '<br>';
                // }
                // để test theo từng người

            }
            // echo $totalOvertime.'<br>';
            
            $works = $employee->timesheets()
                ->join('work_symbols', 'timesheets.work_symbol_id', '=', 'work_symbols.id')
                ->join('workdates', 'timesheets.workdate_id', '=', 'workdates.id')
                ->whereBetween('workdate_id', [$workdates->first()->id, $workdates->last()->id])
                ->get();

            $tmp_work_special_day=0;

            foreach ($works as $work) {
                if ($work->work_symbols_coefficient >=2) {
                    $tmp_work_special_day += $work->work_symbols_coefficient; 
                }
            }

            $totalWorkdate = $works->sum('work_symbols_coefficient');

            if ($totalWorkdate >= $totalBaseWorkdate) {
                $totalWorkdate = ($totalWorkdate - $tmp_work_special_day - $totalBaseWorkdate) * 2 + $totalBaseWorkdate + $tmp_work_special_day;
            }

            // if($employee->id == 290){
            //     echo($tmp_work_special_day.'------'.$totalWorkdate);
            // }

            $date = \Carbon\Carbon::parse($workdates->first()->workdate);
            $reportprevious = $employee
                ->reports()
                ->where('start_date', $date->subMonth())
                ->first();

            if(isset($reportprevious)){
                $total_surplus_workdate = $reportprevious->total_surplus_workdate;
            }else{
                $total_surplus_workdate = 0;
            }

            $totalWorkdate = $totalWorkdate + $totalOvertime;

            $totalSurplusWorkdate = $total_surplus_workdate + $totalWorkdate - $totalBaseWorkdate;

            if ($totalSurplusWorkdate <= 0) {
                $totalSurplusWorkdate = 0;
            }

            $report = reports::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'start_date' => $fromMonth,
                ],
                [
                    'end_date' => $toMonth,
                    'total_overtime' => $totalOvertime,
                    'total_timesheet' => $totalWorkdate,
                    'total_base_workdate' => $totalBaseWorkdate,
                    'total_surplus_workdate' =>  $totalSurplusWorkdate,
                ]
            );
        };

        $message = 'Đã hoàn tất chấm công';

        return redirect('/report/'.$department->id.'/'.$month->toDateString())->with('message', 'Đã hoàn tất chấm công tháng '.$month->month.'/'.$month->year);
    }

}
