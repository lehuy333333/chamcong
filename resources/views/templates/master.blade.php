<!DOCTYPE html>
<html lang="en">

<head>
    @include('templates.head')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('templates.nav')
        <!-- /.navbar -->


        <!-- Main Sidebar Container -->
        {{-- @if(Auth::user()->level_id == 1)
            @include('templates.sidebarAdmin')
        @else
            @include('templates.sidebarNhanvien')
        @endif --}}
        @include('templates.sidebarAdmin')



        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->


            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card  mt-3">

                                <div class="card-header d-print-none">
                                    <h3 class="card-title">@yield('title')</h3>
                                </div>
                                <!-- Đây là nội dung -->
                                <div class="card-body">
                                    @yield('content')
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-6">

                        </div>
                        <!--/.col (right) -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer  d-print-none">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 5.0.1
            </div>
            {{-- <strong>Copyright &copy; . date("Y") . <a href="{{url('home/index')}}">DVKT Tân Cảng</a>.</strong> All rights
            reserved. --}}
            <strong>
                <?php
                echo '<p>Copyright &copy; ' . date('Y') . ' Công Ty Cổ Phần Dịch Vụ Kỹ Thuật Tân Cảng</p>';
                ?>
            </strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    @include('templates.modal')
    @include('templates.footer')
</body>

</html>
