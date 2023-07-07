<?php

namespace App\Http\Controllers\Employee;

use App\Exports\timesheetExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\department;
use App\Models\employees;
use App\Models\position;
use App\Models\employee_type;
use App\Imports\employeeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Auth;

class EmployeeController extends Controller
{
    function getEmployeeList()
    {
        $employees      = employees::orderBy('firstname', 'asc')->paginate(20);
        $positions      = position::all();
        $departments    = department::where('id', '>', 0)->get();
        $Etype          = employee_type::where('id', '>', 0)->get();

        return view('pages/Employee/index')->with(compact('employees', 'positions', 'departments', 'Etype'));
    }

    function getEmployeePersonal()
    {
        // $employees       = employees::all();
        $Etype          = employee_type::where('id', '>', 0)->get();
        $employees = DB::table('employees')->orderBy('firstname', 'ASC')
            ->select('employees.*')
            ->where('employees.department_id', Auth::user()->department_id)
            ->get();
        return view('pages/Employee/personal')->with(compact('employees', 'Etype'));
    }

    function getEmployeeById(Request $id)
    {

        $employee       = employees::find($id)->first();

        return view('pages/employee/profile1')->with(compact('employee'));
    }

    function addEmployee(Request $request)
    {

        $this->validate($request, [
            'employeeID'  => 'required',
            'firstname'  => 'required',
            'lastname'  => 'required',
            // 'email'     => 'required|email',
            'position_id'  => 'required',
            'department_id'  => 'required',
            // 'personal_coefficient'  => 'required',
            'employee_type_id'  => 'required',

        ]);
        // dd($request);
        try {
            $employee                           =   new employees();
            $employee->employeeID               =   mb_strtoupper(trim($request->input('employeeID')));
            $employee->firstname                =   mb_strtoupper(trim($request->input('firstname')));
            $employee->lastname                 =   mb_strtoupper(trim($request->input('lastname')));
            // $employee->email                    =   trim($request->input('email'));
            $employee->position_id                 =   $request->input('position_id');
            $employee->department_id            =   $request->input('department_id');
            // $employee->personal_coefficient     =   trim($request->input('personal_coefficient'));
            $employee->employee_type_id         =   $request->input('employee_type_id');


            $employee->save();
            $message = ' Thêm nhân viên' . ' ' . $employee->firstname . ' ' . 'thành công !!!! ';
        } catch (Exception $e) {
            $message = $e;
        }
        return redirect('/employee/index')->with(compact('message'));
    }

    function updateEmployee(Request $request)
    {
        $employee_id                            =   $request->input('id');
        try {
            $employee                           =   employees::find($employee_id);
            $employee->employeeID               =   mb_strtoupper(trim($request->input('employeeID')));
            $employee->firstname                =   mb_strtoupper(trim($request->input('firstname')));
            $employee->lastname                 =   mb_strtoupper(trim($request->input('lastname')));
            // $employee->email                    =   trim($request->input('email'));
            $employee->position_id                 =   $request->input('position_id');
            $employee->department_id            =   $request->input('department_id');
            // $employee->personal_coefficient     =   trim($request->input('personal_coefficient'));
            // $employee->employee_type_id         =   trim($request->input('employee_type_id'));
            $employee->save();
            $message = 'Cập nhật nhân viên' . ' ' . $employee->name . ' ' . 'thành công !!!';
        } catch (Exception $e) {
            $message = 'Email đã tồn tại trong hệ thống';
        }
        return redirect('/employee/index')->with(compact('message'));
    }

    function updatepersonal_coefficient(Request $request)
    {
        $employee_id                            =   $request->input('id');
        try {
            $employee                           =   employees::find($employee_id);
            $employee->employeeID               =   mb_strtoupper(trim($request->input('employeeID')));
            $employee->firstname                =   mb_strtoupper(trim($request->input('firstname')));
            $employee->lastname                 =   mb_strtoupper(trim($request->input('lastname')));
            // $employee->email                    =   trim($request->input('email'));
            $employee->position_id              =   $request->input('position_id');
            $employee->department_id            =   $request->input('department_id');
            if (trim($request->input('personal_coefficient')) != null) {
                $employee->personal_coefficient     =   trim($request->input('personal_coefficient'));
            } else {
                $employee->personal_coefficient     =   null;
            }
            $employee->save();
            $message = 'Cập nhật hệ số nhân viên' . ' ' . $employee->lastname . ' ' . $employee->firstname  . ' ' . 'thành công !!!';
        } catch (Exception $e) {
            $message = 'Email đã tồn tại trong hệ thống';
        }
        return redirect('/employee/personal')->with(compact('message'));
    }

    function deleteEmployee($id)
    {
        $employee                    =   employees::find($id);

        try {
            $employee->delete();
            $message = 'Xóa nhân viên thành công !!!';
        } catch (Exception $e) {
            $message = 'Nhân viên này không thể xóa, vui lòng liên hệ admin !!!';
        }

        return redirect('/employee/index')->with(compact('message'));
    }


    // public function export_csv(Request $request){
    //     return Excel::download(new UsersExport($request) , 'nhanvien.xlsx');

    //     // $message = 'Export nhân viên thành công!';
    //     // return back()->with(compact('message'));
    // }

    public function import(Request $request)
    {
        $path = $request->file('employeeImport')->getRealPath();
        Excel::import(new employeeImport, $path);
        $message = 'Import nhân viên thành công!';
        return back()->with(compact('message'));
    }


    public function export()
    {
        return Excel::download(new timesheetExport, 'timesheet.xlsx');
    }
}
