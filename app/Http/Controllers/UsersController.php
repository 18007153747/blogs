<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    //加载中间件过滤
    public function __construct()
    {
        $this->middleware('auth',['except' => ['show']]);
    }

    //页面展示
    public function show(User $user){
        return view('users.show',compact('user'));
    }

    //编辑页面
    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    //更新个人资料操作
    public function update(UserRequest $request,ImageUploadHandler $uploader,User $user){
        $this->authorize('update',$user);
        $data = $request->all();
        if ($request->avatar){
            $result = $uploader->save($request->avatar,'avatars',$user->id);
            if ($result){
                $data['avatar'] = $result['path'];

            }
        }
        $user->update($data);
        return redirect()->route('users.show',$user->id)->with('success','更新成功');
    }
}
