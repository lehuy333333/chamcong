<?php

namespace App\Exports;

use App\Models\timesheets;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\workdates;
use App\Models\employees;
use App\Models\department;
use App\Models\reports;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;

class TimesheetExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($month, $department) {
        $this->month = $month;
    	$month = Carbon::parse($month);
        $this->from = $month->copy()->startOfMonth();
        $this->to = $month->copy()->endOfMonth();

        $this->department = $department;

    }


    public function view(): View
    {
        $payroll_employees = employees::where('department_id', $this->department)->where('employee_type_id', 1)->orderBy('firstname')->get();
        $contact_employees = employees::where('department_id', $this->department)->where('employee_type_id', 2)->orderBy('firstname')->get();

        $workdaysPayroll = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(1, $this->month);
        $workdaysContact = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(2, $this->month);

        $workdates = workdates::whereBetween('workdate', [$this->from, $this->to])
        ->orderBy('workdate', 'asc')
        ->get();

        return view('pages.Report.export', [
            'payroll_employees' =>  $payroll_employees,
            'contact_employees' => $contact_employees,
            'workdaysPayroll' => $workdaysPayroll,
            'workdaysContact' => $workdaysContact,
            'workdates' => $workdates,
        ]);
    }
    
}   
