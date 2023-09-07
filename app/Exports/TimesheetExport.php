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
use Maatwebsite\Excel\Sheet;

class TimesheetExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $month;
    protected $department_id;

    public function __construct($department_id,$month) {
        $this->month = Carbon::parse($month);

        $this->from = $this->month->copy()->startOfMonth();
        $this->to = $this->month->copy()->endOfMonth();

        $this->department_id = $department_id;
    }


    public function view(): View
    {
        $payroll_employees = employees::where('department_id', $this->department_id)->where('employee_type_id', 1)->orderBy('firstname')->get();
        $contact_employees = employees::where('department_id', $this->department_id)->where('employee_type_id', 2)->orderBy('firstname')->get();

        $workdaysPayroll = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(1, $this->month);
        $workdaysContact = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(2, $this->month);

        $workdates = workdates::whereBetween('workdate', [$this->from, $this->to])
        ->orderBy('workdate', 'asc')
        ->get();

        $department = department::find($this->department_id);

        return view('pages.Report.export', [
            'payroll_employees' =>  $payroll_employees,
            'contact_employees' => $contact_employees,
            'workdaysPayroll' => $workdaysPayroll,
            'workdaysContact' => $workdaysContact,
            'workdates' => $workdates,
            'department' => $department,
        ]);
    }

    public function registerEvents(): array
        {
            return [
                AfterSheet::class => function(AfterSheet $event) {
                  // multi cols
                  $event->sheet->getStyle('1')->getAlignment()->setHorizontal('center');
                  // single col
                  $event->sheet->getStyle('2')->getAlignment()->setHorizontal('right');
                },
            ];
        }
    
}   
