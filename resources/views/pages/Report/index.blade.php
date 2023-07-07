@extends('templates.master')

@section('title')
    <h3>BÁO CÁO CHẤM CÔNG</h3>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-3 mb-2 d-print-none">
            <form action="{{ url('report/delete') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="month">Chọn ngày</label>
                    <input type="date" name="date" id="date" class="form-control">

                    <label for="department" class="mr-2 ">Chọn phân xưởng</label>
                    <select id="department" name="department" class="mr-2 form-control">
                        @if (Auth::user()->level_id > 2)
                            <option value="{{ Auth::user()->department_id }}">
                                {{ Auth::user()->department->department_name }}</option>
                        @else
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-success">Xoá</button>
            </form>

        </div>
    </div>
@endsection
