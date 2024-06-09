<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string',
            'body' => 'required|string',
            'sender' => 'required|string|email',
            'recipients' => 'required|string', // Validate further for comma-separated emails if needed
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = Email::create($request->all());

        return response()->json($email, 201);
    }

    public function create_group(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = Group::create($request->all());

        return response()->json($email, 201);
    }

    public function attachToGroup(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|exists:groups,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $email = Email::findOrFail($id);
        $group = Group::findOrFail($request->group_id);

        $email->groups()->attach($group);

        return response()->json(['message' => 'Email attached to group successfully'], 200);
    }

    public function sendToQueue($id)
    {
        $email = Email::findOrFail($id);
        dispatch(new SendEmailJob($email));

        return response()->json(['message' => 'Email sent to queue'], 200);
    }
}
