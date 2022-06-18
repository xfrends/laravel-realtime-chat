<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [
            'status' => 'success',
            'msg' => 'There are '.User::count().' users in total',
            'errors' => null,
            'content' => User::with('role')->get()
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'status' => 'aktif',
            'avatar' => 'https://avataaars.io',
            'phone' => $request->phone,
            'role_id' => 7
        ]);
        $response = [
            'status' => 'success',
            'msg' => 'Update successfully',
            'errors' => null,
            'content' => $user
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        $response = [
            'status' => 'success',
            'msg' => null,
            'errors' => null,
            'content' => User::email($email)->first(),
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $auth = $request->user();
        if ($auth->id != $id) {
            $response = [
                'status' => 'failed',
                'msg' => 'Update failed',
                'errors' => 'Id is not the same as your auth',
                'content' => null
            ];
            return response()->json($response, 401);
        }
        $user = User::find($auth->id);
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->avatar) {
            $user->avatar = $request->avatar;
        }
        if ($request->status) {
            $user->status = $request->status;
        }
        $user->save();

        $response = [
            'status' => 'success',
            'msg' => 'Update successfully',
            'errors' => null,
            'content' => $user
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function manage(Request $request, $id)
    {
        $auth = $request->user();
        if ($auth->role_id > 3) {
            $response = [
                'status' => 'failed',
                'msg' => 'Update failed',
                'errors' => 'Only manager can update manage user',
                'content' => null
            ];
            return response()->json($response, 401);
        }

        $user = User::find($id);
        if ($request->name) {
            $user->name = $request->name;
        }
        if ($request->email) {
            $user->email = $request->email;
        }
        if ($request->avatar) {
            $user->avatar = $request->avatar;
        }
        if ($request->status) {
            $user->status = $request->status;
        }
        if ($request->role_id) {
            $user->role_id = $request->role_id;
        }
        $user->save();

        $response = [
            'status' => 'success',
            'msg' => 'Update successfully',
            'errors' => null,
            'content' => $user
        ];
        return response()->json($response, 200);
    }
}
