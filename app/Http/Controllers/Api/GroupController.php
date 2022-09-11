<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $chats = Chat::select('chats.id', 'chats.name','chats.updated_at','chat_users.pin')->join('chat_users', 'chat_users.chat_id', '=', 'chats.id')->where('chats.type', 'group')->whereNull('chat_users.deleted_at')->where('chat_users.user_id', $user->id)->orderByDesc('chat_users.pin')->orderByDesc('chats.updated_at')->get();
        foreach ($chats as $chat) {
            $message = Message::where('chat_id', $chat->id)->orderByDesc('updated_at')->first();
            if (isset($message)) {
                $chat->lastMessages = $message->content;
                $chat->updated_at = $message->updated_at;
            }
        }
        $response = [
            'status' => 'success',
            'msg' => 'There are '.$chats->count().' group in total',
            'errors' => null,
            'content' => $chats
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
        $validate = Validator::make($request->all(), [
            'participant' => 'required',
            'name' => 'required'
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
            $chat = Chat::create([
                'type' => 'group',
                'name' => $request->name,
                'desc' => $request->desc
            ]);
            DB::insert('insert into chat_users (chat_id, user_id) values (?, ?)', [$chat->id, $user->id]);
            foreach ($request->participant as $key => $value) {
                DB::insert('insert into chat_users (chat_id, user_id) values (?, ?)', [$chat->id, $value]);
            }
            $response = [
                'status' => 'success',
                'msg' => 'Add successfully',
                'errors' => null,
                'content' => $chat
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
    public function show($id, Request $request)
    {
        $user = $request->user();
        $chat = Chat::where('id',$id)->with('messages')->first();
        $chat->participant = User::select('users.name')->join('chat_users', 'chat_users.user_id', '=', 'users.id')->where('chat_users.chat_id', $chat->id)->where('chat_users.user_id', '!=', $user->id)->where('users.id', '!=', $user->id)->get();
        $response = [
            'status' => 'success',
            'msg' => null,
            'errors' => null,
            'content' => $chat
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
        $user = $request->user();
        $chat = Chat::find($id);
        $participant = ChatUser::where('chat_id', $id)->where('user_id', $user->id)->count();
        if (empty($chat) && $participant == 0) {
            $response = [
                'status' => 'failed',
                'msg' => 'Update failed',
                'errors' => 'Only participant can update group',
                'content' => null
            ];
            return response()->json($response, 401);
        }
        $chat->name = $request->name;
        $chat->desc = $request->desc;
        $chat->save();
        $response = [
            'status' => 'success',
            'msg' => null,
            'errors' => null,
            'content' => $chat
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        ChatUser::where('chat_id', $id)->where('user_id', $user->id)->delete();
        $response = [
            'status' => 'success',
            'msg' => 'Delete successfully',
            'errors' => null,
            'content' => null
        ];
        return response()->json($response, 200);
    }

    public function pin(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'pin' => 'required',
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
            DB::update('update chat_users set pin = ? where chat_id = ? and user_id = ?', [$request->pin, $id, $user->id]);
            $response = [
                'status' => 'success',
                'msg' => 'Pin successfully',
                'errors' => null,
                'content' => null
            ];
            return response()->json($response, 200);
        }
    }
}
