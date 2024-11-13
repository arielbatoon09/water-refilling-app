<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getAllUser(){
        $users = User::all();

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerGetAllUser',
            'data' => $users
        ]);
    }

    public function getUserById(Request $request){
        $user = User::where('id', $request->id)->first();

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerRemoveUser',
            'data' => $user
        ]);
    }

    public function removeUser(Request $request){
        $user = User::where('id', $request->id)->first();

        $user->update([
            'flag' => 0
        ]);

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerRemoveUser',
            'message' => 'Success Remove from view'
        ]);
    }

    public function updateUser(Request $request){   
        $user = User::where('id', $request->id)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'user_role' => $request->user_role,
            'flag' => $request->flag,
        ]);

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerRemoveUser',
            'message' => 'Success update user'
        ]);
    }
}
