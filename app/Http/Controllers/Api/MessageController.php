<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'chat_id' => 'required',
        ]);

        if ($validate->fails()) {
            $response = [
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 200);
        } else {
            $user = $request->user();
            ChatUser::withTrashed()->where('chat_users.chat_id', $request->chat_id)->where('chat_users.user_id', '=', $user->id)->restore();
            $to = User::select('users.name')->join('chat_users', 'chat_users.user_id', '=', 'users.id')->where('chat_users.chat_id', $request->chat_id)->where('chat_users.user_id', '!=', $user->id)->where('users.id', '!=', $user->id)->first();
            $messages = Message::where('chat_id', $request->chat_id)->with('user')->orderByDesc('updated_at')->get();
            $response = [
                'status' => 'success',
                'msg' => 'There are '.$messages->count().' messages in total',
                'errors' => null,
                'content' => ['message' => $messages, 'to' => $to]
            ];
            return response()->json($response, 200);
        }
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
        $validate = Validator::make($request->all(), [
            'chat_id' => 'required',
            'content' => 'required',
            'type' => 'required',
            'status' => 'required'
        ]);

        if ($validate->fails()) {
            $response = [
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 200);
        } else {
            $user = $request->user();
            $message = Message::create([
                'user_id' => $user->id,
                'chat_id' => $request->chat_id,
                'content' => $request->content,
                'type' => $request->type,
                'status' => $request->status,
                'reply' => $request->reply
            ]);
            $response = [
                'status' => 'success',
                'msg' => 'Add successfully',
                'errors' => null,
                'content' => $message
            ];
            return response()->json($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
}
