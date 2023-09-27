<?php

namespace App\Imports;

use App\Models\tasks;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\employees;
use App\Models\department;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;

class ImportTask implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.0' => 'required|date_format:Y/m/d',
            '*.5' => 'required|date_format:Y/m/d H:i:s',
            '*.6' => 'required|date_format:Y/m/d H:i:s',
        ])->validate();


        foreach ($rows as $row) {
            $dep = department::where('department_code', $row[1])->first();
            if (isset($dep)) {
                $numItems = count($row);
                $task =  tasks::create([
                    'added_on' => $row[0],
                    'department_id' => $dep->id,
                    'device_name' => $row[2],
                    'name' => $row[3],
                    'remedies' => $row[4],
                    'started_at' => $row[5],
                    'ended_at' => $row[6],
                    'interruption_time' => $row[7],
                    'interruption_cause' => $row[8],
                    'type_repair' => $row[9],
                    'result' => $row[10],
                ]);

                for ($i = 11; $i < $numItems; $i++) {
                    $emp = employees::where('employeeID', $row[$i])->first();
                    $task->employees()->attach($emp);
                }
            }
        }
    }
}
