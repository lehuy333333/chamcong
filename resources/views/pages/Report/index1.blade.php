@extends('templates.master')

@section('title')
    <h3>BÁO CÁO CHẤM CÔNG</h3>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="col-md-3 mb-2 d-print-none">
            {{-- <form action="{{ url('report/show') }}" method="post" class="mb-2">
                @csrf
                <div class="form-group">
                    <label for="month">Chọn tháng</label>
                    <input type="month" name="month" id="month"
                        value="{{ isset($month) ? \Carbon\Carbon::parse($month)->format('Y-m') : '' }}"
                        class="form-control">

                    <label for="department" class="mr-2 ">Chọn phân xưởng</label>
                    <select id="department" name="department" class="mr-2 form-control">
                        @if (Auth::user()->level_id > 2)
                            <option value="{{ Auth::user()->department_id }}">
                                {{ Auth::user()->department->department_name }}</option>
                        @else
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-success">Xem</button>
            </form> --}}

            <div class="form-group">
                <label for="month">Chọn tháng</label>
                <input type="month" name="month" id="month"
                    value="{{ isset($month) ? \Carbon\Carbon::parse($month)->format('Y-m') : '' }}" class="form-control">

                <label for="department" class="mr-2 ">Chọn phân xưởng</label>
                <select id="department" name="department" class="mr-2 form-control">
                    @if (Auth::user()->level_id > 2)
                        <option value="{{ Auth::user()->department_id }}">
                            {{ Auth::user()->department->department_name }}</option>
                    @else
                        @foreach ($departments as $key => $department)
                            @php
                                $tmp_depart = '';
                            @endphp
                            @if (isset($depart))
                                @if ($depart->id == $department->id)
                                    @php
                                        $tmp_depart = 'selected';
                                    @endphp
                                @endif
                            @endif
                            <option value="{{ $department->id }}" {{$tmp_depart}}>{{ $department->department_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <a href="" class="btn btn-sm btn-success" id="link_get_report">Xem</a>
            <script>
                $("#link_get_report").on("click", function(e) {
                    $(this).attr("href", "{{ url('report') }}" + "/" + $("#department").find(":selected").val() + "/" + $(
                        "#month").val());
                });
            </script>

            <ul class="nav nav-pills ">
                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Chính thức</a></li>
                <li class="nav-item"><a class="nav-link" href="#KV" data-toggle="tab">Khoán việc</a></li>
                <!--<li class="nav-item"><a class="nav-link" href="#GT" data-toggle="tab">Giải Trình</a></li>-->
            </ul>

        </div>
        @if (isset($workdates))
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <div class="table-responsive d-print-table">
                        <table class="table table-sm table-bordered" id="table-data-export">
                            <tr class="noborder">
                                @php
                                    $coltop = 13 + $workdates->count();
                                @endphp
                                <td colspan="{{ $coltop }}"
                                    class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
                                    style="text-align: center;vertical-align: middle;">
                                    <div class="text-center">
                                        <h5>BẢNG CHẤM CÔNG - CÔNG TY CỔ PHẦN DỊCH VỤ KỸ THUẬT TÂN CẢNG THÁNG
                                            {{ \Carbon\Carbon::parse($workdates->first()->workdate)->format('m-Y') }}</h5>
                                    </div>
                                </td>
                            </tr>
                            <tr class="noborder">
                                <td colspan="{{ $coltop }}"
                                    class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
                                    style="text-align: center;vertical-align: middle;">
                                    <div class="text-center">
                                        <h6>{{ $depart->department_name }}</h6>
                                    </div>
                                </td>
                            </tr>
                            <tr class="noborder">
                                <td colspan="{{ $coltop }}"
                                    class="text-center  border-top-0  border-right-0 border-bottom-0 border-left-0 noborder"
                                    style="text-align: right;vertical-align: middle;">
                                    <div class="text-right">
                                        Ngày công định mức: <b>{{ $workdaysPayroll }}</b>
                                    </div>
                                </td>
                            </tr>

                            <tr class="table-primary" style="text-align: center">
                                <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center">STT</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center ">MSNV</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center ">Họ Tên</td>
                                @foreach ($workdates as $workdate)
                                    <td class="text-center"
                                        @if ($workdate->isWeekend) style="background-color:#FAFAD2;border-color: #7abaff;" 
                                        @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border-color: #7abaff;"
                                        @else style="background-color:#b8daff;border-color: #7abaff;" @endif>

                                        {{ \Carbon\Carbon::parse($workdate->workdate)->format('d') }}
                                    </td>
                                @endforeach
                                <td style="background-color:#b8daff;border-color: #7abaff;"><b>Cdư Ttrước</b></td>
                                <td style="background-color:#b8daff;border-color: #7abaff;"><b>LV</b></td>

                                <td style="background-color:#b8daff;border-color: #7abaff;">Phép</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;">Ốm</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;"><b>Tổng<b></td>
                                {{-- <td style="background-color:#b8daff;border-color: #7abaff;" ><b>Làm thêm</b></td> --}}
                                <td style="background-color:#b8daff;border-color: #7abaff;"><b>Tổng Công<b></td>
                                <td style="background-color:#b8daff;border-color: #7abaff;">Trực lễ</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;"><b>Cdư Tnày</b></td>
                                <td style="background-color:#b8daff;border-color: #7abaff;">Hệ số</td>
                                <td style="background-color:#b8daff;border-color: #7abaff;">Xếp Loại</td>
                            </tr>

                            @foreach ($payroll_employees as $employee)
                                <tr class="border-primary bg-light">
                                    <th style="border: 1px solid #dee2e6;" rowspan="3" scope="row">
                                        <b>{{ $loop->index + 1 }}</b></td>
                                    <td style="border: 1px solid #dee2e6;" rowspan="3">
                                        {{ Str::upper($employee->employeeID) }}</td>
                                    <td style="border: 1px solid #dee2e6;" rowspan="3">
                                        <b class="text-nowrap">
                                            {{ Str::upper($employee->lastname . ' ' . $employee->firstname) }}</b>
                                    </td>
                                    @php
                                        $totalWorkdate = 0;
                                        $totalOff = 0;
                                        $totalSick = 0;
                                        $totalTL = 0;
                                        $totalOverHour = 0;
                                        $timesheets = $employee
                                            ->timesheets()
                                            ->whereBetween('workdate_id', [$workdates->first()->id, $workdates->last()->id])
                                            ->get()
                                            ->sortBy('workdate_id');
                                    @endphp

                                    @foreach ($workdates as $workdate)
                                        @php
                                            $tmp = null;
                                            //$wsc = 0;
                                        @endphp

                                        @foreach ($timesheets as $timesheet)
                                            @if ($timesheet->workdate_id == $workdate->id)
                                                @php
                                                    $tmp = $timesheet->work_symbol->symbol_id;
                                                    $totalWorkdate += $timesheet->work_symbol->work_symbols_coefficient;
                                                @endphp
                                                @if ($timesheet->work_symbol_id == 9)
                                                    @php
                                                        $totalOff += 1;
                                                    @endphp
                                                @elseif ($timesheet->work_symbol_id == 10)
                                                    @php
                                                        $totalSick += 1;
                                                    @endphp
                                                @elseif ($timesheet->work_symbol_id == 7)
                                                    @php
                                                        $totalTL += 1;
                                                    @endphp
                                                @endif
                                            @endif
                                        @endforeach
                                        @if (!is_null($tmp))
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                {{ $tmp }}</td>
                                        @else
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                            </td>
                                        @endif
                                    @endforeach
                                    @php
                                        $date = \Carbon\Carbon::parse($workdates->first()->workdate);
                                        $start = $date->subMonth();
                                        $reportprevious = $employee
                                            ->reports()
                                            ->where('start_date', $start)
                                            ->first();
                                    @endphp
                                    @php
                                        $report = $employee
                                            ->reports()
                                            ->where('start_date', $workdates->first()->workdate)
                                            ->first();
                                    @endphp
                                    <td style="border: 1px solid #dee2e6;" class="text-center">
                                        <b>{{ isset($reportprevious->total_surplus_workdate) ? round($reportprevious->total_surplus_workdate, 1) : '' }}</b>
                                    </td>
                                    <td style="border: 1px solid #dee2e6;" class="text-center">
                                        <b>{{ $totalWorkdate - $totalOff }}</b>
                                    </td>
                                    @if (!empty($report))
                                        <td style="border: 1px solid #dee2e6;" class="text-center">{{ $totalOff }}
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">{{ $totalSick }}
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ $totalWorkdate }}</b>
                                        </td>
                                        {{-- <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ round($report->total_overtime, 1) }}</b>
                                        </td> --}}
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ round($report->total_timesheet + $reportprevious->total_surplus_workdate, 1) }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">{{ $totalTL }}
                                        </td>
                                        <td style="border: 1px solid #dee2e6;">
                                            <b>{{ round($report->total_surplus_workdate, 1) }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;">{{ $employee->personal_coefficient }}</td>
                                        <td style="border: 1px solid #dee2e6;"></td>
                                    @else
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ $totalOff }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ $totalSick }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ $totalWorkdate }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">

                                        </td>
                                        <td style="border: 1px solid #dee2e6;" class="text-center">
                                            <b>{{ $totalTL }}</b>
                                        </td>
                                        <td style="border: 1px solid #dee2e6;"></td>
                                        <td style="border: 1px solid #dee2e6;">{{ $employee->personal_coefficient }}</td>
                                        <td style="border: 1px solid #dee2e6;"></td>
                                    @endif

                                </tr>
                                <tr>
                                    @php
                                        $totalDuty = 0;
                                    @endphp
                                    @foreach ($workdates as $workdate)
                                        @php
                                            $tmpDuty = false;
                                        @endphp
                                        @foreach ($timesheets as $timesheet)
                                            @if ($timesheet->workdate_id == $workdate->id && $timesheet->duty)
                                                @php
                                                    $tmpDuty = true;
                                                    $totalDuty++;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($tmpDuty)
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                T
                                            </td>
                                        @else
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>

                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;" class="text-center">{{ $totalDuty }}</td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>

                                </tr>
                                <tr>
                                    @foreach ($workdates as $workdate)
                                        @php
                                            $tmpOver = false;
                                            $valOver = 0;
                                        @endphp
                                        @foreach ($timesheets as $timesheet)
                                            @if ($timesheet->workdate_id == $workdate->id && isset($timesheet->overtime))
                                                @php
                                                    $tmpOver = true;
                                                    $valOver = $timesheet->overtime;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($tmpOver)
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                {{ round($valOver, 1) }}
                                            </td>
                                        @else
                                            <td class="text-center"
                                                @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                            </td>
                                        @endif
                                    @endforeach
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;" class="text-center">
                                        <b>{{ isset($report->total_overtime) ? round($report->total_overtime, 1) : 0 }}</b>
                                    </td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>
                                    <td style="border: 1px solid #dee2e6;"></td>

                                </tr>
                            @endforeach
                            @if (Auth::user()->level_id > 1)
                                <tr class="noborder">
                                    @php
                                        $col = 10 + $workdates->count();
                                    @endphp
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>Người lập báo cáo</b></h6>
                                    </td>
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>{{ Auth::user()->department->department_name }}</b></h6>
                                    </td>
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>Phó giám đốc phụ trách</b></h6>
                                    </td>
                                </tr>
                            @else
                                <tr class="noborder">
                                    @php
                                        $col = 10 + $workdates->count();
                                    @endphp
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>Người lập báo cáo</b></h6>
                                    </td>
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>{{ $depart->department_name }}</b></h6>
                                    </td>
                                    <td colspan="{{ $col / 3 }}" style="text-align: center;vertical-align: middle;"
                                        class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                        <h6><b>Giám đốc phụ trách</b></h6>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- <p style="page-break-after: always;">&nbsp;</p>
                <p style="page-break-before: always;">&nbsp;</p> --}}
                <div class="tab-pane" id="KV">
                    @if ($contact_employees->count() > 0)
                        <div class="table-responsive d-print-table">
                            <table class="table table-sm table-bordered" id="table-data-export2">
                                <tr class="noborder">
                                    @php
                                        $coltop2 = 13 + $workdates->count();
                                    @endphp
                                    <td colspan="{{ $coltop2 }}"
                                        class="text-center border-right-0 border-top-0 border-bottom-0 border-left-0 noborder"
                                        style="text-align: center;vertical-align: middle;">
                                        <div class="text-center">
                                            <h5>BẢNG CHẤM CÔNG - CÔNG TY CỔ PHẦN DỊCH VỤ CONTAINER TÂN CẢNG THÁNG
                                                {{ \Carbon\Carbon::parse($workdates->first()->workdate)->format('m-Y') }}
                                            </h5>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="noborder">
                                    <td colspan="{{ $coltop2 }}"
                                        class="text-center border-right-0 border-top-0 border-bottom-0 border-left-0 noborder"
                                        style="text-align: center;vertical-align: middle;">
                                        <div class="text-center">
                                            <h6>{{ $depart->department_name }}</h6>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="noborder">
                                    <td style="text-align: right;vertical-align: middle;"
                                        class="text-center  border-top-0  border-right-0 border-bottom-0 border-left-0 noborder"
                                        colspan="{{ $coltop2 }}">
                                        <div class="text-right">
                                            Ngày công định mức: <b>{{ $workdaysContact }}</b>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="table-primary" style="text-align: center">
                                    <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center">STT
                                    </td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center ">MSNV
                                    </td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;" class="text-center ">Họ
                                        Tên</td>
                                    @foreach ($workdates as $workdate)
                                        <td class="text-center"
                                            @if ($workdate->isWeekend) style="background-color:#FAFAD2;border-color: #7abaff;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border-color: #7abaff;"
                                            @else style="background-color:#b8daff;border-color: #7abaff;" @endif>

                                            {{ \Carbon\Carbon::parse($workdate->workdate)->format('d') }}
                                        </td>
                                    @endforeach
                                    <td style="background-color:#b8daff;border-color: #7abaff;"><b>Cdư Ttrước</b></td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;"><b>LV</b></td>

                                    <td style="background-color:#b8daff;border-color: #7abaff;">Phép</td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;">Ốm</td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;"><b>Tổng<b></td>
                                    {{-- <td style="background-color:#b8daff;border-color: #7abaff;" ><b>Làm thêm</b></td> --}}
                                    <td style="background-color:#b8daff;border-color: #7abaff;"><b>Tổng Công<b></td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;">Trực lễ</td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;"><b>Cdư Tnày</b></td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;">Hệ số</td>
                                    <td style="background-color:#b8daff;border-color: #7abaff;">Xếp Loại</td>
                                </tr>

                                @foreach ($contact_employees as $employee)
                                    <tr class="border-primary bg-light">
                                        <th rowspan="3" scope="row"><b>{{ $loop->index + 1 }}</b></td>
                                        <td rowspan="3">{{ Str::upper($employee->employeeID) }}</td>
                                        <td rowspan="3">
                                            <b class="text-nowrap">
                                                {{ Str::upper($employee->lastname . ' ' . $employee->firstname) }}</b>
                                        </td>
                                        @php
                                            $totalWorkdate = 0;
                                            $totalOff = 0;
                                            $totalSick = 0;
                                            $totalTL = 0;
                                            $totalOverHour = 0;
                                            $timesheets = $employee
                                                ->timesheets()
                                                ->whereBetween('workdate_id', [$workdates->first()->id, $workdates->last()->id])
                                                ->get()
                                                ->sortBy('workdate_id');
                                        @endphp

                                        @foreach ($workdates as $workdate)
                                            @php
                                                $tmp = null;
                                            @endphp

                                            @foreach ($timesheets as $timesheet)
                                                @if ($timesheet->workdate_id == $workdate->id)
                                                    @php
                                                        $tmp = $timesheet->work_symbol->symbol_id;
                                                        $totalWorkdate += $timesheet->work_symbol->work_symbols_coefficient;
                                                    @endphp
                                                    @if ($timesheet->work_symbol_id == 9)
                                                        @php
                                                            $totalOff += 1;
                                                        @endphp
                                                    @elseif ($timesheet->work_symbol_id == 10)
                                                        @php
                                                            $totalSick += 1;
                                                        @endphp
                                                    @elseif ($timesheet->work_symbol_id == 7)
                                                        @php
                                                            $totalTL += 1;
                                                        @endphp
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if (!is_null($tmp))
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                    {{ $tmp }}</td>
                                            @else
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                </td>
                                            @endif
                                        @endforeach
                                        @php
                                            $date = \Carbon\Carbon::parse($workdates->first()->workdate);
                                            $start = $date->subMonth();
                                            $reportprevious = $employee
                                                ->reports()
                                                ->where('start_date', $start)
                                                ->first();
                                        @endphp
                                        @php
                                            $report = $employee
                                                ->reports()
                                                ->where('start_date', $workdates->first()->workdate)
                                                ->first();
                                        @endphp
                                        <td class="text-center">
                                            <b>{{ isset($reportprevious->total_surplus_workdate) ? round($reportprevious->total_surplus_workdate, 1) : '' }}</b>
                                        </td>
                                        <td class="text-center">
                                            <b>{{ $totalWorkdate - $totalOff }}</b>
                                        </td>
                                        @if (!empty($report))
                                            <td class="text-center">{{ $totalOff }}</td>
                                            <td class="text-center">{{ $totalSick }}</td>
                                            <td class="text-center"><b>{{ $totalWorkdate }}</b></td>
                                            {{-- <td class="text-center">
                                                <b>{{ round($report->total_overtime, 1) }}</b>
                                            </td> --}}
                                            <td class="text-center">
                                                <b>{{ round($report->total_timesheet + $reportprevious->total_surplus_workdate, 1) }}</b>
                                            </td>
                                            <td class="text-center">{{ $totalTL }}</td>
                                            <td><b>{{ round($report->total_surplus_workdate, 1) }}</b></td>
                                            <td>{{ $employee->personal_coefficient }}</td>
                                            <td></td>
                                        @else
                                            <td class="text-center"><b>{{ $totalOff }}</b></td>
                                            <td class="text-center"><b>{{ $totalSick }}</b></td>
                                            <td class="text-center">
                                                <b>{{ $totalWorkdate }}</b>
                                            </td>
                                            <td class="text-center">

                                            </td>
                                            <td class="text-center"><b>{{ $totalTL }}</b></td>
                                            <td></td>
                                            <td>{{ $employee->personal_coefficient }}</td>
                                            <td></td>
                                        @endif

                                    </tr>
                                    <tr>
                                        @php
                                            $totalDuty = 0;
                                        @endphp
                                        @foreach ($workdates as $workdate)
                                            @php
                                                $tmpDuty = false;
                                            @endphp
                                            @foreach ($timesheets as $timesheet)
                                                @if ($timesheet->workdate_id == $workdate->id && $timesheet->duty)
                                                    @php
                                                        $tmpDuty = true;
                                                        $totalDuty++;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($tmpDuty)
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                    T
                                                </td>
                                            @else
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                </td>
                                            @endif
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                        <td></td>
                                        <td class="text-center">{{ $totalDuty }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        @foreach ($workdates as $workdate)
                                            @php
                                                $tmpOver = false;
                                                $valOver = 0;
                                            @endphp
                                            @foreach ($timesheets as $timesheet)
                                                @if ($timesheet->workdate_id == $workdate->id && isset($timesheet->overtime))
                                                    @php
                                                        $tmpOver = true;
                                                        $valOver = $timesheet->overtime;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @if ($tmpOver)
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                    {{ round($valOver, 1) }}
                                                </td>
                                            @else
                                                <td class="text-center"
                                                    @if ($workdate->isWeekend) style="background-color:#FAFAD2;border: 1px solid #dee2e6;" 
                                            @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;border: 1px solid #dee2e6;" @endif>
                                                </td>
                                            @endif
                                        @endforeach
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                            <b>{{ isset($report->total_overtime) ? round($report->total_overtime, 1) : 0 }}</b>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                @endforeach
                                @if (Auth::user()->level_id > 1)
                                    <tr class="noborder">
                                        @php
                                            $col = 10 + $workdates->count();
                                        @endphp
                                        <td colspan="{{ $col / 3 }}"
                                            style="text-align: center;vertical-align: middle;"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>Người lập báo cáo</b></h6>
                                        </td>
                                        <td colspan="{{ $col / 3 }}"
                                            style="text-align: center;vertical-align: middle;"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>{{ $depart->department_name }}</b></h6>
                                        </td>
                                        <td colspan="{{ $col / 3 }}"
                                            style="text-align: center;vertical-align: middle;"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>Phó giám đốc phụ trách</b></h6>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="noborder">
                                        @php
                                            $col = 10 + $workdates->count();
                                        @endphp
                                        <td style="text-align: center;vertical-align: middle;"
                                            colspan="{{ $col / 3 }}"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>Người lập báo cáo</b></h6>
                                        </td>
                                        <td style="text-align: center;vertical-align: middle;"
                                            colspan="{{ $col / 3 }}"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>{{ $depart->department_name }}</b></h6>
                                        </td>
                                        <td style="text-align: center;vertical-align: middle;"
                                            colspan="{{ $col / 3 }}"
                                            class="text-center p-5 border-right-0 border-bottom-0 border-left-0 noborder">
                                            <h6><b>Giám đốc phụ trách</b></h6>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif
                </div>

                @if (Auth::user()->level_id > 1)
                    <div class="d-print-none">
                        <a class="btn btn-success" data-toggle="modal" data-target="#confirmReportModal">Tính công</a>
                        <a class="btn btn-primary" id="btnPrint">In</a>
                        <a class="btn btn-danger" download="DuLieuChamCongChinhThuc.xls" href="#"
                            onclick="return ExcellentExport.excel(this, 'table-data-export');"><i
                                class="fa fa-file-excel"></i> Xuất file chính thức </a>
                        <a class="btn btn-warning" download="DuLieuChamCongKhoanViec.xls" href="#"
                            onclick="return ExcellentExport.excel(this, 'table-data-export2');"><i
                                class="fa fa-file-excel"></i> Xuất file khoán việc</a>

                    </div>
                @endif

                <a href="{{url('report/export/'.$depart->id.'/'.$month)}}">asdasdasd</a>
            </div>
        @endif
    </div>

    @if (isset($workdates))
        <div class="modal fade" id="confirmReportModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmReportModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmReportModalLabel">Thông báo!</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <i>Khi <b>xuất báo cáo</b> tháng <b>nhỏ hơn</b> so với hiện tại, sẽ <b>khoá
                                chấm
                                công</b> tháng được
                            xuất báo cáo!
                        </i> --}}
                        <i>Bạn có muốn tính công?</i>
                        <form action="{{ url('report/autoCalculate') }}" method="POST" id="exportReport">
                            @csrf
                            <input type="hidden" name="department" value="{{ isset($depart) ? $depart->id : '' }}">
                            <input type="hidden" name="month" value="{{ isset($month) ? $month : '' }}">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ</button>
                        <a class="btn btn-warning" id="submitButton">Tôi muốn tính công</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        $('#btnExportconfirm').click(function() {
            $('#confirmReportModal').modal('toggle');
        });

        $('#submitButton').click(function(e) {
            e.preventDefault();
            var form = $('#exportReport');
            form.submit();
        });

        $('#btnPrint').click(function(e) {
            window.print();
        });

        // function printDiv() {
        //     var divToPrint = window;
        //     var htmlToPrint = '' +
        //         '<style type="text/css">' +
        //         'table th, table td {' +
        //         'border:1px solid #000;' +
        //         'padding:0.5em;' +
        //         '}' +
        //         '</style>';
        //     htmlToPrint += divToPrint.outerHTML;
        //     newWin = window.open("");
        //     newWin.document.write(htmlToPrint);
        //     newWin.print();
        //     newWin.close();
        // }
    </script>

    <style>
        @media print {
            .card-body {
                font-size: 9px;
                margin: 0;
                padding: 0;
            }

            .table tr td {
                page-break-inside: avoid;
            }

            .table .noborder {
                border: 0 !important;
                border-width: 0 !important;
            }

            .table {
                color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }


            header,
            footer {
                display: none !important;
            }

            @page :footer {
                color: #fff
            }

            @page :header {
                color: #fff
            }

        }

        @page {
            size: A4 landscape;
        }
    </style>


@endsection
