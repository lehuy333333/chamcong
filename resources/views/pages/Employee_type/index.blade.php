@extends('templates.master')

@section('title')

    <h3>QUẢN LÝ CHỨC VỤ</h3>

@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex">
                <div class="mr-auto p-2">
                    <a class="btn btn-success nav-link btn-circle" href="#" data-toggle="modal"
                        data-target="#EtypeFormModal" id="insertEtypetButton">
                        <i class="fas fa-plus">Thêm Khối</i>
                    </a>
                    
                </div>
            </div>
        </div>
        <div class="card-body">
            
            @if ($Etype->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped projects" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: rgb(175, 200, 236)">
                        <tr>
                            <th style="width: 3%">
                                No.ID
                            </th>
                            <th style="width: 30%">
                                Khối
                            </th>
                            
                            <th style="width: 25%">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($Etype as $Etypes)
                            <tr>
                                <form action="{{ url('/Etype/update') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $Etypes->id }}" name="id">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <p class="show_hidden">{{ $Etypes->Etype_name }}</p>
                                        <input type="text" value="{{ $Etypes->Etype_name }}" name="Etype_name"
                                            class="hidden_form form-control">
                                    </td>
                                    

                                    <td>
                                        <a href="javascript:void(0);" class="show_hidden"><i
                                                class="fas fa-edit show_hidden_btn"></i>Sửa</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-save"
                                                style="color: green"></i> |</a>
                                        <a href="javascript:void(0);" class="hidden_form"><i class="fas fa-times"
                                                style="color: grey"></i> |</a>
                                        <a href="../Etype/delete/{{ $Etypes->id }}" data-toggle="modal"
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

    <div class="modal fade bd-example-modal-lg" id="EtypeFormModal" tabindex="-1" role="dialog" aria-labelledby="Thêm mới"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white" id="titleModal"></h5>
                </div>
               
                <form action="{{ url('/Etype/create') }}" method="POST" id="EtypeFormModal">
                    @csrf
                    <input type="hidden" name="id" value="" id="Etype_id" name="Etype_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="txtName">Chức Vụ</label>
                            <input type="text" class="form-control border border-danger"  id="txtName" name="Etype_name">
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
