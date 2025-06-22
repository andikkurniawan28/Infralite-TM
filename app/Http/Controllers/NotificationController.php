<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        // Tiket yang belum di-assign (assigned_to = null)
        $unassignedCount = Ticket::whereNull('assigned_to')->count();

        // Tiket dengan status Open (anggap id 1 = Open)
        $openCount = Ticket::where('ticket_status_id', 1)->count();

        // Tiket yang di-assign ke user saat ini
        $assignedToMeCount = Ticket::where('assigned_to', $user_id)
            ->whereHas('ticketStatus', function ($q) {
                $q->where('name', '!=', 'Closed');
            })
            ->count();

        return response()->json([
            'unassigned' => $unassignedCount,
            'open' => $openCount,
            'assigned_to_me' => $assignedToMeCount,
        ]);
    }
}
