<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketPriorities;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $statuses = TicketStatus::all();
        $priorities = TicketPriorities::all();
        $categories = TicketCategory::all();
        if ($request->ajax()) {
            $tickets = Ticket::with(['ticketStatus', 'ticketCategory', 'ticketPriorities', 'creator', 'assignee']); // eager load relasi

            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('ticketStatus_name', function ($row) {
                    return $row->ticketStatus->name ?? '-';
                })
                ->addColumn('ticketCategory_name', function ($row) {
                    return $row->ticketCategory->name ?? '-';
                })
                ->addColumn('ticketPriorities_name', function ($row) {
                    return $row->ticketPriorities->name ?? '-';
                })
                ->addColumn('creator_name', function ($row) {
                    return $row->creator->name ?? '-';
                })
                ->addColumn('assignee_name', function ($row) {
                    return $row->assignee->name ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['creator_name']) // kalau kamu ingin render HTML nanti
                ->make(true);
        }

        return view('ticket.index', compact('statuses', 'priorities', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
            'ticket_priorities_id' => 'required|exists:ticket_priorities,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
        ]);

        $ticket = new Ticket();
        $ticket->title = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->ticket_status_id = $validated['ticket_status_id'];
        $ticket->ticket_priorities_id = $validated['ticket_priorities_id'];
        $ticket->ticket_category_id = $validated['ticket_category_id'];
        $ticket->created_by = auth()->id(); // otomatis dari user login
        $ticket->save();
        ActivityLog::insert([
            'user_id' => auth()->id(),
            'description' => "Creating ticket {$request->title}",
        ]);

        return redirect()->route('ticket.index')->with('success', 'Ticket successfully created!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
