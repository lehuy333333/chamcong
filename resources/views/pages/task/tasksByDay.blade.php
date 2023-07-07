@extends('templates.master')

@section('title')
    <h3>QUẢN LÝ CÔNG VIỆC - {{ \Carbon\Carbon::parse($selectDate)->format('d/m/Y') }}</h3>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <a class="btn btn-success nav-link btn-circle" href="#" data-toggle="modal"
                        data-target="#TaskFormModal" id="inserTaskButton">
                        <i class="fas fa-plus"> Thêm</i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped projects">
                    <thead style="background-color: rgb(175, 200, 236)">
                        <tr>
                            
                            <th>
                                Thiết bị
                            </th>
                            <th>
                                Hạng mục cv
                            </th>
                            <th>
                                Biện pháp sc
                            </th>
                            <th>
                                Bắt đầu
                            </th>
                            <th>
                                kết thúc
                            </th>
                            <th>
                                Gián đoạn
                            </th>
                            <th>
                                Nguyên nhân
                            </th>
                            <th>
                                NV thực hiện
                            </th>
                            <th>
                                Loại sửa chữa
                             </th>
                            <th>
                               Kết quả
                            </th>
                            <th>
                                Xoá
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($tasks->count() > 0)
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="d-none">{{ $task->id }}</td>
                                    <td><input type="text" value="{{ $task->device_name }}" name="device_name"></td>
                                    <td><input type="text" value="{{ $task->name }}" name="name"></td>
                                    <td><input type="text" value="{{ $task->remedies }}" name="remedies"></td>
                                    <td><input type="datetime-local" value="{{ $task->started_at }}" name="started_at">
                                    </td>
                                    <td><input type="datetime-local" value="{{ $task->ended_at }}" name="ended_at"></td>
                                    <td><input type="number" value="{{ $task->interruption_time }}"
                                            name="interruption_time"></td>
                                    <td><input type="text" value="{{ $task->interruption_cause }}"
                                            name="interruption_cause"></td>
                                    <td>
                                        @foreach ($task->employees as $employee)
                                            {{-- {{ $employee->employeeID }}, --}}
                                            {{ $employee->lastname }} {{ $employee->firstname }},
                                        @endforeach
                                        <select class="select2" name="states[]" multiple="multiple">
                                            @foreach (Auth::user()->department->employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->lastname }} {{ $employee->firstname }}  </option>
                                            @endforeach
                                        </select>
                                    </td>
                                  <td><input type="text" value="{{ $task->type_repair }}"
                                        name="type_repair">
                                    </td>
                                    <td><input type="text" value="{{ $task->result }}"
                                        name="result">
                                    </td>
                                    <td>
                                        <a href="{{ url('task/delete?task_id=') . $task->id }}" class="btn btn-danger">X</a>    
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <a class="btn btn-success" onclick="saveTasks()">Lưu</a>
            </div>
            {{-- {{ $level->links('pagination::bootstrap-4') }} --}}
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="TaskFormModal" tabindex="-1" role="dialog" aria-labelledby="Thêm mới"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ url('/task/add') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="titleModal"></h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="txtTaskTotal">Tổng số công việc</label>
                            <input type="text" class="form-control border border-danger" id="txtTaskTotal"
                                name="txtTaskTotal">
                        </div>
                        <input type="hidden" value="{{ $selectDate }}" name="selectDate">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Thêm</button>
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function saveTasks() {
            var arrData = [];
            $("table > tbody > tr").each(function() {
                var objTemp = {
                    id: $(this).find('td').eq(0).text(),
                    device_name: $(this).find('td').eq(1).find('input').val(),
                    name: $(this).find('td').eq(2).find('input').val(),
                    remedies: $(this).find('td').eq(3).find('input').val(),
                    started_at: $(this).find('td').eq(4).find('input').val(),
                    ended_at: $(this).find('td').eq(5).find('input').val(),
                    interruption_time: $(this).find('td').eq(6).find('input').val(),
                    interruption_cause: $(this).find('td').eq(7).find('input').val(),
                    employees: $(this).find('td').eq(8).find('select').val(),
                    type_repair: $(this).find('td').eq(9).find('input').val(),
                    result: $(this).find('td').eq(10).find('input').val(),
                };

                arrData.push(objTemp);
            });

            //alert(JSON.stringify(arrData));
            // sendRequest(arrData);

            $.ajax({
                url: "{{ url('task/update') }}",
                type: 'post',
                data: {
                    _token: "{{ csrf_token() }}",
                    data: arrData
                },
                success: function(result) {
                    alert(JSON.stringify(result));
                    $('#timesheetModalBody').html(result);
                    $('#timesheetModal').modal('toggle');
                }
            });
        }

        // function sendRequest(arrData){
        //     var xhr = new XMLHttpRequest();
        //     var body = JSON.stringify(arrData);
        //     xhr.open("POST", "{{ url('task/update') }}", true);
        //     xhr.setRequestHeader('Content-type', 'application/json');
        //     xhr.send(body);
        // }
    </script>
@endsection
