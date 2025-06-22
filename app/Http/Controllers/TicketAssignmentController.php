<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TicketAssignmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index($id)
    {
        $ticket = Ticket::whereId($id)->get()->last();
        $technicians = User::whereIn('role_id', [1,2])->get()->all();
        return view('ticket.assignment', compact('ticket', 'technicians'));
    }

    public function process(Request $request, $id)
    {
        Ticket::whereId($id)->update(['assigned_to' => $request->technician_id]);
        $technician_name = User::whereId($request->technician_id)->get()->last()->name;
        ActivityLog::insert([
            'user_id' => auth()->id(),
            'description' => "Assign ticket #{$id} to {$technician_name}",
        ]);
        return redirect()->route('ticket.index')->with('success', 'Ticket successfully assigned!');
    }
}
