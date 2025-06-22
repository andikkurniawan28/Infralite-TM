<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketChat;
use Illuminate\Http\Request;

class TicketChatController extends Controller
{
    public function index($ticket_id)
    {
        $ticket = Ticket::whereId($ticket_id)->get()->last();
        return view('ticket_chat.index', compact('ticket'));
    }

    public function process(Request $request)
    {
        TicketChat::create([
            'ticket_id' => $request->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
        return response()->json(['success' => true]);
    }

    public function data($ticket_id)
    {
        $chats = TicketChat::with(['user:id,name,role_id']) // ambil user.name & user.role_id
            ->where('ticket_id', $ticket_id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($chats);
    }
}
