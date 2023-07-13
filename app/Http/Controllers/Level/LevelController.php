<?php

namespace App\Http\Controllers\Level;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\levels;

class LevelController extends Controller
{
    //***add Level***
    public function addLevel(Request $request){
        $this->validate($request, [
            'level_name'      => 'required',
        ]);

        $level                   = new levels();
        $level->level_name       = trim($request->get('level_name'));
        try{
            $level->save();
            $message = 'Thêm chức vụ' .$level->level_name.'  thành công !!!! ';
        }catch(QueryException $e){
            $message = $level->level_name.' Chức vụ đã tồn tại!!!! ';
        }

       return redirect()->route('level.index')->with(compact('message'));
            
    }

    public function updateLevel(Request $request){
        $level                  = levels::find($request->get('id'));
        $level->level_name            = trim($request->get('name'));
        $level->update();

        $message = 'Sửa chức vụ thành công !!!';

        return redirect()->route('level.index')->with(compact('message'));
            
    }
    //***Danh sách Level***
    public function index(){
        $level = levels::all();
        return view('pages.levels.index', compact('level'));
    }

    //***Xóa Level***
    public function deleteLevel($id){
        $level = levels::findOrFail($id);
        $level->delete();
        $message = 'Xóa chức vụ thành công !!!';
        return redirect()->route('level.index')->with(compact('message'));
            
    }

}
