@extends('templates.master')

@section('title')
    <h3>QUẢN LÝ NGÀY LỄ</h3>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">


            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if ($workdates->count() <= 300)
                <div class="row mb-4">
                    <div class="col-md-12">
                        <a class="btn btn-success btn-circle" href="#" data-toggle="modal" data-target="#yearModal"
                            id="inserLeveltButton">
                            <i class="fas fa-plus"> Khởi tạo năm làm việc</i>
                        </a>
                    </div>
                </div>
            @else
                <div class="row mb-2">
                    <!-- /.col -->
                    <div class="col-md-4">
                        {{-- @foreach ($workdates as $workdate)
                            @if ($workdate->isHoliday)
                                {{ $workdate->workdate }}: {{ $workdate->holiday }}
                            @endif
                        @endforeach --}}
                        {{-- @if ($workdates->isHoliday) --}}
                        <div class="table-responsive">
                            <table class="table table-striped projects" id="dataTable" width="100%" cellspacing="0">
                                <thead style="background-color: rgb(76, 135, 223)">
                                    <tr>
                                        <th style="width: 3%">
                                            Ngày
                                        </th>
                                        <th style="width: 35%">
                                            Tên
                                        </th>

                                        <th style="width: 20%">
                                            Hệ Số
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($workdates as $workdate)
                                        @if ($workdate->isHoliday)
                                            <tr>
                                                <td>
                                                    <p class="show_hidden">
                                                        {{ \Carbon\Carbon::parse($workdate->workdate)->format('d/m') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="show_hidden">{{ $workdate->holiday }}</p>
                                                </td>
                                                <td>
                                                    <p class="show_hidden">{{ $workdate->work_coefficient }}</p>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- {{ $level->links('pagination::bootstrap-4') }} --}}
                        {{-- @else
                            <div class="alert alert-danger" role="alert">
                                Không có dữ liệu
                            </div>
                        @endif --}}
                    </div>
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-body p-0">
                                <!-- THE CALENDAR -->
                                <div id="calendar"></div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            @endif
        </div><!-- /.container-fluid -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');

                var events_array = [
                    <?php
                    foreach ($workdates as $workdate) {
                        if ($workdate->isHoliday) {
                            echo '{id:"' . $workdate->id . '",';
                            echo 'title:"' . $workdate->holiday . '",';
                            echo 'start:"' . $workdate->workdate . '",},';
                        }
                    }
                    ?>
                ];

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: 'vi',
                    initialView: 'dayGridMonth',
                    timeZone: 'Asia/Ho_Chi_Minh',
                    selectable: true,
                    dateClick: function(info) {
                        date = new Date(info.date);
                        var day = ("0" + date.getDate()).slice(-2);
                        var month = ("0" + (date.getMonth() + 1)).slice(-2);

                        var today = date.getFullYear() + "-" + (month) + "-" + (day);
                        $("#holidayDate").val(today);
                        $("#holidayModal").modal("show");

                    },
                    events: events_array,

                });

                calendar.render();

                changeBgColorWeekend();
                $(".fc-button").click(function() {
                    changeBgColorWeekend();
                    //alert('ád');
                });

                function changeBgColorWeekend() {
                    <?php
                    foreach ($workdates as $workdate) {
                        if ($workdate->isWeekend) {
                            echo '$("*[data-date=' . $workdate->workdate . ']").css("background-color", "#F5F5F5");';
                        }
                    }
                    ?>
                }
            });
        </script>

    </section>
    <!-- /.content -->

    <div class="modal fade bd-example-modal-lg" id="yearModal" tabindex="-1" role="dialog" aria-labelledby="Năm làm việc"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal"></h5>
                </div>

                <form action="{{ url('/workdate/create') }}" method="POST" id="yearModal">
                    @csrf
                    <input type="hidden" name="id" value="" id="level_id" name="level_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <?php $years = range(2010, strftime('%Y', time())); ?>
                            <label for="txtName">Chọn năm</label>
                            <select name="year">
                                <?php foreach(array_reverse($years) as $year) : ?>
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

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
                            <span class="text">Khởi tạo</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="holidayModal" tabindex="-1" role="dialog" aria-labelledby="Năm làm việc"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal">
                        Ngày lễ/ Nghỉ bù
                    </h5>
                </div>

                <form action="{{ url('/workdate/update') }}" method="POST" id="holidayModal">
                    @csrf
                    <input type="hidden" name="id" value="" id="level_id" name="level_id">
                    <div class="modal-body">

                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="txtDate">Ngày</label>
                            <input type="date" class="form-control border border-danger" id="holidayDate" name="workdate">
                        </div>
                        <div class="form-group">
                            <label for="txtholidayName">Tên ngày lễ</label>
                            <input type="text" class="form-control border border-danger" id="holidayName" name="holiday">
                        </div>
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-light active">
                                    <input type="radio" name="isHoliday" value="1" id="option1" autocomplete="off" checked>
                                    Ngày lễ
                                </label>
                                <label class="btn btn-light">
                                    <input type="radio" name="isHoliday" value="0" id="option2" autocomplete="off"> Cuối
                                    tuần
                                </label>
                            </div>
                        </div>

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


@endsection
