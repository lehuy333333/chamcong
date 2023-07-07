<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\employee_type;

class EmployeeTypeController extends Controller
{
    public function addEmployee_type(Request $request){
        $this->validate($request, [
            'employee_type'      => 'required',
        ]);

        $Etype                   = new employee_type();
        $Etype->employee_type    = trim($request->get('level_name'));
        try{
            $Etype->save();
            $message = 'Thêm khối' .$Etype->employee_type.'  thành công !!!! ';
        }catch(QueryException $e){
            $message = $Etype->employee_type.' Khối đã tồn tại!!!! ';
        }

       return redirect()->route('Etype.index')->with(compact('message'));
            
    }

    public function updateEmployee_type(Request $request){
        $Etype                  = employee_type::find($request->get('id'));
        $Etype->level_name            = trim($request->get('employee_type'));
        $Etype->update();

        $message = 'Sửa khối thành công !!!';

        return redirect()->route('Etype.index')->with(compact('message'));
            
    }
    //***Danh sách Khối***
    public function index(){
        $Etype = employee_type::all();
        return view('pages.Employee_type.index', compact('Etype'));
    }

    //***Xóa Khối***
    public function deleteEmployee_type($id){
        $Etype = employee_type::findOrFail($id);
        $Etype->delete();
        $message = 'Xóa chức vụ thành công !!!';
        return redirect()->route('level.index')->with(compact('message'));
            
    }
}
