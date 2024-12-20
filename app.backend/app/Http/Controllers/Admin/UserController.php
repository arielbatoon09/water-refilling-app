<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orders;
use App\Models\Refill;

class UserController extends Controller
{
    public function getAllUser(){
        $users = User::all();

        $data = [];

        foreach ($users as $user) {
            $getRefillers = Refill::whereIn('status', ['Delivered', 'Completed', 'Rated'])
                ->orderBy('created_at', 'desc')
                ->where('user_id', $user->id)
                ->get();
    
            $getOrders = Orders::whereIn('status', ['Delivered', 'Completed', 'Rated'])
                ->orderBy('created_at', 'desc')
                ->where('user_id', $user->id)
                ->get();

            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_role' => $user->user_role,
                'flag' => $user->flag,
                'successRefill' => $getRefillers->count(),
                'successOrders' => $getOrders->count(),
            ];
        }

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerGetAllUser',
            'data' => $data,
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

    public function getLatestRefill (){
        $userId = auth()->user()->id;

        $latestRefill = Refill::where('user_id', $userId)->orderBy('created_at', 'desc')->first();

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerRemoveUser',
            'data' => $latestRefill
        ]);

    }

    public function getLatestOrder (){
        $userId = auth()->user()->id;

        $latestOrders = Orders::where('user_id', $userId)->orderBy('created_at', 'desc')->value('refid');
        $getTotalRefId = Orders::where('refid', $latestOrders)->get();

        return response([
            'status' => 200,
            'source' => 'AdminUserControllerRemoveUser',
            'data' => $getTotalRefId
        ]);

    }
}
