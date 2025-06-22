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
                    Assign Ticket
                </li>
            </ol>
        </nav>

        <h4 class="mb-3">Assign Technician to Ticket #{{ $ticket->id }}</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('ticket.assignment.process', $ticket->id) }}" method="POST">
                    @csrf

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
                        <label for="technician_id" class="form-label">Select Technician</label>
                        <select name="technician_id" id="technician_id" class="form-control" required>
                            <option value="" hidden selected disabled>-- Select Technician --</option>
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}">
                                    {{ $technician->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ticket.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
