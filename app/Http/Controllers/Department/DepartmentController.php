<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\department;

class DepartmentController extends Controller
{
    function getDepartment(){
        $data = department::where('id', '>', 0)->paginate(15);
        return view("pages.department.list1")->with(compact('data'));
        
    }

    public function jsonDepartmentById(Request $request){
        $department_id = $request->get('department_id');
        $department = department::find($department_id);

        return response()->json($department);
    }
    


    public function updateDepartment(Request $request){
        $department                  = department::find($request->get('id'));
        $department->department_name            = trim($request->get('name'));
        $department->department_code            = trim($request->get('code'));
        $department->update();

        $message = 'Sửa đơn vị thành công !!!';

        return redirect()->route('department.index')->with(compact('message'));
            
    }



    //***Xóa Department***
    public function deleteDepartment($id){
        $department = department::findOrFail($id);
        $department->delete();
        $message = 'Xóa đơn vị'.' ' .$department->department_name.' '. 'thành công !!!';
        return redirect()->route('department.index')->with(compact('message'));
            
    }

    public function addDepartment(Request $request){
        
        $this->validate(request(), [
            'department_name' => 'required',
        ]);

        //$department_id = $request->get('department_id');
        $values = [
            'department_name' => trim($request->get('department_name')),
            'department_code' => trim($request->get('department_code')),
        ];

        
            department::create($values);
            $message = '"'.trim($request->get('department_name')).'" Thêm thành công';
        

        $request->session()->flash('message', $message);

        return back();
    }
}
