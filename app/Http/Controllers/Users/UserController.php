<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\department;
use App\Models\levels;

class UserController extends Controller
{
    function create()
    {
        $levels = levels::where('id', '>', 1)->get();
        $departments = department::where('id', '>', 0)->get();
        return view('pages/user/add')->with(compact('levels', 'departments'));
    }

    function getUserList()
    {
        $users      = User::where('id', '>', 1)->paginate(20);
        $levels     = levels::where('id', '>', 1)->get();
        $departments = department::where('id', '>', 0)->get();

        return view('pages/user/index')->with(compact('users', 'levels', 'departments'));
    }

    function getUserById(Request $id)
    {

        $data   = User::find($id)->first();
        $levels = levels::find($id)->first();
        $departments = department::find($id)->first();

        return view('pages/user/profile1')->with(compact('data', 'levels', 'departments'));
    }

    function addUser(Request $request)
    {
        $this->validate($request, [
            'email'         => 'required|email',
            'username'      => 'required',
            'fullname'      => 'required',
            'level_id'      => 'required',
            'department_id' => 'required',
        ]);

        try {
            $user                   =   new User();
            $user->username         =   trim($request->input('username'));
            $user->fullname         =   trim($request->input('fullname'));
            $user->email            =   trim($request->input('email'));
            $user->password         =   Hash::make(trim('Nv123456'));
            // $user->birthday         =   trim($request->input('birthday'));
            $user->level_id         =   trim($request->input('level_id'));
            $user->department_id    =   trim($request->input('department_id'));


            $user->save();
            $message = ' Thêm nhân viên' . ' ' . $user->fullname . ' ' . 'thành công !!!! ';
        } catch (Exception $e) {
            $message = $e;
        }
        return redirect('/users/index')->with(compact('message'));
    }

    function updateUser(Request $request)
    {
        $user_id                =   $request->input('id');
        try {
            $user                   =   User::find($user_id);
            $user->username         =   trim($request->input('username'));
            $user->fullname         =   trim($request->input('fullname'));
            $user->email            =   trim($request->input('email'));
            // $user->password         =   Hash::make(trim('Nv123456'));
            // $user->birthday         =   trim($request->input('birthday'));
            $user->level_id         =   trim($request->input('level_id'));
            $user->department_id    =   trim($request->input('department_id'));

            $user->save();
            $message = 'Cập nhật nhân viên' . ' ' . $user->name . ' ' . 'thành công !!!';
        } catch (Exception $e) {
            $message = 'Email đã tồn tại trong hệ thống';
        }
        return redirect('/users/index')->with(compact('message'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        $message = 'Xóa tài khoản thành công !!!';
        return redirect('/users/index')->with(compact('message'));
    }

    function updateProfile(Request $request)
    {
        $link   = url()->previous();
        $parts = parse_url($link);
        parse_str($parts['query'], $query);
        $user_id = $query['id'];

        // $user_id            =   $request->input('id');
        try {
            $user               =   User::find($user_id);
            $user->fullname     =   trim($request->input('name'));
            $user->email        =   trim($request->input('email'));
            if (isset($user->phone)) {
                $user->phone        =   trim($request->input('phone'));
            }
            $picture            =   $request->file('picture');

            if (!empty($picture)) {
                $imageName = time() . '.' . $picture->getClientOriginalExtension();
                $request->file('picture')->move(public_path('dist/img'), $imageName);

                $user->picture_path = $imageName;
            }
            $user->save();
            $message = 'Cập nhật thông tin' . ' ' . $user->name . ' ' . 'thành công !!!';
        } catch (Exception $e) {
            $message = 'Email đã tồn tại trong hệ thống';
        }
        return back()->with(compact('message'));
    }




     public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:new-password',
        ]);

        $user_id               = Auth::user()->id;
        $user   = User::find($user_id);
        if (!(Hash::check($request->get('current-password'), $user->password))) {
            // The passwords matches 
            $message = 'Mật khẩu hiện tại không đúng. Mời nhập lại!';
            return back()->with(compact('message'));
        }

        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            $message = 'Mật khẩu mới không được giống mật khẩu cũ. Mời chọn lại mật khẩu mới';
            return back()->with(compact('message'));
        }

        //Change Password
        $user->password = Hash::make($request->get('new-password'));
        $user->update();
        $message = 'Thay đổi mật khẩu thành công !';
        return back()->with(compact('message'));
    }
}
