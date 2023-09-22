<table class="table table-sm table-bordered" id="table-data-export">
    <tr class="noborder">
        <td colspan=6 class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
            style="text-align: center;vertical-align: middle;">
            <div class="text-center">
                <h5><b>BẢNG GIẢI TRÌNH - CÔNG TY CỔ PHẦN DỊCH VỤ KỸ THUẬT TÂN CẢNG THÁNG
                        {{ \Carbon\Carbon::parse($workdates->first()->workdate)->format('m-Y') }}</b></h5>
            </div>
        </td>
    </tr>
    <tr class="noborder">
        <td colspan=6 class="text-center border-right-0  border-top-0 border-bottom-0 border-left-0 noborder"
            style="text-align: center;vertical-align: middle;">
            <div class="text-center">
                <h6><b>{{ $department->department_name }}</b></h6>
            </div>
        </td>
    </tr>

    <tr class="table-primary">
        <td style="background-color:#b8daff;">STT</td>
        <td style="background-color:#b8daff;">Ngày</td>
        <td style="background-color:#b8daff;">MSNV</td>
        <td style="background-color:#b8daff;">Họ Tên</td>
        {{-- @foreach ($workdates as $workdate)
            <td class="text-center"
                @if ($workdate->isWeekend) style="background-color:#FAFAD2;" 
                @elseif ($workdate->isHoliday) style="background-color:#CCFFFF;"
                @else style="background-color:#b8daff;" @endif>

                {{ \Carbon\Carbon::parse($workdate->workdate)->format('d') }}
            </td>
        @endforeach --}}
        <td style="background-color:#b8daff;"><b>Giải trình</b></td>
    </tr>

    @php
        $count = 0;
    @endphp
    @foreach ($department->employees as $employee)
        @php
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
                    @if (@isset($timesheet->explain))
                        @php
                            $count++;
                        @endphp
                        <tr>
                            <td>{{ $count }}</td>
                            <td>{{ \Carbon\Carbon::parse($workdate->workdate)->format('d-m') }}</td>
                            <td>{{ $employee->employeeID }}</td>
                            <td><b>{{ $employee->fullname }}</b></td>
                            <td>{{ $timesheet->explain }}</td>
                        </tr>
                    @endif
                @endif
            @endforeach
        @endforeach
    @endforeach


    {{-- @if (Auth::user()->level_id > 1)
        <tr class="noborder">
            @php
                $col = 6;
            @endphp
            <td colspan="{{ $col / 3 }}">
                <h6><b>Người lập báo cáo</b></h6>
            </td>
            <td colspan="{{ $col / 3 }}">
                <h6><b>{{ Auth::user()->department->department_name }}</b></h6>
            </td>
            <td colspan="{{ $col / 3 }}">
                <h6><b>Phó giám đốc phụ trách</b></h6>
            </td>
        </tr>
    @else
        <tr class="noborder">
            @php
                $col = 6;
            @endphp
            <td colspan="{{ $col / 3 }}">
                <h6><b>Người lập báo cáo</b></h6>
            </td>
            <td colspan="{{ $col / 3 }}">
                <h6><b>{{ $department->department_name }}</b></h6>
            </td>
            <td colspan="{{ $col / 3 }}">
                <h6><b>Giám đốc phụ trách</b></h6>
            </td>
        </tr>   
    @endif --}}
</table>
