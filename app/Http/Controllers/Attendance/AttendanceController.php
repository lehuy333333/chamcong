<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;
use App\Models\employees;
use App\Models\zkteco_devices;

class AttendanceController extends Controller
{
    public function index()
    {
        $zk = new ZktecoLib('192.168.20.51', '4370');
        if ($zk->connect()) {
            $attendances = $zk->getAttendance();
            return "connect success";
        } else {
            return "connect fail";
        }
    }

    public function listDevice()
    {
        $zkteco_devices = zkteco_devices::all();
        return view('zkteco::app', compact('zkteco_devices'));
    }

    public function addDevice(Request $request)
    {
        $this->validate($request, [
            'ip'  => 'required',
            'port'  => 'required',
            'model_name'  => 'required',
        ]);

        $input = $request->all();
        $zk = new zkteco_devices($input);
    }

    public function syncUser($department_id, $device_id)
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

    public function getAttendanceByDevice($device_id)
    {
        $device = zkteco_devices::find($device_id);
        $zk = new ZktecoLib($device->ip, $device->port);
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            return view('zkteco::app', compact('attendance'));
        }
    }
}
