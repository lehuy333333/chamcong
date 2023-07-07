<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->


    <a href="{{ url('home/index') }}" class="brand-link">

        <img src="{{ url('dist/img/logo.png') }}" alt="AdminLTE Logo" class="img-thumbnail" style="opacity: .8">
        {{-- <span class="brand-text font-weight-light">DỊCH VỤ KỸ THUẬT TÂN CẢNG</span> --}}
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        @auth
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ url('/dist/img\/') . Auth::user()->picture_path }}" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ url('/users/profile?id=' . Auth::user()->id) }}" class="d-block">Hello
                        {{ Auth::user()->name }}</a>
                </div>
            </div>
        @endauth

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- ---Quản Lý Nhân Sự--- --}}
                <li class="nav-item">
                </li>
                {{-- ---Quản Lý Công Việc--- --}}
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Chấm công
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../task/create"
                            
                             class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Giao Việc</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../task/list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Danh sách công việc</p>
                            </a>
                        </li>



                    </ul>
                </li>


            </ul>


        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
