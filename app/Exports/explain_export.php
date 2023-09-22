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

class explain_export implements FromView, WithEvents, WithTitle
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
        $full_employees = employees::where('department_id', $this->department_id)->orderBy('firstname')->get();

        // $workdaysContact = app('App\Http\Controllers\timesheet\CalendarController')->getBaseWorkDay(2, $this->month);

        $workdates = workdates::whereBetween('workdate', [$this->from, $this->to])
            ->orderBy('workdate', 'asc')
            ->get();

            

        $department = department::find($this->department_id);

        return view('pages.Report.explain_template', [
            'full_employees' => $full_employees,
            // 'workdaysContact' => $workdaysContact,
            'workdates' => $workdates,
            'department' => $department,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getFont()->setName('Times New Roman');
                $event->sheet->getStyle('2:' . $event->sheet->getHighestRow())->getFont()->setSize(12);
                $event->sheet->getStyle('1:2')->getFont()->setSize(16);
                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $event->sheet->getStyle('A:' . $event->sheet->getHighestColumn())->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle('E4:E' . $event->sheet->getHighestRow())->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('C4:C' . $event->sheet->getHighestRow())->getAlignment()->setHorizontal('left');
                $event->sheet->getStyle('D4:D' . $event->sheet->getHighestRow())->getAlignment()->setHorizontal('left');

                $event->sheet->getDelegate()->getRowDimension(3)->setRowHeight(40);

                $event->sheet->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);

                $event->sheet->calculateColumnWidths();
                $calculatedWidth_b = $event->sheet->getColumnDimension('E')->getWidth();
                $calculatedWidth_c = $event->sheet->getColumnDimension('C')->getWidth();
                $calculatedWidth_d = $event->sheet->getColumnDimension('D')->getWidth();

                $event->sheet->getColumnDimension('E')->setAutoSize(false);
                $event->sheet->getColumnDimension('C')->setAutoSize(false);
                $event->sheet->getColumnDimension('D')->setAutoSize(false);

                $event->sheet->getColumnDimension('E')->setWidth((int) $calculatedWidth_b * 1.2);
                $event->sheet->getColumnDimension('C')->setWidth((int) $calculatedWidth_c * 1.4);
                $event->sheet->getColumnDimension('D')->setWidth((int) $calculatedWidth_d * 1.4);

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
        return 'Giải trình';
    }
}
