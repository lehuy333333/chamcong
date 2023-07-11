<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laradevsbd\Zkteco\Http\Library\ZktecoLib;

class AttendanceController extends Controller
{
    public function index()
    {
        $zk = new ZktecoLib('192.168.20.51', '4370');
        if ($zk->connect()) {
            $attendance = $zk->getAttendance();
            return view('zkteco::app', compact('attendance'));
        }
    }
}
