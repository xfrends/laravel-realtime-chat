<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $contacts = Contact::user($user->id)->with('otherUser')->where('accept', true)->get();
        $response = [
            'status' => 'success',
            'msg' => 'There are '.$contacts->count().' contact in total',
            'errors' => null,
            'content' => $contacts
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
            'email' => 'required'
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
            $contact = Contact::updateOrCreate(
                [ 'user_id' => $user->id, 'other_user_id' => $otherUser->id ],
                [ 'accept' => true ]
            );
            $response = [
                'status' => 'success',
                'msg' => 'Add successfully',
                'errors' => null,
                'content' => $contact
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
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        Contact::where('id', $id)->where('user_id', $user->id)->update(['accept' => false]);;
        $response = [
            'status' => 'success',
            'msg' => 'Delete successfully',
            'errors' => null,
            'content' => null
        ];
        return response()->json($response, 200);
    }
}
