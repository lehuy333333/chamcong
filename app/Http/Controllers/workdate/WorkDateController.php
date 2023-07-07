<?php

namespace App\Http\Controllers\Workdate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\workdates;
use Carbon\Carbon;

class WorkdateController extends Controller
{
    public function index(Request $request)
    {
        $year_actived = Carbon::parse($request->session()->get('yearActived') . '-01-01');
        $workdates = workdates::whereBetween('workdate', [$year_actived->copy()->startOfYear(), $year_actived->copy()->endOfYear()])->get();
        return view('pages/WorkDate/index', compact('workdates'));
    }

    public function addWorkdates(Request $request)
    {
        $year_actived = Carbon::parse($request->session()->get('yearActived') . '-01-01');
        $year = Carbon::parse($request->get('year') . '-01-01');
        $start = $year->copy()->startOfYear();
        $end = $year->copy()->endOfYear();

        for ($i = $start; $i <= $end; $i->modify('+1 day')) {
            $workdate = new workdates();
            $workdate->workdate = $i;
            $workdate->work_coefficient = (Carbon::parse($i)->isWeekend()) ? 2 : 1;
            $workdate->isWeekend = (Carbon::parse($i)->isWeekend()) ? 1 : 0;
            $workdate->save();
        }
        $workdates = workdates::whereBetween('workdate', [ $year_actived->copy()->startOfYear(),  $year_actived->copy()->endOfYear()])->get();

        return view('pages/WorkDate/index', compact('workdates'));
    }

    public function updateWorkdate(Request $request)
    {
        $this->validate($request, [
            'workdate'              => 'required',
            'holiday'               => 'required',
        ]);

        $isHoliday = $request->isHoliday;

        $workdate                       = workdates::where('workdate', $request->get('workdate'))->first();
        $workdate->holiday              = trim($request->get('holiday'));
        if ($isHoliday) {
            $workdate->work_coefficient = 3;
            $workdate->isHoliday = 1;
            $workdate->isWeekend = 0;
        } else {
            $workdate->work_coefficient = 2;
            $workdate->isWeekend = 1;
            $workdate->isHoliday = 0;
        }

        $workdate->save();
        $message = 'Thêm ' . $workdate->holiday . '  thành công  !!!! ';

        return redirect()->route('workdate.index')->with(compact('message'));
    }

    public function indexHoliday()
    {
        // $workdate = workdates::where('isHoliday	', '1')->get();
        $workdate = workdates::all();
        return view('pages/WorkDate/holiday', compact('workdate'));
    }

    public function addHoliday(Request $request)
    {
        $this->validate($request, [
            'workdate'              => 'required',
            'work_coefficient'      => 'required',
            'holiday'               => 'required',
        ]);

        $workdate                       = workdates::where('workdate', $request->get('workdate'));
        $workdate->work_coefficient     = trim($request->get('work_coefficient'));
        $workdate->holiday              = trim($request->get('holiday'));
        $workdate->isHoliday            = 1;
        try {
            $workdate->save();
            $message = 'Thêm ' . $workdate->holiday . '  thành công !!!! ';
        } catch (QueryException $e) {
            $message = $workdate->holiday . '  đã tồn tại!!!! ';
        }

        return redirect()->route('workdate.holiday')->with(compact('message'));
    }

    public function updateHoliday(Request $request)
    {
        $workdate                       = workdates::find($request->get('id'));
        $workdate->workdate             = trim($request->get('workdate'));
        $workdate->work_coefficient     = trim($request->get('work_coefficient'));
        $workdate->holiday              = trim($request->get('holiday'));
        $workdate->isHoliday            = 1;
        $workdate->update();

        $message = 'Cập nhật ngày lễ thành công !!!';

        return redirect()->route('workdate.holiday')->with(compact('message'));
    }

    public function deleteHoliday($id)
    {
        $workdate = workdates::findOrFail($id);
        $workdate->delete();
        $message = 'Xóa ngày lễ thành công !!!';
        return redirect()->route('workdate.holiday')->with(compact('message'));
    }
}
