<?php

namespace App\Exports;

use App\Models\timesheets;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\workdates;
use App\Models\employees;
use App\Models\department;
use App\Models\reports;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class contact_export implements FromView, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $month;
    protected $department_id;
    use RegistersEventListeners;

    public function __construct($department_id, $month)
    {
        $this->month = Carbon::parse($month);

        $this->from = $this->month->copy()->startOfMonth();
        $this->to = $this->month->copy()->endOfMonth();

        $this->department_id = $department_id;
    }


    public function view(): View
    {
        $contact_employees = employees::where('department_id', $this->department_id)->where('employee_type_id', 2)->orderBy('firstname')->get();

        $workdaysContact = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(2, $this->month);

        $workdates = workdates::whereBetween('workdate', [$this->from, $this->to])
            ->orderBy('workdate', 'asc')
            ->get();

        $department = department::find($this->department_id);

        return view('pages.Report.contact_template', [
            'contact_employees' => $contact_employees,
            'workdaysContact' => $workdaysContact,
            'workdates' => $workdates,
            'department' => $department,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getFont()->setName('Times New Roman');
                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getFont()->setSize(8);
                $event->sheet->getStyle('A5:A100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('B5:B100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('C5:C100')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $contact_employees = employees::where('department_id', $this->department_id)->where('employee_type_id', 2)->orderBy('firstname')->get();

                $event->sheet->getStyle('A:'.$event->sheet->getHighestColumn())->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('3')->getAlignment()->setHorizontal('right');
                $event->sheet->getStyle('B5:B100')->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('C5:C100')->getAlignment()->setHorizontal('left');

                for ($i = 'A'; $i != $event->sheet->getHighestColumn(); $i++) {
                    $event->sheet->getColumnDimension($i)->setWidth(4.5);
                }
                $event->sheet->getColumnDimension($event->sheet->getHighestColumn())->setWidth(4.5);

                $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(40);
                $event->sheet->getStyle('4')->getAlignment()->setWrapText(true);
                
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);

                for ($i = 'A'; $i != $event->sheet->getHighestColumn(); $i++) {
                    $event->sheet->getStyle($i . '4:' . $i . $contact_employees->count() * 3 + 4)
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                $event->sheet->getStyle($event->sheet->getHighestColumn() . '4:' . $event->sheet->getHighestColumn() . $contact_employees->count() * 3 + 4)
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }

    public function title(): string
    {
        return 'Khoán việc';
    }
}
