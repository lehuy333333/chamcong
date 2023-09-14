<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\payroll_export;
use App\Exports\contact_export;
use App\Exports\explain_export;

class total_export implements WithMultipleSheets
{

    public function __construct($department_id, $month)
    {
        $this->month = $month;
        $this->department_id = $department_id;
    }
    public function sheets(): array
    {
        return [
            'Khoán việc' =>  new contact_export($this->department_id, $this->month),
            'Chính thức' =>  new payroll_export($this->department_id, $this->month),
            'Giải trình' =>  new explain_export($this->department_id, $this->month),
          
            
        ];
    }
}
