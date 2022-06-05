<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $chats = DB::table('chats')->select('chats.id','chats.updated_at')->join('chat_users', 'chat_users.chat_id', '=', 'chats.id')->where('chats.type', 'private')->where('chat_users.user_id', $user->id)->orderByDesc('chats.updated_at')->get();
        foreach ($chats as $chat) {
            $chatUser = User::select('users.name','users.email','users.avatar')->join('chat_users', 'chat_users.user_id', '=', 'users.id')->where('chat_users.chat_id', $chat->id)->where('chat_users.user_id', '!=', $user->id)->where('users.id', '!=', $user->id)->first();
            $chat->name = $chatUser->name;
            $chat->email = $chatUser->email;
            $chat->avatar = $chatUser->avatar;
        }
        $response = [
            'status' => 'success',
            'msg' => 'There are '.$chats->count().' chats in total',
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
            'email' => 'required',
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
            $otherUser = User::where('email', $request->email)->first();

            $contact = Contact::where('user_id', $otherUser->id)->where('other_user_id', $user->id)->first();
            if (isset($contact) && empty($contact->chat_id) || !isset($contact)) {
                $chat = Chat::create(['type' => 'private']);
                DB::insert('insert into chat_users (chat_id, user_id) values (?, ?)', [$chat->id, $user->id]);
                DB::insert('insert into chat_users (chat_id, user_id) values (?, ?)', [$chat->id, $otherUser->id]);
                $myContact = Contact::updateOrCreate(
                    ['user_id' => $user->id, 'other_user_id' => $otherUser->id],
                    ['chat_id' => $chat->id, 'accept' => true]
                );
                $contact = Contact::updateOrCreate(
                    ['user_id' => $otherUser->id, 'other_user_id' => $user->id],
                    ['chat_id' => $chat->id]
                );
            }
            $chat = Chat::find($contact->chat_id);

            $response = [
                'status' => 'success',
                'msg' => 'Add successfully',
                'errors' => null,
                'content' => $chat->with('messages')->first()
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
        $user = User::select('users.name')->join('chat_users', 'chat_users.user_id', '=', 'users.id')->where('chat_users.chat_id', $chat->id)->where('chat_users.user_id', '!=', $user->id)->where('users.id', '!=', $user->id)->first();
        $chat->to = $user->name;
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
