@extends('templates.master')

@section('title')
    <h3>Giao việc</h3>
@endsection

@section('content')
    <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Cảnh báo!</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Không thể chấm công cho tương lai!</div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

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
            <form action="{{ url('task/import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="taskImport" id="taskImport" accept=".xlsx, .csv, .xls" hidden=true
                    onchange="this.form.submit()">
                <label for="taskImport"><span class="btn btn-secondary"><i class="fas fa-file-import"></i> Thêm từ file
                        excel</span></label>

            </form>
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger col-md-12" role="alert">
                        @if ($errors->any())
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
                <!-- /.col -->
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-1">
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
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
                    defaultView: 'month',
                    locale: 'vi',
                    initialView: 'dayGridMonth',
                    timeZone: 'Asia/Ho_Chi_Minh',
                    selectable: true,
                    dateClick: function(info) {

                        window.location.href = "{{ url('/task') }}/" + info.dateStr;

                    },
                    events: events_array,
                    eventRender: function(event, element) {
                        element.attr('title', event.tip);
                    },

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
        <script>
            $(".fa-save").click(function() {
                $(this).closest('tr').find('form').submit();
            });
        </script>
    </section>
    <!-- /.content -->
@endsection
