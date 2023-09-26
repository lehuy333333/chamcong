<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use App\Models\employees;
use App\Models\zkteco_devices;

class AttendanceController extends Controller
{
    // public function index()
    // {
    //     $zk = new ZktecoLib('192.168.20.51', '4370');
    //     if ($zk->connect()) {
    //         $attendances = $zk->getAttendance();
    //         return "connect success";
    //     } else {
    //         return "connect fail";
    //     }
    // }

    public function index()
    {
        $zkteco_devices = zkteco_devices::all();
        // foreach ($zkteco_devices as $key => $device) {
        //     $zk = new ZktecoLib($device->ip, $device->port);
        //     if ($zk->connect()) {
        //         $device->status = true;
        //     } else {
        //         $device->status = false;
        //     }
        //     $device->update();
        // }
        return view('pages/attendance/index', compact('zkteco_devices'));
    }

    public function add(Request $request)
    {
        $this->validate($request, [
            'ip'  => 'required',
            'port'  => 'required',
            'model_name'  => 'required',
        ]);

        $input = $request->all();
        zkteco_devices::create($input);

        return redirect('/attendances/list');
    }

    public function getDeviceById($device_id)
    {
        $zkteco_device = zkteco_devices::find($device_id);

        return view('zkteco::app', compact('zkteco_devices'));
    }

    public function edit(Request $request)
    {
        $this->validate($request, [
            'ip'  => 'required',
            'port'  => 'required',
            'model_name'  => 'required',
        ]);

        $input = $request->all();
        $device = zkteco_devices::findorfail($request->id);
        $device->update($input);
        return redirect('/attendances/list');
    }

    function delete($device_id)
    {
        $zkteco_device = zkteco_devices::find($device_id);

        try {
            $zkteco_device->delete();
            $message = 'Xóa nhân viên thành công !!!';
        } catch (Exception $e) {
            $message = 'Nhân viên này không thể xóa, vui lòng liên hệ admin !!!';
        } 

        return redirect('/attendances/list');
    }

    public function syncUserDevice($department_id, $device_id)
    {
        $device = zkteco_devices::find($device_id);
        $zk = new ZktecoLib($device->ip, $device->port);
        $employees = employees::where('department_id', $department_id);

        if ($zk->connect()) {
            $zk->clearUser();
            foreach ($employees as $employee) {
                $zk->setUser($employee->id, $employee->id, $employee->fullname, '123456', 0);
            }
            return "Add user success";
        } else {
            return "Device not connected";
        }
    }

    public function getAttendancesByDevice($device_id)
    {
        $device = zkteco_devices::find($device_id);
        $zk = new ZktecoLib($device->ip, $device->port);
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            return view('zkteco::app', compact('attendance'));
        }
    }
}
