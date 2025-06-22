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
        $priorities = TicketPriorities::all();
        $categories = TicketCategory::all();
        if ($request->ajax()) {
            $tickets = Ticket::with(['ticketStatus', 'ticketCategory', 'ticketPriorities', 'creator', 'assignee']); // eager load relasi

            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('ticketStatus_name', function ($row) {
                    $statusName = $row->ticketStatus->name ?? 'No Status';

                    if (strtolower($statusName) === 'closed') {
                        return '<span class="badge bg-secondary">' . e($statusName) . '</span>';
                    }

                    return '<a href="' . route('ticket.edit', $row->id) . '" class="btn btn-sm btn-primary">' . e($statusName) . '</a>';
                })
                ->addColumn('ticketCategory_name', function ($row) {
                    return $row->ticketCategory->name ?? '-';
                })
                ->addColumn('ticketPriorities_name', function ($row) {
                    $name = $row->ticketPriorities->name ?? '-';

                    switch (strtolower($name)) {
                        case 'urgent':
                            $color = 'danger'; // merah
                            break;
                        case 'high':
                            $color = 'warning'; // oranye
                            break;
                        case 'medium':
                            $color = 'primary'; // biru
                            break;
                        case 'low':
                            $color = 'secondary'; // abu
                            break;
                        default:
                            return '-';
                    }
                    return '<span class="badge bg-' . $color . '">' . e($name) . '</span>';
                })
                ->addColumn('creator_name', function ($row) {
                    return $row->creator->name ?? '-';
                })
                ->addColumn('assignee_name', function ($row) {
                    if ($row->assignee) {
                        return e($row->assignee->name);
                    } else {
                        return '<a href="' . route('ticket.assignment.index', $row->id) . '" class="btn btn-sm btn-primary">Assign</a>';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('ticket_chat', function ($row) {
                    return '<a href="' . route('ticket_chat.index', $row->id) . '" class="btn btn-sm btn-primary">Chat</a>';
                })
                ->rawColumns(['creator_name', 'assignee_name', 'ticketStatus_name', 'ticket_chat', 'ticketPriorities_name']) // kalau kamu ingin render HTML nanti
                ->make(true);
        }

        return view('ticket.index', compact('priorities', 'categories'));
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
            'ticket_priorities_id' => 'required|exists:ticket_priorities,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id',
        ]);

        $ticket = new Ticket();
        $ticket->title = $validated['title'];
        $ticket->description = $validated['description'];
        $ticket->ticket_priorities_id = $validated['ticket_priorities_id'];
        $ticket->ticket_category_id = $validated['ticket_category_id'];
        $ticket->created_by = auth()->id(); // otomatis dari user login
        $ticket->save();
        ActivityLog::insert([
            'user_id' => auth()->id(),
            'description' => "Creating ticket #{$ticket->id}",
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
        if (auth()->user()->role_id == 1) {
            $ticket_status = TicketStatus::all();
        } else {
            $ticket_status = TicketStatus::where('id', '<', 4)->get();
        }
        $priorities = TicketPriorities::all();
        $categories = TicketCategory::all();
        return view('ticket.edit', compact('ticket_status', 'ticket', 'priorities', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Validasi dasar: status wajib
        $rules = [
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
        ];

        // Jika user adalah Admin, wajibkan juga priority dan category
        if (auth()->user()->role->name === 'Admin') {
            $rules['ticket_priorities_id'] = 'required|exists:ticket_priorities,id';
            $rules['ticket_category_id'] = 'required|exists:ticket_categories,id';
        }

        $validated = $request->validate($rules);

        // Data yang akan diupdate
        $updateData = [
            'ticket_status_id' => $validated['ticket_status_id'],
        ];

        // Jika admin, tambahkan data lain
        if (auth()->user()->role->name === 'Admin') {
            $updateData['ticket_priorities_id'] = $validated['ticket_priorities_id'];
            $updateData['ticket_category_id'] = $validated['ticket_category_id'];
        }

        $ticket->update($updateData);

        ActivityLog::insert([
            'user_id' => auth()->id(),
            'description' => "Updated ticket #{$ticket->id}",
        ]);

        return redirect()->route('ticket.index')->with('success', 'Ticket successfully updated!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function __construct()
    {
        $this->middleware('tech')->only(['edit', 'update']);
    }
}
