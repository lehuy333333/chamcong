@extends('templates.master')

@section('title')
    NHẬP CÔNG DƯ THÁNG TRƯỚC
    <form action="{{ url('timesheet/getSurplusMonth') }}" method="get">
        @csrf
        <input type="month" name="month" value="{{ \Carbon\Carbon::parse($start_date)->format('Y-m') }}">
        <button type="submit">chọn</button>
    </form>
@endsection

@section('content')
    <div class="container-fluid mt-3 mb-5 mobi-mt-50 mobi-mb-200">
        <div class="row">
            <div class="col-md-12">
                {{-- @dd($start_date) --}}
                <div class="table-responsive">
                    <table class="table table-striped projects" width="100%" cellspacing="0">
                        <thead style="background-color: rgb(175, 200, 236)">
                            <th class="text-center" style="width: 1%">STT</th>
                            <th class="text-center">MSNV</th>
                            <th class="text-left">Họ</th>
                            <th class="text-left">Tên</th>
                            <th class="text-center">Công dư</th>

                        </thead>

                        <tbody id="empList">
                            @foreach (Auth::user()->department->employees->sortBy('firstname', SORT_LOCALE_STRING) as $employee)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>

                                    <td class="text-center">{{ $employee->employeeID }}</td>
                                    <td class="text-left">{{ $employee->lastname }}</td>
                                    <td class="text-left"><b>{{ $employee->firstname }}</b></td>
                                    {{-- <td>{{ $employee->employee_type->Etype_name }}</td> --}}

                                    <td class="text-center">
                                        @if (isset($employee->reports->where('start_date', $start_date)->first()->total_surplus_workdate))
                                        <input type="text" name="congdu" value="{{$employee->reports->where('start_date', $start_date)->first()->total_surplus_workdate}}">
                                        @else
                                        <input type="text" name="congdu" value="0">
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
                <div class="col-md-12 text-right" id="abba">
                    <a class="btn btn-success" onclick="saveCongdu()">Lưu</a>
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

        function saveCongdu() {
            var arrData = [];
            $("table > tbody > tr").each(function() {
                var objTemp = {
                    empID: $(this).find('td').eq(1).text(),
                    congdu: $(this).find('td').eq(4).find('input').val(),
                    start_date: '{{$start_date}}',
                };
                // alert(JSON.stringify(objTemp));
                arrData.push(objTemp);
            });

            // alert(JSON.stringify(arrData));
            $.ajax({
                url: "{{ url('timesheet/setSurplusMonth') }}",
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
@endsection
