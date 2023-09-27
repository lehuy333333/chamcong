<!DOCTYPE html>
<html lang="en">

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<head>
    @include('templates.head')
    <title>Đăng Nhập</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href=" {{ url('dist/img/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ url('dist/css/util.css') }}">
    <link rel="stylesheet" href="{{ url('dist/css/main.css') }}">
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" action="{{ url('/checklogin') }}" method="POST">
                    {{ csrf_field() }}

                    <span class="login100-form-title p-b-43">
                        <img src="{{ url('dist/img/logo.png') }}" alt="Logo" width="100%">
                    </span>

                    <div class="input-group mb-1">
                        <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-1">
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <?php $years = range(2010, date('Y') + 1); ?>

                    <div class="input-group mb-1">
                        <label for="year_actived" class="form-control">Chọn năm làm việc</label>
                        <select name="year_actived" class="form-control" id="year_actived">
                            <?php foreach(array_reverse($years) as $year) : ?>
                            @if ($year == date('Y'))
                                <option value="<?php echo $year; ?>" selected><?php echo $year; ?></option>
                            @else
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            @endif
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                </form>

                <div class="login100-more" style="background-image: url('dist/img/backgroundCL.jpg');">
                </div>
            </div>
        </div>
    </div>

    @include('templates.footer')

</body>


</html>
