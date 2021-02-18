<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function rooms(Request $request)
    {
        return ChatRoom::all();
    }
    public function messages(Request $request, $roomID)
    {
        return ChatMessage::where('chat_room_id',$roomID)->with('user')->orderBy('created_at','DESC')->get();
    }
    public function newMessage(Request $request, $roomID)
    {
        
        $newMessage=new ChatMessage();
        $newMessage->user_id=Auth::id();
        $newMessage->chat_room_id=$roomID;
        $newMessage->message=$request->message;
        $newMessage->save();
        broadcast(new NewChatMessage($newMessage))->toOthers();
        return $newMessage;
    }
}