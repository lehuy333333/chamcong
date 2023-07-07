<?php

namespace App\Http\Controllers\Timesheet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\work_symbols;

class WorkSymbolController extends Controller
{
    public function addSymbol(Request $request)
    {
        $this->validate($request, [
            'symbol_name'      => 'required',
            // 'symbol_id'      => 'required',
            'description' => 'required',
        ]);

        $symbol                     = new work_symbols();
        $symbol->symbol_id          = trim($request->get('symbol_id'));
        $symbol->symbol_name        = trim($request->get('symbol_name'));
        $symbol->description        = trim($request->get('description'));
        try {
            $symbol->save();
            $message = 'Thêm ký hiệu' . $symbol->symbol_name . '  thành công !!!! ';
        } catch (QueryException $e) {
            $message = $symbol->symbol_id . ' Ký hiệu đã tồn tại!!!! ';
        }

        return redirect()->route('symbol.index')->with(compact('message'));
    }


    public function updateSymbol(Request $request)
    {
        $symbol                  = work_symbols::find($request->get('id'));
        $symbol->symbol_id       = trim($request->get('symbol_id'));
        $symbol->symbol_name     = trim($request->get('symbol_name'));
        $symbol->description     = trim($request->get('description'));
        $symbol->update();

        $message = 'Sửa ký hiệu thành công !!!';

        return redirect()->route('symbol.index')->with(compact('message'));
    }
    //***Danh sách Ký Hiệu***
    public function index()
    {
        $symbol = work_symbols::all();
        return view('pages.WorkSymbol.index', compact('symbol'));
    }

    //***Xóa Ký Hiệu***
    public function deleteSymbol($id)
    {
        $symbol = work_symbols::findOrFail($id);
        $symbol->delete();
        $message = 'Xóa ký hiệu thành công !!!';
        return redirect()->route('symbol.index')->with(compact('message'));
    }
}
