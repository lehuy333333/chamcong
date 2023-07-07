@extends('templates.master')

@section('title')
    QUẢN LÝ TÀI KHOẢN
@endsection

@section('content')

    <div class="container-fluid mt-3 mb-5 mobi-mt-50 mobi-mb-200">
        <div class="row">
            <div class="col-md-12">

                {{-- <div class="table-buttons mb-3">
                    <button class="btn btn-success" data-toggle="modal" data-target="#registerModal">

                        <i class="fa fa-plus mr-2">Thêm tài khoản</i>

                    </button>
                </div> --}}
                <div class="card-header py-3">
                    <div class="d-flex">
                        <div class="mr-auto p-2">
                            <a class="btn btn-success nav-link btn-circle" href="#" data-toggle="modal"
                                data-target="#registerModal" id="insertUserButton">
                                <i class="fas fa-plus">Thêm Tài khoản</i>
                            </a>
                        </div>
                    </div>
                </div>

                @if (count($users))
                    <div class="float-right">
                        <h4>Tổng Cộng: <b>{{ $users->total() }}</b> tài khoản</h4>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped projects" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: rgb(175, 200, 236)">
                            <th class="text-center" style="width: 3%">No.</th>
                            <th class="text-center" style="width: 15%">UserName</th>
                            <th class="text-center" style="width: 15%">Họ tên</th>
                            <th class="text-center" style="width: 15%">Email</th>
                            <th class="text-center" style="width: 10%">Chức Vụ</th>
                            <th class="text-center" style="width: 15%">Đơn Vị</th>
                            <th class="text-center" style="width: 15%">Action</th>

                        </thead>
                        <tbody>
                            {{-- @if (isset(Auth::user()->department->users)) --}}
                                @foreach ($users as $user)
                                    <tr>
                                        <form action="{{ url('/users/update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $user->id }}" name="id">
                                            <input type="hidden" value="{{ $user->isActive }}" name="isActive">
                                            <td class="text-center">{{ ($users->currentPage()-1)*$users->perPage() + $loop->index + 1 }}</td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $user->username }}</p>
                                                <input type="text" value="{{ $user->username }}" name="username"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $user->fullname }}</p>
                                                <input type="text" value="{{ $user->fullname }}" name="fullname"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td class="text-center">
                                                <p class="show_hidden">{{ $user->email }}</p>
                                                <input type="email" value="{{ $user->email }}" name="email"
                                                    class="hidden_form form-control">
                                            </td>

                                            <td class="text-center">
                                                <p class="show_hidden">{{ $user->level->level_name }}</p>
                                                <select name="level_id" class="hidden_form form-control">
                                                    <option value="{{ $user->level->id }}">
                                                        {{ $user->level->level_name }}
                                                    </option>
                                                    @foreach ($levels as $level)
                                                        @if ($level->id != $user->level->id)
                                                            <option value="{{ $level->id }}">{{ $level->level_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                @if (isset($user->department))
                                                    <p class="show_hidden">{{ $user->department->department_name }}
                                                    </p>
                                                    <select name="department_id" class="hidden_form form-control">
                                                        <option value="{{ $user->department->id }}">
                                                            {{ $user->department->department_name }}</option>
                                                        @foreach ($departments as $department)
                                                            @if ($department->id != $user->department->id)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->department_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                            </td>

                                            <td>
                                                <a href="#" class="show_hidden"><i
                                                        class="fas fa-edit show_hidden_btn">Sửa</i>
                                                    |</a>
                                                <a href="#" class="hidden_form"><i class="fas fa-save"
                                                        style="color: green"></i>
                                                    |</a>
                                                <a href="#" class="hidden_form"><i class="fas fa-times"
                                                        style="color: grey"></i>
                                                    |</a>
                                                <a href="../users/delete/{{ $user->id }}" data-toggle="modal"
                                                    data-target="#confirmModal" class="confirm-action-btn"><i
                                                        class="fas fa-trash" style="color: red">Xóa</i></a>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            {{-- @else
                                @foreach ($users as $user)
                                    <tr>
                                        <form action="{{ url('/users/update') }}" method="POST"
                                            enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <input type="hidden" value="{{ $user->id }}" name="id">
                                            <input type="hidden" value="{{ $user->isActive }}" name="isActive">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                <p class="show_hidden">{{ $user->username }}</p>
                                                <input type="text" value="{{ $user->username }}" name="username"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td>
                                                <p class="show_hidden">{{ $user->fullname }}</p>
                                                <input type="text" value="{{ $user->fullname }}" name="fullname"
                                                    class="hidden_form form-control">
                                            </td>
                                            <td>
                                                <p class="show_hidden">{{ $user->email }}</p>
                                                <input type="email" value="{{ $user->email }}" name="email"
                                                    class="hidden_form form-control">
                                            </td>

                                            <td class="text-center">
                                                <p class="show_hidden">{{ $user->level->level_name }}</p>
                                                <select name="level_id" class="hidden_form form-control">
                                                    <option value="{{ $user->level->id }}">
                                                        {{ $user->level->level_name }}
                                                    </option>
                                                    @foreach ($levels as $level)
                                                        @if ($level->id != $user->level->id)
                                                            <option value="{{ $level->id }}">{{ $level->level_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td class="text-center">
                                                @if (isset($user->department))
                                                    <p class="show_hidden">{{ $user->department->department_name }}
                                                    </p>
                                                    <select name="department_id" class="hidden_form form-control">
                                                        <option value="{{ $user->department->id }}">
                                                            {{ $user->department->department_name }}</option>
                                                        @foreach ($departments as $department)
                                                            @if ($department->id != $user->department->id)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->department_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="#" class="show_hidden"><i
                                                        class="fas fa-edit show_hidden_btn">Sửa</i>
                                                    |</a>
                                                <a href="#" class="hidden_form"><i class="fas fa-save"
                                                        style="color: green"></i>
                                                    |</a>
                                                <a href="#" class="hidden_form"><i class="fas fa-times"
                                                        style="color: grey"></i>
                                                    |</a>
                                                <a href="../users/delete/{{ $user->id }}" data-toggle="modal"
                                                    data-target="#confirmModal" class="confirm-action-btn"><i
                                                        class="fas fa-trash" style="color: red">Xóa</i></a>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            @endif --}}


                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Thêm tài khoản</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/users/add') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row form-group" style="margin: 30px 50px 0px 50px">
                            <div class="col-sm-6">
                                <label for="fname">Tên đăng nhập</label>
                                <input type="text" value="{{ old('username') }}" class="form-control form-control-user"
                                    placeholder="Tên đăng nhập" name="username">
                                @error('username')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fname">Họ Tên</label>
                                <input type="text" value="{{ old('fullname') }}" class="form-control form-control-user"
                                    placeholder="Họ tên" name="fullname">
                                @error('fullname')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group" style="margin: 10px 50px 0px 50px">
                            <div class="col-sm-6">
                                <label for="fname">Email</label>
                                <input type="email" value="{{ old('email') }}" class="form-control form-control-user"
                                    placeholder="Email" name="email">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-sm-6">
                                <label for="fname">Phân quyền</label>
                                <select class="form-control form-control-user" name="level_id">
                                    <option value="">---Chọn phân quyền---</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" @if (old('level_id') == $level->id) selected @endif>
                                            {{ $level->level_name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group" style="margin: 10px 50px 0px 50px">
                           
                            <div class="col-sm-12">
                                <label for="fname" class="text-center">Phòng Ban</label>
                                <select class="form-control form-control-user" id="frm-phongban" name="department_id">
                                    <option class="text-center" value="">---Chọn Phòng Ban---</option>
                                    @foreach ($departments as $department)
                                        <option class="text-center" value="{{ $department->id }}" @if (old('department_id') == $department->id) selected @endif>
                                            {{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>


                        <div class="row form-group" style="margin: 10px 50px 30px 50px">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-user btn-block">Thêm nhân viên</button>
                                <button type="reset" class="btn btn-secondary btn-user btn-block">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Huỷ</button>
                    <a class="btn btn-primary" id="modalLink">Lưu</a>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade bd-example-modal-lg" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="Thêm mới"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal"></h5>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ url('/users/add') }}" enctype="multipart/form-data" id="registerModal">
                        {{ csrf_field() }}
                        <div class="row form-group" style="margin: 30px 50px 0px 50px">
                            <div class="col-sm-6">
                                <label for="fname">Tên đăng nhập</label>
                                <input type="text" value="{{ old('username') }}" class="form-control form-control-user"
                                    placeholder="Tên đăng nhập" name="username">
                                @error('username')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fname">Họ Tên</label>
                                <input type="text" value="{{ old('fullname') }}" class="form-control form-control-user"
                                    placeholder="Họ tên" name="fullname">
                                @error('fullname')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row form-group" style="margin: 10px 50px 0px 50px">
                            <div class="col-sm-6">
                                <label for="fname">Email</label>
                                <input type="email" value="{{ old('email') }}" class="form-control form-control-user"
                                    placeholder="Email" name="email">
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-sm-6">
                                <label for="fname">Phân quyền</label>
                                <select class="form-control form-control-user" name="level_id">
                                    <option value="">---Chọn phân quyền---</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" @if (old('level_id') == $level->id) selected @endif>
                                            {{ $level->level_name }}</option>
                                    @endforeach
                                </select>
                                @error('level_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group" style="margin: 10px 50px 0px 50px">
                           
                            <div class="col-sm-12">
                                <label for="fname" class="text-center">Phòng Ban</label>
                                <select class="form-control form-control-user" id="frm-phongban" name="department_id">
                                    <option class="text-center" value="">---Chọn Phòng Ban---</option>
                                    @foreach ($departments as $department)
                                        <option class="text-center" value="{{ $department->id }}" @if (old('department_id') == $department->id) selected @endif>
                                            {{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>


                        <div class="row form-group" style="margin: 10px 50px 30px 50px">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-user btn-block">Thêm nhân viên</button>
                                <button type="reset" class="btn btn-secondary btn-user btn-block">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
                
            
                    <div class="modal-footer">
                        <a href="#" class="btn btn-secondary btn-icon-split" data-dismiss="modal">
                            <span class="icon text-white-50">
                                <i class="fas fa-ban"></i>
                            </span>
                            <span class="text">Đóng</span>
                        </a>
                        <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="text">Lưu</span>
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <style>
        .hidden_form {
            display: none;
        }

    </style>

    <script>
        $(".show_hidden_btn").click(function() {
            $(this).closest('tr').find('.hidden_form').fadeIn(1);
            $(this).closest('tr').find('.show_hidden').fadeOut(1);
        });

        $(".fa-times").click(function() {
            $(this).closest('tr').find('.hidden_form').fadeOut(1);
            $(this).closest('tr').find('.show_hidden').fadeIn(1);
        });

        $(".fa-save").click(function() {
            $(this).closest('tr').find('form').submit();
        });
    </script>

    {{-- <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script> --}}


@endsection
