@extends('templates.master')

@section('title')
    <h3>QUẢN LÝ HỆ SỐ TỔ</h3>
@endsection

@section('content')

    <div class="container-fluid mt-3 mb-5 mobi-mt-50 mobi-mb-200">
        <div class="row">
            <div class="col-md-12">
                @if (count($employees) > 0)
                    <div class="float-right">
                        <h4>Tổng Cộng: <b>{{ $employees->count() }}</b> CB-CNV</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped projects" width="100%" cellspacing="0">
                            <thead style="background-color: rgb(175, 200, 236)">
                                <th style="width: 3%">STT</th>
                                <th class="text-center">Mã NV</th>
                                <th class="text-left">Họ</th>
                                <th class="text-left">Tên</th>
                                {{-- <th class="text-center">Khối</th> --}}
                                <th class="text-center">Hệ số tổ</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <form action="{{ url('/employee/updateperson') }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $employee->id }}" name="id">
                                            {{-- <input type="hidden" value="{{ $employee->isActive }}" name="isActive"> --}}
                                            <input type="hidden" value="{{ $employee->department_id }}"
                                                name="department_id">
                                            <input type="hidden" value="{{ $employee->position_id }}" name="position_id">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $employee->employeeID }}</p>
                                                <input type="text" value="{{ $employee->employeeID }}" name="employeeID"
                                                    class="hidden_form form-control" readonly="readonly">
                                            </td>
                                            <td>
                                                <p class="show_hidden">{{ $employee->lastname }}</p>
                                                <input type="text" value="{{ $employee->lastname }}" name="lastname"
                                                    class="hidden_form form-control" readonly="readonly">
                                            </td>
                                            <td>
                                                <p class="show_hidden"><b>{{ $employee->firstname }}</b></p>
                                                <input type="email" value="{{ $employee->firstname }}" name="firstname"
                                                    class="hidden_form form-control" readonly="readonly">
                                            </td>

                                            {{-- <td class="text-center">
                                                <p>{{ $employee->employee_type->Etype_name }}</p>
                                                <select name="level_id" class="hidden_form form-control">
                                                    <option value="{{ $employee->employee_type->id }}">
                                                        {{ $employee->employee_type->Etype_name }}
                                                    </option>
                                                    @foreach ($Etype as $Etypes)
                                                        @if ($Etypes->id != $employee->employee_type->id)
                                                            <option value="{{ $Etypes->id }}">{{ $Etypes->Etype_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td> --}}

                                            <td class="text-center">
                                                <p class="show_hidden"><b>{{ $employee->personal_coefficient }}</b></p>
                                                <input type="email" value="{{ $employee->personal_coefficient }}"
                                                    name="personal_coefficient" class="hidden_form form-control">
                                            </td>

                                            <td class="text-center">
                                                <a href="javascript:void(0)" class="show_hidden"><i
                                                        class="fas fa-edit show_hidden_btn">Sửa</i>
                                                </a>
                                                <a href="javascript:void(0)" class="hidden_form"><i class="fas fa-save"
                                                        style="color: green"></i>
                                                    |</a>
                                                <a href="javascript:void(0)" class="hidden_form"><i class="fas fa-times"
                                                        style="color: grey"></i>
                                                </a>

                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div>
                        Chưa có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <style>
        .hidden_form {
            display: none;
        }
    </style>

    <script>
        $(".show_hidden_btn").click(function() {
            $(this).closest('tr').find('.hidden_form').fadeIn("fast");
            $(this).closest('tr').find('.show_hidden').fadeOut(1);
        });

        $(".fa-times").click(function() {
            $(this).closest('tr').find('.hidden_form').fadeOut(1);
            $(this).closest('tr').find('.show_hidden').fadeIn("fast");
        });

        $(".fa-save").click(function() {
            $(this).closest('tr').find('form').submit();
        });
    </script>


@endsection
