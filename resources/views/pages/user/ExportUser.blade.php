<table>
    <thead>
        <tr>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Đơn Vị</th>
            <th>Tổ</th>
            <th>Chức Vụ</th>
            <th>Số điện thoại</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @isset($user->phongban)
                        {{ $user->phongban->name }}
                    @endisset

                </td>
                <td>
                    @isset($user->to)
                        {{ $user->to->name }}
                    @endisset
                </td>
                <td>
                    @isset($user->level)
                        {{ $user->level->name }}
                    @endisset
                </td>
                <td>{{ $user->phone }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
