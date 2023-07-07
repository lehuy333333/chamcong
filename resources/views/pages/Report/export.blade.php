<table>
    <tr>
        <td colspan="{{ 9 + $workdates->count() }}">
            <h5>BẢNG CHẤM CÔNG KHỐI CHÍNH THỨC THÁNG
                {{ \Carbon\Carbon::parse($workdates->first()->workdate)->format('m-Y') }}</h5>
        </td>
    </tr>
    <tr>
        <td colspan="{{ 8 + $workdates->count() }}"></td>
        <td>Ngày công định mức:
            <b>{{ $workdaysPayroll }}</b></td>
    </tr>
    <tr>
        <td >STT</td>
        <td>MSNV</td>
        <td >Họ Tên</td>
        @foreach ($workdates as $workdate)
            <td>
                {{ \Carbon\Carbon::parse($workdate->workdate)->format('d') }}
            </td>
        @endforeach

        <td>Tổng</td>
        <td>Phép</td>
        <td>Ốm</td>
        <td>Công dư</td>
        <td>Hệ số</td>
        <td>Xếp loại</td>
    </tr>

    @foreach ($payroll_employees as $employee)
        <tr>
            <th rowspan="3" scope="row"><b>{{ $loop->index + 1 }}</b></td>
            <td rowspan="3">{{ Str::upper($employee->employeeID) }}</td>
            <td rowspan="3">
                <b>
                    {{ Str::upper($employee->lastname . ' ' . $employee->firstname) }}</b>
            </td>
            @php
                $totalWorkdate = 0;
                $totalOff = 0;
                $totalSick = 0;
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
                        @endif
                    @endif
                @endforeach
                @if (!is_null($tmp))
                    <td >{{ $tmp }}</td>
                @else
                    <td ></td>
                @endif
            @endforeach

            @php
                $report = $employee
                    ->reports()
                    ->where('start_date', $workdates->first()->workdate)
                    ->first();
            @endphp

            @if (!empty($report))
                <td>
                    <b>{{ $report->total_timesheet }}</b>
                </td>
                <td><b>{{ $totalOff }}</b></td>
                <td><b>{{ $totalSick }}</b></td>
                <td><b>{{ $report->total_surplus_workdate }}</b></td>
                <td>{{ $employee->personal_coefficient }}</td>
                <td></td>
            @else
                <td>
                    <b>{{ $totalWorkdate }}</b>
                </td>
                <td><b>{{ $totalOff }}</b></td>
                <td><b>{{ $totalSick }}</b></td>
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
                    <td>T</td>
                @else
                    <td></td>
                @endif
            @endforeach
            <td>{{ $totalDuty }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            @php
                $totalOver = 0;
            @endphp
            @foreach ($workdates as $workdate)
                @php
                    $tmpOver = 0;
                @endphp
                @foreach ($timesheets as $timesheet)
                    @if ($timesheet->workdate_id == $workdate->id)
                        @if ($timesheet->overtime != null)
                            @php
                                $tmpOver = $tmpOver + $timesheet->overtime;
                                $totalOver = $totalOver + $tmpOver;
                            @endphp
                        @endif
                    @endif
                @endforeach
                @if ($tmpOver > 0)
                    <td>{{ $tmpOver }}</td>
                @else
                    <td></td>
                @endif
            @endforeach
            <td>{{ $totalOver }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
</table>