<?php

namespace App\Imports;

use App\Models\employees;
use Maatwebsite\Excel\Concerns\ToModel;

class employeeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new employees([
        //     //á
        //     'employeeID' => $row[0],
        //     'lastname' => $row[1],
        //     'firstname' => $row[2],
        //     'email' => $row[3],
        //     'level_id' => $row[4],
        //     'department_id'  => $row[5],
        //     'personal_coefficient'  => $row[6],
        //     'employee_type_id'  => $row[7],
        // ]);

        return new employees([
            //á
            'employeeID' => mb_strtoupper($row[0]),
            'lastname' => mb_strtoupper($row[1]),
            'firstname' => mb_strtoupper($row[2]),
            'position_id' => $row[3],
            'department_id'  => $row[4],
            'employee_type_id'  => $row[5],
        ]);
    }
}
