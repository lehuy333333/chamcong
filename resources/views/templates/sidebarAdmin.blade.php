<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


    <a href="{{ url('timesheet/Calendar') }}" class="brand-link">

        <img src="{{ url('dist/img/logo.png') }}" alt="AdminLTE Logo" class="img-thumbnail" style="opacity: .8">
        {{-- <span class="brand-text font-weight-light">DỊCH VỤ KỸ THUẬT TÂN CẢNG</span> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    @if (isset(Auth::user()->picture_path))
                        <img src="{{ url('/dist/img\/') . Auth::user()->picture_path }}" class="img-circle elevation-2"
                            alt="User Image">
                    @else
                        <img class="img-circle elevation-2" src="{{ url('/dist/img/avatar.jpg') }}"
                            alt="User profile picture">
                    @endif

                </div>
                <div class="info">
                    <a href="{{ url('/users/profile?id=' . Auth::user()->id) }}" class="d-block">Hello
                        {{ Auth::user()->fullname }}</a>
                </div>
            </div>
        @endauth

        <!-- SidebarSearch Form -->


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- ---Quản Lý Công Việc--- --}}

                <li class="nav-item">

                    <a href="#" class="nav-link">

                        <i class="fas fa-th-list"></i>
                        <p>
                            Quản Lý Chấm Công
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="background-color: rgb(100, 100, 100);">
                        @if (Auth::user()->level_id == 3)
                        <li class="nav-item">
                            <a href="{{ url('/timesheet/Calendar') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                                <p>Chấm công</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/employee/personal') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                                <p>Hệ số tổ</p>
                            </a>
                        </li>
                         <li class="nav-item">
                          <a href="{{ url('/timesheet/getSurplusMonth') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                             <p>Công dư tháng trước</p>
                          </a>
                        </li> 
                        @endif
                        {{-- <li class="nav-item">
                            <a href="{{ url('/timesheet') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                                <p>Bảng chấm công</p>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a href="{{ url('/report/index') }}" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Báo cáo chấm công</p>
                            </a>
                        </li>
                        
                         <li class="nav-item">
                            <a href="{{ url('/tasks') }}" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Hạng mục công việc</p>
                            </a>
                        </li>

                       

                        @if (Auth::user()->level_id != 3)
                            <li class="nav-item">
                                <a href="{{ url('/symbol/index') }}" class="nav-link">
                                    <i class="fas fa-tasks nav-icon"></i>
                                    {{-- <i class="fas fa-tasks"></i> --}}
                                    <p>Ký hiệu chấm công</p>
                                </a>
                            </li>
                            <li class="nav-item">
                            <a href="{{ url('/timesheet/getSurplusMonth') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                                <p>Công dư tháng trước</p>
                            </a>
                        </li>
                           <li class="nav-item">
                                <a href="{{ url('/workdate/index') }}" class="nav-link">
                                    <i class="far fa-regular fa-calendar nav-icon"></i>
                                    
                                    <p>Ngày lễ</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ url('/workdate/holiday') }}" class="nav-link">
                                    <i class="far fa-regular fa-calendar nav-icon"></i>
                                    
                                    <p>Ngày lễ</p>
                                </a>
                            </li> --}}
                            {{-- <li class="nav-item">
                                <a href="{{ url('/employee/index') }}" class="nav-link">
                                    <i class="fas fa-fw fa-user-circle nav-icon"></i>
                                    <p>Rà soát chấm công</p>
                                </a>
                            </li> --}}
                        @endif
                    </ul>
                </li>

                {{-- ---Quản Lý Nhân Sự--- --}}
                @if (Auth::user()->level_id != 3)
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fas fa-fw fa-user"></i>
                            <p>
                                Quản Lý Nhân Sự
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="background-color: rgb(100, 100, 100);">
                            <li class="nav-item">
                                <a href="{{ url('/department/index') }}" class="nav-link">
                                    <i class="far fa-building nav-icon"></i>
                                    <p>Phòng Ban</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('/employee/index') }}" class="nav-link">
                                    <i class="fas fa-fw fa-user-circle nav-icon"></i>
                                    <p>Nhân Viên</p>
                                </a>
                            </li>   
                     
                            @if (Auth::user()->level_id == 1)
                               
                                <li class="nav-item">
                                    <a href="{{ url('/level/index') }}" class="nav-link">
                                        <i class="fas fa-tag nav-icon"></i>
                                        {{-- <i class="fas fa-tag"></i> --}}
                                        <p>Phân quyền</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/position/index') }}" class="nav-link">
                                        <i class="fas fa-tag nav-icon"></i>
                                        {{-- <i class="fas fa-tag"></i> --}}
                                        <p>Chức Vụ</p>
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="{{ url('/Etype/index') }}" class="nav-link">
                                        <i class="far fa-address-book nav-icon"></i>
                                        <p>Khối nhân viên</p>
                                    </a>
                                </li> --}}
                                <li class="nav-item">
                                    <a href="{{ url('/users/index') }}" class="nav-link">
                                        <i class="far fa-address-book nav-icon"></i>
                                        <p>Tài khoản</p>
                                    </a>
                                </li>
                                 <li class="nav-item">
                            <a href="{{ url('/report/delete') }}" class="nav-link">
                                <i class="fas fa-trash nav-icon"></i>
                                <p>Xoá chấm công</p>
                            </a>
                        </li>
                            @endif


                        </ul>
                    </li>
                @endif


                {{-- ---Quản Lý Tài Liệu--- --}}
                {{-- @if (Auth::user()->level_id == 1)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-folder-open"></i>
                        <p>
                            Quản Lý Tài Liệu
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="background-color: rgb(100, 100, 100);">
                        <li class="nav-item">
                            <a href="{{ url('/task/create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Giao Việc</p>
                            </a>
                        </li>
                    </ul>
                </li> --}}

                {{-- ---Quản Lý Quy Trình--- --}}
                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-folder-open"></i>
                        <p>
                            Quản Lý Quy Trình
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="background-color: rgb(100, 100, 100);">
                        <li class="nav-item">
                            <a href="{{ url('/task/create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Giao Việc</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/task/list') }}" class="nav-link">
                                <i class="fas fa-tasks nav-icon"></i>
                                <p>Danh sách công việc</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/task/list') }}" class="nav-link">
                                <i class="fas fa-chart-pie nav-icon"></i>
                                <p>Hiệu Suất</p>
                            </a>
                        </li>



                    </ul>
                </li>
                @endif --}}


            </ul>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
