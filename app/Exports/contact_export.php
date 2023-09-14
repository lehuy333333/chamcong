<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\workdates;
use App\Models\employees;
use App\Models\department;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
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
                $event->sheet->getStyle('2:' . $event->sheet->getHighestRow())->getFont()->setSize(10);
                $event->sheet->getStyle('1:2')->getFont()->setSize(16);
                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $contact_employees = employees::where('department_id', $this->department_id)->where('employee_type_id', 2)->orderBy('firstname')->get();

                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('3')->getAlignment()->setHorizontal('right');
                $event->sheet->getStyle('B5:B' . $event->sheet->getHighestRow())->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('C5:C' . $event->sheet->getHighestRow())->getAlignment()->setHorizontal('left');

                for ($i = 'A'; $i != $event->sheet->getHighestColumn(); $i++) {
                    if (trim($event->sheet->getCell($i . '4')->getValue()) === "Cdư Ttrước" || trim($event->sheet->getCell($i . '4')->getValue()) === "Tổng Công" 
                    || trim($event->sheet->getCell($i . '4')->getValue()) === "Tổng" || trim($event->sheet->getCell($i . '4')->getValue()) === "Cdư Tnày"
                    || trim($event->sheet->getCell($i . '4')->getValue()) === "LV") {
                        $event->sheet->getColumnDimension($i)->setWidth(6.5);
                    }elseif(trim($event->sheet->getCell($i . '4')->getValue()) === "Phép" || trim($event->sheet->getCell($i . '4')->getValue()) === "Trực lễ"){
                        $event->sheet->getColumnDimension($i)->setWidth(5);
                    }
                    else {
                        $event->sheet->getColumnDimension($i)->setWidth(4);
                    }
                }
                $event->sheet->getColumnDimension($event->sheet->getHighestColumn())->setWidth(4.5);

                $event->sheet->getDelegate()->getRowDimension(4)->setRowHeight(40);
                $event->sheet->getStyle('4')->getAlignment()->setWrapText(true);

                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);

                $event->sheet->calculateColumnWidths();
                $calculatedWidth_b = $event->sheet->getColumnDimension('B')->getWidth();
                $calculatedWidth_c = $event->sheet->getColumnDimension('C')->getWidth();

                $event->sheet->getColumnDimension('B')->setAutoSize(false);
                $event->sheet->getColumnDimension('C')->setAutoSize(false);

                $event->sheet->getColumnDimension('B')->setWidth((int) $calculatedWidth_b * 1.2);
                $event->sheet->getColumnDimension('C')->setWidth((int) $calculatedWidth_c * 1.4);

                for ($i = 'A'; $i != $event->sheet->getHighestColumn(); $i++) {
                    $event->sheet->getStyle($i . '4:' . $i . $contact_employees->count() * 3 + 4)
                        ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                $event->sheet->getStyle($event->sheet->getHighestColumn() . '4:' . $event->sheet->getHighestColumn() . $contact_employees->count() * 3 + 4)
                    ->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->getPageSetup()
                    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getPageSetup()
                    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

                $event->sheet->getProtection()->setPassword('DVKT');
                $event->sheet->getProtection()->setSheet(true);
            },
        ];
    }

    public function title(): string
    {
        return 'Khoán việc';
    }
}
