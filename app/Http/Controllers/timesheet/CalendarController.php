<?php

namespace App\Http\Controllers\Timesheet;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\work_symbols;
use App\Models\timesheets;
use App\Models\workdates;
use App\Models\employees;
use App\Models\reports;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\timesheetExport;
use Auth;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $year_actived = Carbon::parse($request->session()->get('yearActived') . '-01-01');
        $workdates = workdates::whereBetween('workdate', [$year_actived->copy()->startOfYear(), $year_actived->copy()->endOfYear()])->get();
        
        return view("pages.timesheet.Clickcalendar", compact('workdates'));
    }

    public function deleteTimesheet($request)
    {
        $workdate = $request->get('wworkdate');
        $department = $request->get('department');
    }

    public function timesheet($selectDate)
    {
        $workdate = workdates::where('workdate', $selectDate)->first();

        if (is_null($workdate)) {
            $message = 'Ngày làm việc chưa khởi tạo';
            return redirect()->back()->with('message', $message);
        } elseif ($workdate->isLock) {
            $message = 'Ngày làm việc đã khoá chấm công';
            return redirect()->back()->with('message', $message);
        } else {

            $workSymbols = work_symbols::all();
            $data = DB::table('timesheets')

                ->leftJoin('workdates', 'workdate_id', '=', 'workdates.id')
                ->leftJoin('employees', 'employee_id', '=', 'employees.id')
                ->leftJoin('work_symbols', 'work_symbol_id', '=', 'work_symbols.id')

                ->where('workdates.workdate', $selectDate)
                ->where('employees.department_id', Auth::user()->department_id)
                ->where('employees.deleted_at', Null)
                ->select(
                    'timesheets.*',
                    'employees.firstname',
                    'employees.lastname',
                    'employees.employeeID',
                    'workdates.workdate',
                    'work_symbols.id as work_symbol_id',
                    'work_symbols.symbol_id',
                )
                ->orderBy('employees.firstname', 'asc')
                ->get();

            //1: loại chính thức
            //2: loại khoán việc
            $workdaysPayroll = $this->getBaseWorkDay(1, $selectDate);
            $workdaysContact = $this->getBaseWorkDay(2, $selectDate);
            return view("pages.timesheet.timesheet")->with(compact('selectDate', 'workSymbols', 'data', 'workdaysPayroll', 'workdaysContact'));
        }
    }


    public function timekeeping(Request $request)
    {
        $data = $request->get('data');
        $actionDate = $data[0]['workDate'];

        $workdate = workdates::where('workdate', $actionDate)->first();

        $totalW = 0;
        $totalP = [];
        $totalOm = [];
        $totalCT = [];
        $totalT = [];
        $totalNB = [];
        $totalKH = [];
        $totalAbsence = [];
        $totalTS = [];
        $totalDA = [];

        foreach ($data as $value) {
            switch ($value['workSymbol']) {
                case 9:
                    array_push($totalP, $value);
                    break;
                case 10:
                    array_push($totalOm, $value);
                    break;
                case 8:
                    array_push($totalCT, $value);
                    $totalW += 1;
                    break;
                case 7:
                    array_push($totalT, $value);
                    break;
                case 1: case 2: case 3: case 4: case 5: case 6:
                    $totalW += 1;
                    break;
                case 11:
                    array_push($totalAbsence, $value);
                    break;
                case 13:
                    array_push($totalKH, $value);
                    break;
                case 12:
                    array_push($totalDA, $value);
                    break;
                case 14:
                    array_push($totalTS, $value);
                    break;
                default:
                    break;
            };
            
            if($value['duty'] == true){
                array_push($totalT, $value);
            };

            $employee = employees::where('employeeID', $value['empID'])->first();

            $timesheet = timesheets::updateOrCreate(
                ['workdate_id' =>  $workdate->id, 'employee_id' => $employee->id],
                [
                    'work_symbol_id' => $value['workSymbol'],
                    'work_coefficient' => $workdate->work_coefficient,
                    'explain' => $value['explain'],
                    //'overtime' => $value['overtime'],
                    'duty' => $value['duty']
                ]
            );
        }

        $message = 'BÁO CÁO QUÂN SỐ  "'.$employee->department->department_name.'" ngày ' . Carbon::parse($actionDate)->format('d/m/Y')
        . '<br><b>1. Quân số làm việc: '.$totalW.'/'.$employee->department->employees->count().'</b>';

        $message .= '<br><b>2. Quân số công tác: '.count($totalCT).'</b>';
        foreach ($totalCT as $ct) {
            $emp_ct = employees::where('employeeID', $ct['empID'])->first();
            $message .= '<br>- '.$emp_ct->lastname . ' ' . $emp_ct->firstname . ', ';
        }
        
        $message .= '<br><b>3. Quân số dự án: '.count($totalDA).'</b>';
        foreach ($totalDA as $da) {
            $emp_da = employees::where('employeeID', $da['empID'])->first();
            $message .= '<br>- '.$emp_da->lastname . ' ' . $emp_da->firstname . ', ';
        }

        $message .= '<br><b>4. Quân số vắng: '. (count($totalP) + count($totalOm)  + count($totalAbsence) + count($totalTS)).'</b>';

        $message .= '<br>- Phép (' . count($totalP) .'): ';
        foreach ($totalP as $p) {
            $emp_p = employees::where('employeeID', $p['empID'])->first();
            $message .= $emp_p->lastname . ' ' . $emp_p->firstname . ', ';
        }
        $message.= '<br>- Ốm ('. count($totalOm) .'): ';
        foreach ($totalOm as $om) {
            $emp_om = employees::where('employeeID', $om['empID'])->first();
            $message.= $emp_om->lastname . ' ' . $emp_om->firstname . ', ';
        }
        // $message .= '<br>- Nghỉ bù (' . count($totalNB) .'): ';
        // foreach ($totalNB as $nb) {
        //     $emp_nb = employees::where('employeeID', $nb['empID'])->first();
        //     $message .= $emp_nb->lastname . ' ' . $emp_nb->firstname . ', ';
        // }
        $message .= '<br>- Nghỉ thai sản (' . count($totalTS) .'): ';
        foreach ($totalTS as $ts) {
            $emp_ts = employees::where('employeeID', $ts['empID'])->first();
            $message .= $emp_ts->lastname . ' ' . $emp_ts->firstname . ', ';
        }
        $message .= '<br>- Kế hoạch (' . count($totalKH) .'): ';
        foreach ($totalKH as $kh) {
            $emp_kh = employees::where('employeeID', $kh['empID'])->first();
            $message .= $emp_kh->lastname . ' ' . $emp_kh->firstname . ', ';
        }
        $message .= '<br>- Nghỉ (' . count($totalAbsence) .'): ';
        foreach ($totalAbsence as $a) {
            $emp_a = employees::where('employeeID', $a['empID'])->first();
            $message .= $emp_a->lastname . ' ' . $emp_a->firstname . ', ';
        }
        $message.= '<br><b>5. Quân số trực: '.count($totalT). '<br>- '.'</b>';
        foreach ($totalT as $t) {
            $emp_t = employees::where('employeeID', $t['empID'])->first();
            $message .= $emp_t->lastname . ' ' . $emp_t->firstname . ', ';
        }

        return $message;
    }

    public function getBaseWorkDay($classWorker, $date)
    {
        //1: loại chính thức
        //2: loại khoán việc
        
        $date = Carbon::parse($date);
        $start = $date->copy()->firstOfMonth();
        $end = $date->copy()->endOfMonth();
        $satCount = 0;

        $workdate_count = workdates::whereBetween('workdate', [$start, $end])
        ->where('isHoliday', 0)
        ->where('isWeekend', 0)->count();

        for ($i = $start; $i <= $end; $i->modify('+1 day')) {
            $carbon = Carbon::parse($i);
            if ($carbon->dayOfWeek == 6) {
                $checkSatHol = workdates::where('workdate', $carbon)->first();
                if ($checkSatHol->isWeekend) {
                    $satCount += 1;
                }
            }
        }

        if ($classWorker == 2) {
            $workdate_count += ($satCount/2);
        }

        return $workdate_count;
    }


    // public function export()
    // {
    //     return Excel::download(new timesheetExport, 'timesheet.xlsx');
    // }

    public function getMonthSurplus(Request $request)
    {
        if(isset($request->month)){
            $start_date = Carbon::parse($request->month)->startOfMonth();
        }else{
            $start_date = new Carbon('first day of last month');
        }
        $start_date = $start_date->toDateString();
        return view("pages.timesheet.surpluse")->with(compact('start_date'));
    }
    

    public function setSurplusMonth(Request $request)
    {
        $data = $request->get('data');

        foreach ($data as $value) {

            $employee = employees::where('employeeID', $value['empID'])->first();

            $report = reports::updateOrCreate(
                ['start_date' =>  $value['start_date'], 'employee_id' => $employee->id],
                [
                    'total_surplus_workdate' => $value['congdu']
                ]
            );
        }

        $message = 'Xong';

        return $message;
    }
}
