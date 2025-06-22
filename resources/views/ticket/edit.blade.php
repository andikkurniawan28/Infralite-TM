@extends('template.master')

@section('content')
    <div class="container py-1">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item">
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('ticket.index') }}">Ticket</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Update Ticket
                </li>
            </ol>
        </nav>

        <h4 class="mb-3">Update Ticket Status #{{ $ticket->id }}</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Created At</label>
                        <input type="text" class="form-control" value="{{ $ticket->created_at }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" value="{{ $ticket->title }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" readonly>{{ $ticket->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="ticket_status_id" class="form-label">Change Status</label>
                        <select name="ticket_status_id" id="ticket_status_id" class="form-control" required>
                            <option value="" hidden disabled>-- Select Status --</option>
                            @foreach ($ticket_status as $status)
                                <option value="{{ $status->id }}" {{ $ticket->ticket_status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(Auth()->user()->role->name == 'Admin')
                    <div class="mb-3">
                        <label for="ticket_priorities_id" class="form-label">Change Priority</label>
                        <select name="ticket_priorities_id" id="ticket_priorities_id" class="form-control" required>
                            <option value="" hidden disabled>-- Select Priority --</option>
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}" {{ $ticket->ticket_priorities_id == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="ticket_category_id" class="form-label">Change Category</label>
                        <select name="ticket_category_id" id="ticket_category_id" class="form-control" required>
                            <option value="" hidden disabled>-- Select Category --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $ticket->ticket_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ticket.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
