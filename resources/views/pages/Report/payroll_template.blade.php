<table class="table table-sm table-bordered" id="table-data-export">
    <tr class="noborder">
        @php
            $coltop = 13 + $workdates->count();
        @endphp
        <td colspan="{{ $coltop }}"
            class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
            style="text-align: center;vertical-align: middle;">
            <div class="text-center">
                <h5><b>BẢNG CHẤM CÔNG - CÔNG TY CỔ PHẦN DỊCH VỤ KỸ THUẬT TÂN CẢNG THÁNG
                    {{ \Carbon\Carbon::parse($workdates->first()->workdate)->format('m-Y') }}</b></h5>
            </div>
        </td>
    </tr>
    <tr class="noborder">
        <td colspan="{{ $coltop }}"
            class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
            style="text-align: center;vertical-align: middle;">
            <div class="text-center">
                <h6><b>{{ $department->department_name }}</b></h6>
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

    <tr class="table-primary">
        <td style="background-color:#b8daff;">STT</td>
        <td style="background-color:#b8daff;">MSNV</td>
        <td style="background-color:#b8daff;">Họ Tên</td>
        @foreach ($workdates as $workdate)
            <td class="text-center"
                @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;"
                @else style="background-color:#b8daff;" @endif>

                {{ \Carbon\Carbon::parse($workdate->workdate)->format('d') }}
            </td>
        @endforeach
        <td style="background-color:#b8daff;"><b>Cdư Ttrước</b></td>
        <td style="background-color:#b8daff;"><b>LV</b></td>

        <td style="background-color:#b8daff;">Phép</td>
        <td style="background-color:#b8daff;">Ốm</td>
        <td style="background-color:#b8daff;"><b>Tổng</b></td>
        {{-- <td style="background-color:#b8daff;border-color: #7abaff;" ><b>Làm thêm</b></td> --}}
        <td style="background-color:#b8daff;"><b>Tổng Công</b></td>
        <td style="background-color:#b8daff;">Trực lễ</td>
        <td style="background-color:#b8daff;"><b>Cdư Tnày</b></td>
        <td style="background-color:#b8daff;">Hệ số</td>
        <td style="background-color:#b8daff;">Xếp Loại</td>
    </tr>

    @foreach ($payroll_employees as $employee)
        <tr class="border-primary bg-light">
            <td rowspan="3" scope="row">
                <b>{{ $loop->index + 1 }}</b>
            </td>
            <td rowspan="3">
                {{ Str::upper($employee->employeeID) }}</td>
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
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;" @endif>
                        {{ $tmp }}</td>
                @else
                    <td class="text-center"
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;" @endif>
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
            <td>
                <b>{{ isset($reportprevious->total_surplus_workdate) ? round($reportprevious->total_surplus_workdate, 1) : '' }}</b>
            </td>
            <td>
                <b>{{ $totalWorkdate - $totalOff }}</b>
            </td>
            @if (!empty($report))
                <td>{{ $totalOff }}
                </td>
                <td>{{ $totalSick }}
                </td>
                <td>
                    <b>{{ $totalWorkdate }}</b>
                </td>
                <td>
                    <b>{{ round($report->total_timesheet + $reportprevious->total_surplus_workdate, 1) }}</b>
                </td>
                <td>{{ $totalTL }}
                </td>
                <td>
                    <b>{{ round($report->total_surplus_workdate, 1) }}</b>
                </td>
                <td>{{ $employee->personal_coefficient }}</td>
                <td></td>
            @else
                <td>
                    <b>{{ $totalOff }}</b>
                </td>
                <td>
                    <b>{{ $totalSick }}</b>
                </td>
                <td>
                    <b>{{ $totalWorkdate }}</b>
                </td>
                <td>

                </td>
                <td>
                    <b>{{ $totalTL }}</b>
                </td>
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
                    <td
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;" @endif>
                        T
                    </td>
                @else
                    <td
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;" @endif>
                    </td>
                @endif
            @endforeach
            <td></td>
            <td></td>
            <td></td>

            <td></td>
            <td>{{ $totalDuty }}</td>
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
                    <td
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF" @endif>
                        {{ round($valOver, 1) }}
                    </td>
                @else
                    <td
                        @if ($workdate->isWeekend) style="background-color:#FAFAD2" 
                    @elseif ($workdate->isHoliday) style="background-color:#CCFFFF" @endif>
                    </td>
                @endif
            @endforeach
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
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
            $col = 13 + $workdates->count();
        @endphp
        <td colspan="{{ $col / 4 }}">
            <h6><b>Người lập</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>{{ Auth::user()->department->department_name }}</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>Phòng TCLĐ-TL</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>Ban giám đốc</b></h6>
        </td>
    </tr>
    @else
    <tr class="noborder">
        @php
            $col = 13 + $workdates->count();
        @endphp
        <td colspan="{{ $col / 4 }}">
            <h6><b>Người lập</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>{{ Auth::user()->department->department_name }}</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>Phòng TCLĐ-TL</b></h6>
        </td>
        <td colspan="{{ $col / 4 }}">
            <h6><b>Ban giám đốc</b></h6>
        </td>
    </tr>
    @endif
</table>
