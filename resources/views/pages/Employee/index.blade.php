@extends('templates.master')

@section('title')
    <h3>QUẢN LÝ NHÂN VIÊN</h3>
@endsection

@section('content')

    <div class="container-fluid mt-3 mb-5 mobi-mt-50 mobi-mb-200">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header py-3">
                    <form action="{{ url('employee/import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <a class="btn btn-success" href="#" data-toggle="modal"
                            data-target="#addEmployeeModal" id="inserEmployeetButton">
                            <i class="fas fa-plus">Thêm Nhân viên</i>
                        </a>
                        <input type="file" name="employeeImport" id="employeeImport" accept=".xlsx, .csv, .xls" hidden=true
                            onchange="this.form.submit()">
                        <label for="employeeImport"><i class="btn btn-primary">Thêm từ file excel</i></label>

                        <a class="btn btn-success" href="{{ url('employee/export') }}">Xuất dữ liệu</a>
                    </form>
                </div>
                @if (count($employees) > 0)
                    <div class="float-right">
                        <h4>Tổng Cộng: <b>{{ $employees->total() }}</b> CB-CNV</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped projects" id="dataTable" width="100%" cellspacing="0">
                            <thead style="background-color: rgb(175, 200, 236)">
                                <th style="width: 3%">STT</th>
                                <th class="text-center">Mã NV</th>
                                <th class="text-left">Họ</th>
                                <th class="text-left">Tên</th>
                                <th class="text-center">Đơn Vị</th>
                                {{-- <th class="text-center">Chức vụ</th> --}}
                                <th class="text-center">Khối</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    {{-- <tr>
                                        <td class="text-center">{{ $employee->employeeID }}</td>
                                        <td class="text-left">{{ $employee->lastname }}</td>
                                        <td class="text-left"><b>{{ $employee->firstname }}</b></td>
                                        <td class="text-center">{{ $employee->department->department_name }}</td>
                                        <td class="text-center">{{ $employee->position->name }}</td>
                                        <td class="text-center">{{ $employee->employee_type->Etype_name }}</td>
                                        <td class="text-center">
                                            <a href="#" class="show_hidden"><i
                                                    class="fas fa-edit show_hidden_btn"></i>Sửa</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                            <a href="#" class="hidden_form"><i class="fas fa-save"
                                                    style="color: green"></i> |</a>
                                            <a href="#" class="hidden_form"><i class="fas fa-times"
                                                    style="color: grey"></i> |</a>
                                            <a href="../employee/delete/{{ $employee->id }}" data-toggle="modal"
                                                data-target="#confirmModal" class="confirm-action-btn"><i
                                                    class="fas fa-trash" style="color: red">Xóa</i></a>
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <form action="{{ url('/employee/update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $employee->id }}" name="id">
                                            {{-- <input type="hidden" value="{{ $employee->isActive }}" name="isActive"> --}}
                                            <td class="text-center"><p>{{ ($employees->currentPage()-1)*$employees->perPage() + $loop->index + 1 }}</p></td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $employee->employeeID }}</p>
                                                <input type="text" value="{{ $employee->employeeID }}" name="employeeID"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td>
                                                <p class="show_hidden">{{ $employee->lastname }}</p>
                                                <input type="text" value="{{ $employee->lastname }}" name="lastname"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td>
                                                <p class="show_hidden"><b>{{ $employee->firstname }}</b></p>
                                                <input type="email" value="{{ $employee->firstname }}" name="firstname"
                                                    class="hidden_form form-control">
                                            </td>

                                            <td class="text-center">
                                                @if (isset($employee->department))
                                                    <p class="show_hidden">
                                                        {{ $employee->department->department_name }}
                                                    </p>
                                                    <select name="department_id" class="hidden_form form-control">
                                                        <option value="{{ $employee->department->id }}">
                                                            {{ $employee->department->department_name }}</option>
                                                        @foreach ($departments as $department)
                                                            @if ($department->id != $employee->department->id)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->department_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $employee->employee_type->Etype_name }}</p>
                                                <select name="position_id" class="hidden_form form-control">
                                                    <option value="{{ $employee->employee_type->id }}">
                                                        {{ $employee->employee_type->Etype_name }}
                                                    </option>
                                                    @foreach ($Etype as $Etypes)
                                                        @if ($Etypes->id != $employee->employee_type->id)
                                                            <option value="{{ $Etypes->id }}">
                                                                {{ $Etypes->Etype_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                <a href="javascript:void(0);" class="show_hidden"><i
                                                        class="fas fa-edit show_hidden_btn">Sửa</i>
                                                    |</a>
                                                <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-save"
                                                        style="color: green"></i>
                                                    |</a>
                                                <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-times"
                                                        style="color: grey"></i>
                                                    |</a>
                                                <a href="../employee/delete/{{ $employee->id }}" data-toggle="modal"
                                                    data-target="#confirmModal" class="confirm-action-btn"><i
                                                        class="fas fa-trash" style="color: red">Xóa</i></a>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        {{ $employees->links() }}
                    </div>
                @else
                    <div>
                        Chưa có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="Thêm mới"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal"></h5>
                </div>

                <form method="POST" action="{{ url('/employee/add') }}">
                    {{ csrf_field() }}
                    <div class="row form-group" style="margin: 30px 50px 0px 50px">
                        <div class="col-sm-6">
                            <label for="employeeID">MSNV </label>
                            <input type="text" value="{{ old('employeeID') }}" class="form-control form-control-user"
                                placeholder="MSNV " name="employeeID" id="employeeID">
                            @error('employeeID')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="employee_type_id">Khối</label>
                            <select class="form-control form-control-user" name="employee_type_id" id="employee_type_id">
                                <option value="">---Chọn Khối---</option>
                                @foreach ($Etype as $Etypes)
                                    <option value="{{ $Etypes->id }}" @if (old('employee_type_id') == $Etypes->id) selected @endif>
                                        {{ $Etypes->Etype_name }}</option>
                                @endforeach
                            </select>
                            @error('employee_type_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="col-sm-6">
                            <label for="fname">Email</label>
                            <input type="email" value="{{ old('email') }}" class="form-control form-control-user" placeholder="Email"
                                name="email">
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                    </div>

                    <div class="row form-group" style="margin: 10px 50px 0px 50px">
                        <div class="col-sm-6">
                            <label for="lastname">Họ </label>
                            <input type="text" value="{{ old('lastname') }}" class="form-control form-control-user"
                                placeholder="Họ " name="lastname" id="lastname">
                            @error('lastname')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="firstname">Tên </label>
                            <input type="text" value="{{ old('firstname') }}" class="form-control form-control-user"
                                placeholder="Tên " name="firstname" id="firstname">
                            @error('firstname')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row form-group" style="margin: 10px 50px 0px 50px">

                        <div class="col-sm-6">
                            <label for="department_id">Phòng Ban</label>
                            <select class="form-control form-control-user" name="department_id" id="department_id">
                                <option value="">---Chọn Phòng Ban---</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        @if (old('department_id') == $department->id) selected @endif>
                                        {{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-sm-6">
                            <label for="position_id">Chức vụ</label>
                            <select class="form-control form-control-user" name="position_id" id="position_id">
                                <option value="">---Chọn chức vụ---</option>
                                @foreach ($positions as $position)
                                    <option value="{{ $position->id }}"
                                        @if (old('position_id') == $position->id) selected @endif>
                                        {{ $position->position_name }}</option>
                                @endforeach
                            </select>
                            @error('position_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>


                    {{-- <div class="row form-group" style="margin: 10px 50px 0px 50px">
                        <div class="col-sm-6">
                            <label for="fname">Hệ số </label>
                            <input type="text" value="{{ old('personal_coefficient') }}" class="form-control form-control-user" placeholder="Hệ số "
                                name="personal_coefficient">
                            @error('personal_coefficient')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
            
                        
            
                    </div> --}}

                    <div class="row form-group" style="margin: 10px 50px 30px 50px">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary btn-user btn-block">Thêm nhân viên</button>
                            <button type="reset" class="btn btn-secondary btn-user btn-block">Reset</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script> --}}

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
