<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\position;

class PositionController extends Controller
{
    public function addPosition(Request $request){
        $this->validate($request, [
            'position_name'      => 'required',
        ]);

        $position                   = new position();
        $position->position_name             = trim($request->get('position_name'));
        try{
            $position->save();
            $message = 'Thêm chức vụ' .$position->position_name.'  thành công !!!! ';
        }catch(QueryException $e){
            $message = $position->position_name.' Chức vụ đã tồn tại!!!! ';
        }

       return redirect()->route('position.index')->with(compact('message'));
            
    }

    public function updatePosition(Request $request){
        $position                  = position::find($request->get('id'));
        $position->position_name            = trim($request->get('name'));
        $position->update();

        $message = 'Sửa chức vụ thành công !!!';

        return redirect()->route('position.index')->with(compact('message'));
            
    }

    public function index(){
        $positions = position::all();
        return view('pages.position.index', compact('positions'));
    }

    public function deletePosition($id){
        $position = position::findOrFail($id);
        $position->delete();
        $message = 'Xóa chức vụ thành công !!!';
        return redirect()->route('position.index')->with(compact('message'));
            
    }

}
