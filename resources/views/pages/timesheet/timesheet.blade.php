@extends('templates.master')

@section('title')
    CHẤM CÔNG
    "{{ \Carbon\Carbon::parse($selectDate)->format('d/m/Y') }}"
@endsection

@section('content')
    <div class="container-fluid mt-3 mb-5 mobi-mt-50 mobi-mb-200">
        <div class="row">
            <div class="col-md-12">
                <p>Công định mức tháng <b>{{ \Carbon\Carbon::parse($selectDate)->format('m') }} </b></p>
                <p>
                    <li>Ngày công Chính thức: <b>{{ $workdaysPayroll }}</b></li>
                </p>
                <p>
                    <li>Ngày công Khoán việc: <b>{{ $workdaysContact }}</b></li>
                </p>
                <div class="table-responsive">
                    <table class="table table-striped projects" width="100%" cellspacing="0">
                        <thead style="background-color: rgb(175, 200, 236)">
                            <th class="text-center" style="width: 1%">STT</th>
                            <th class="text-center">MSNV</th>
                            <th class="text-left">Họ</th>
                            <th class="text-left">Tên</th>
                            {{-- <th class="text-center">Khối</th> --}}
                            <th class="text-center">Chấm công
                                <select name="selectAll" id="select-all">
                                    @foreach ($workSymbols as $workSymbol)
                                        <option value="{{ $workSymbol->id }}">
                                            {{ $workSymbol->symbol_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </th>
                            <th class="text-center">Giải trình</th>
                            <th class="text-center">Trực</th>
                        </thead>
                        
                        <tbody id="empList">
                            @foreach (Auth::user()->department->employees->sortBy('firstname', SORT_LOCALE_STRING) as $emp)
                                @php
                                    $tmp_check = false;
                                    $tmp_employee;
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td class="text-center">{{ $emp->employeeID }}</td>
                                    <td class="text-left">{{ $emp->lastname }}</td>
                                    <td class="text-left"><b>{{ $emp->firstname }}<b></td>
                                    @if ($data->count())
                                        @foreach ($data as $employee)
                                            @if ($employee->employeeID == $emp->employeeID)
                                                @php
                                                    $tmp_check = true;
                                                    $tmp_employee = $employee;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    @if ($tmp_check)
                                        <td class="text-center">
                                            <select name="checkSelect" class="checkSelect">
                                                @isset($tmp_employee->work_symbol_id)
                                                    <option value="{{ $tmp_employee->work_symbol_id }}">
                                                        {{ $tmp_employee->symbol_id }}
                                                    </option>
                                                @endisset
                                                @foreach ($workSymbols as $workSymbol)
                                                    @if ($workSymbol->id != $tmp_employee->work_symbol_id)
                                                        <option value="{{ $workSymbol->id }}">
                                                            {{ $workSymbol->symbol_id }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="explain" value="{{ $tmp_employee->explain }}">
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-primary dutyCheck"
                                                name="duty">{{ $tmp_employee->duty ? 1 : 0 }}</a>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <select name="checkSelect" class="checkSelect">
                                                @foreach ($workSymbols as $workSymbol)
                                                    <option value="{{ $workSymbol->id }}">{{ $workSymbol->symbol_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="explain">
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-primary dutyCheck" name="duty">0</a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="col-md-12 text-right" id="abba">
                    <a class="btn btn-success" onclick="saveTimesheet()">Lưu</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="timesheetModal" tabindex="0" role="dialog" aria-labelledby="messageLabel"
        aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageLabel">Thông báo!</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="timesheetModalBody">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .hidden_form {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $(".dutyCheck").on("click", function() {
            $(this).text($(this).text() == '0' ? '1' : '0');
        });

        function saveTimesheet() {
            var arrData = [];
            $("table > tbody > tr").each(function() {
                var objTemp = {
                    empID: $(this).find('td').eq(1).text(),
                    workSymbol: $(this).find('td').eq(4).find('select').val(),
                    explain: $(this).find('td').eq(5).find('input').val(),
                    duty: $(this).find('td').eq(6).text().replace(/(\r\n|\n|\r)/gm, "").trim(),
                    workDate: '{{ $selectDate }}'
                };

                arrData.push(objTemp);
            });

            // alert(JSON.stringify(arrData));

            $.ajax({
                url: "{{ url('timesheet/timekeeping') }}",
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    data: arrData
                },
                success: function(result) {
                    $('#timesheetModalBody').html(result);
                    $('#timesheetModal').modal('toggle');
                }
            });
        }
    </script>
    <script>
        $('#select-all').change(function(event) {
            $('.checkSelect').val($('#select-all').val()).change();
        });
    </script>
@endsection
