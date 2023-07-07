<script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ url('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ url('plugins/moment/moment.min.js') }}"></script>
<script src="{{ url('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ url('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ url('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ url('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ url('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ url('plugins/dropzone/min/dropzone.min.js') }}"></script>

<script src="{{ url('plugins/customexcel.js') }}"></script>




<!-- AdminLTE for demo purposes -->
<script src="{{ url('dist/js/demo.js') }}"></script>

<script>
    // $(window).on('load', function() {
    //     $('[data-toggle="tooltip"]').tooltip();
    //     $('#messageModal').modal('show');
    // });

    $("#checkAll").click(function() {
        $(".check").prop('checked', $(this).prop('checked'));
    });

    $(".check").click(function() {
        var count_check = $('input[class="check"]:checked').length;
        if (count_check == 1) {
            $('#updateButton').removeClass('disabled');
        } else {
            $('#updateButton').addClass('disabled');
        }
    });

    $("#insertButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm phòng Ban');
    });

    $("#updateButton").click(function() {
        $("#titleModal").text('Sửa Phòng Ban');
        $.ajax({
            url: "{{ url('/to/phongban_json') }}", //link để lấy dữ liệu .... ra (cho function return json)
            type: 'get',
            dataType: 'json',
            data: {
                phongban_id: $('input[class="check"]:checked').val(),
            }
        }).done(function(response) {
            $("#phongban_id").val(response.id);
            $("#txtName").val(response.name);
            $("#txtDescription").val(response.description);
        });
    });

    $("#deleteButton").click(function() {
        $(".check").prop('checked', $(this).prop('checked'));
    });

    $("#inserLeveltButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Chức Vụ');
    });

    $("#inserTotButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Tổ');
    });

    $("#inserNhomthietbitButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Nhóm Thiết Bị');
    });

    $("#insertThietbiButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Thiết Bị');
    });

    $("#insertBophanButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Bộ Phận');
    });

    $("#inserTotButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Hạng Mục');
    });

    $("#inserSymboltButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Ký Hiệu');
    });

    $("#inserTaskButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Công Việc');
    });

    $("#insertEtypetButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Khối');
    });

    $("#inserHolidaytButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Ngày Lễ');
    });

    $("#inserEmployeetButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Nhân viên');
    });


    $("#insertUserButton").click(function() {
        $("#courseForm").trigger("reset");
        $("#titleModal").text('Thêm Tài khoản');
    });


    $(function () {
      $('.select2').select2()

    });
    
</script>

