@extends('templates.master')

@section('title')

    <h3>QUẢN LÝ NGÀY LỄ</h3>

@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <a class="btn btn-success nav-link btn-circle" href="#" data-toggle="modal"
                        data-target="#HolidayFormModal" id="inserHolidaytButton">
                        <i class="fas fa-plus">Thêm ngày lễ - Làm bù</i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            @if ($workdate->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped projects" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: rgb(175, 200, 236)">
                        <tr>
                            <th class="text-center">
                                Ngày
                            </th>
                            <th class="text-center">
                                Hệ số
                            </th>
                            <th class="text-center">
                                Mô tả ngày
                            </th>
                            <th class="text-center">
                                Action
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($workdate as $workdates)
                            <tr>
                                <form action="{{ url('/workdate/update') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $workdates->id }}" name="id">
                                    {{-- <td>{{ $loop->index + 1 }}</td> --}}
                                    <td class="text-center">
                                        
                                        <p class="show_hidden">{{ \Carbon\Carbon::parse($workdates->workdate)->format('d/m/Y') }}</p>
                                        <input type="date" value="{{ $workdates->workdate }}" name="workdate"
                                            class="hidden_form form-control">
                                    </td>

                                    <td class="text-center">
                                        <p class="show_hidden">{{ $workdates->work_coefficient }}</p>
                                        <input type="text" value="{{ $workdates->work_coefficient }}" name="work_coefficient"
                                            class="hidden_form form-control">
                                    </td>

                                    <td class="text-center">
                                        <p class="show_hidden">{{ $workdates->holiday }}</p>
                                        <input type="text" value="{{ $workdates->holiday }}" name="holiday"
                                            class="hidden_form form-control">
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="javascript:void(0);" class="show_hidden"><i
                                                class="fas fa-edit show_hidden_btn"></i>Sửa</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-save"
                                                style="color: green"></i> |</a>
                                        <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-times"
                                                style="color: grey"></i> |</a>
                                        <a href="../workdate/delete/{{ $workdates->id }}" data-toggle="modal"
                                            data-target="#confirmModal" class="confirm-action-btn"><i
                                                class="fas fa-trash" style="color: red">Xóa</i></a>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                {{-- {{ $level->links('pagination::bootstrap-4') }} --}}
            @else
                <div class="alert alert-danger" role="alert">
                    Không có dữ liệu
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="HolidayFormModal" tabindex="-1" role="dialog" aria-labelledby="Thêm mới"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal"></h5>
                </div>
               
                <form action="{{ url('/workdate/update') }}" method="POST" id="HolidayFormModal">
                    @csrf
                    <input type="hidden" name="id" value="" id="level_id" name="level_id">
                    <div class="modal-body">
                        <div class="row form-group" style="margin: 30px 50px 0px 50px">
                            <div class="col-sm-12">
                                <label for="fname">Ngày</label>
                                <input type="date" value="{{ old('workdate') }}" class="form-control form-control-user"
                                    placeholder="Ngày" name="workdate">
                                @error('workdate')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group" style="margin: 30px 50px 0px 50px">
                            <div class="col-sm-6">
                                <label for="fname">Hệ số</label>
                                <select class="form-control form-control-user" name="work_coefficient">
                                    <option class="text-center" value="">---Chọn hệ số---</option>
                                    <option class="text-center" value="2">2</option>
                                    <option class="text-center" value="3">3</option>
                                </select>
                                @error('work_coefficient')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="fname">Mô tả ngày</label>
                                <input type="text" value="{{ old('holiday') }}" class="form-control form-control-user"
                                    placeholder="Mô tả ngày" name="holiday">
                                @error('holiday')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
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

    <script>
        $("#deleteButton").click(function() {
            $(".check").prop('checked', $(this).prop('checked'));
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
