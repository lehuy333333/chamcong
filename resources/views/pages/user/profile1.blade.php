@extends('templates.master')

@section('title')
    THÔNG TIN CÁ NHÂN
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid" style="margin-top: 30px">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">

                        <div class="card-body box-profile">

                            <div class="text-center">
                                @if (isset(Auth::user()->picture_path))
                                    <img class="profile-user-img img-fluid img-circle" style="width: 100px; height:100px"
                                        src="{{ url('/dist/img\/') . Auth::user()->picture_path }}"
                                        alt="User profile picture">
                                @else
                                    <img class="profile-user-img img-fluid img-circle" style="width: 100px; height:100px"
                                        src="{{ url('/dist/img/avatar.jpg') }}" alt="User profile picture">
                                @endif

                            </div>
                            @if (isset($data->fullname))
                                <h3 class="profile-username text-center">{{ $data->fullname }}</h3>
                            @endif

                            @if (isset($data->fullname))
                                <p class="text-muted text-center">Vai trò: {{ $data->level->level_name }}</p>
                            @endif
                        </div>

                    </div>

                    <div class="card card-primary">


                        <div class="card-body">
                            <strong><i class="far fa-building mr-1"></i>Đơn Vị</strong>
                            {{-- @if (isset($data->to->name))
                        <p class="text-muted">
                            Tổ {{ $data->to->name }} - {{ $data->phongban->name }}
                        </p>
                        @endif --}}
                            @if (isset($data->department->department_name))
                                <p class="text-muted">
                                    {{ $data->department->department_name }}
                                </p>
                            @endif
                            <hr>

                            {{-- <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>

                        <p class="text-muted">Malibu, California</p>

                        <hr> --}}

                            <strong><i class="far fa-envelope mr-1"></i>Email</strong>

                            <p class="text-muted">{{ $data->email }} </p>
                            {{-- <p class="text-muted">Điện thoại: {{ $data->phone }} </p> --}}

                            <hr>

                            {{-- <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                            fermentum enim neque.</p> --}}
                        </div>

                    </div>

                </div>

                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Sửa
                                        Thông Tin Cá Nhân </a></li>
                                <li class="nav-item"><a class="nav-link" href="#changpass" data-toggle="tab">Đổi
                                        Mật Khẩu</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/users/updateProfile') }}" method="POST" id="formedit"
                                                enctype="multipart/form-data">

                                                {{ csrf_field() }}

                                                <input type="hidden" name="user_id" value="{{ $data->id }}">
                                                <div class="row form-group">
                                                    <div class="col-xl-3">Họ Tên</div>
                                                    <div class="col-xl-9">
                                                        <input type="text" class="form-control form-control-user"
                                                            name="name" value="{{ $data->fullname }}" id="name" readonly>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-xl-3">Email</div>
                                                    <div class="col-xl-9">
                                                        <input type="email" class="form-control form-control-user"
                                                            name="email" value="{{ $data->email }}" id="email" readonly>
                                                    </div>
                                                </div>
                                                {{-- <div class="row form-group">
                                                <div class="col-xl-3">Số Điện Thoại</div>
                                                <div class="col-xl-9">
                                                    <input type="phone" class="form-control form-control-user"
                                                        name="phone" value="{{ $data->phone }}" id="phone" readonly>
                                                </div>
                                            </div>

                                            <div class="row form-group">
                                                <div class="col-xl-3">Ảnh Đại Diện</div>
                                                <div class="col-xl-9">
                                                    <img src="{{ url('/dist/img\/') . $data->picture_path }}"
                                                        class="show_hidden" width="100px">
                                                    <input type="file" value="{{ old('picture') }}" name="picture"
                                                        id="picture" class="form-control form-control-user" disabled>
                                                </div>
                                            </div> --}}

                                                <div class="row form-group">
                                                    <div class="col-xl-12">
                                                        <button type="submit" id="submitbtn" form="formedit"
                                                            style="visibility:hidden"
                                                            class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                                                            <i class="fas fa-check"></i>
                                                            <span>Save</span>
                                                        </button>

                                                        <a href="#" id="cancelbtn" onclick="cancelcliked()"
                                                            style="visibility:hidden"
                                                            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                                                            <i class="fas fa-window-close "></i>
                                                            <span>Cancel</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-xl-12">
                                                        <a href="#" id="editbtn" onclick="clickme()"
                                                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                                            <i class="fas fa-edit"></i>
                                                            <span>Sửa Thông Tin Cá Nhân</span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="changpass">
                                    <div class="card">
                                        <div class="card-body">
                                            <form action="{{ url('/users/changPass') }}" method="POST" id="changepass">
                                                {{ csrf_field() }}
                                                <div class="row form-group">
                                                    <div class="col-xl-3">Mật Khẩu Hiện Tại</div>
                                                    <div class="col-xl-9">
                                                        <input type="password" value="{{ old('current-password') }}"
                                                            class="form-control form-control-user"
                                                            placeholder="Mật khẩu hiện tại" name="current-password">
                                                        @error('current-password')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-xl-3">Mật Khẩu Mới</div>
                                                    <div class="col-xl-9">
                                                        <input type="password" value="{{ old('new-password') }}"
                                                            class="form-control form-control-user"
                                                            placeholder="Mật khẩu mới" name="new-password">
                                                        @error('new-password')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row form-group">
                                                    <div class="col-xl-3">Xác Nhận Mật Khẩu Mới</div>
                                                    <div class="col-xl-9">
                                                        <input type="password" value="{{ old('password_confirmation') }}"
                                                            class="form-control form-control-user"
                                                            placeholder="Xác nhận mật khẩu" name="password_confirmation">
                                                        @error('password_confirmation')
                                                            <div class="alert alert-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-sm-12">
                                                        <button type="submit" form="changepass" class="btn btn-primary">Đổi
                                                            mật khẩu</button>
                                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-content -->
                                    </div><!-- /.card-body -->
                                </div>



                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->

    </section>
    <!-- /.content -->
    <script>
        function clickme() {
            $('#name').prop("readonly", false);
            $('#email').prop("readonly", false);
            $('#phone').prop("readonly", false);
            $('#picture').prop("disabled", false);
            $('#password').prop("readonly", false);


            $('#editbtn').css("visibility", 'hidden');
            $('#submitbtn').css("visibility", 'visible');
            $('#cancelbtn').css("visibility", 'visible');
        }

        function cancelcliked() {
            location.reload();
        }
    </script>
@endsection
