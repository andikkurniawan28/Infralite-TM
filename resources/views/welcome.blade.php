@extends('template.master')

@section('breadcrumb')

@endsection

@section('content')
    <main class="container py-1">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white rounded px-3 py-2 shadow-sm">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{ route('welcome') }}">
                        <i class="bi bi-house-door"></i>
                    </a>
                </li>
                {{-- <li class="breadcrumb-item active" aria-current="page">
                    Database Connection
                </li> --}}
            </ol>
        </nav>

        <div class="row g-4">

            {{--

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-download"></i></div>
                        <h5 class="card-title">Manual Backup</h5>
                        <p class="card-text">Perform backup manually anytime.</p>
                        <a href="{{ route('manual_backup.index') }}" class="btn btn-dark">Backup</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                        <h5 class="card-title">Scheduled Backup</h5>
                        <p class="card-text">Set up automatic scheduled backups.</p>
                        <a href="{{ route('schedule.index') }}" class="btn btn-dark">Schedule</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-folder2-open"></i></div>
                        <h5 class="card-title">Backup File</h5>
                        <p class="card-text">Download system backup files easily.</p>
                        <a href="{{ route('backup_file.index') }}" class="btn btn-dark">Open</a>
                    </div>
                </div>
            </div>

             --}}

            @if(Auth::user()->role->name === 'Admin')
            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-person-gear"></i></div>
                        <h5 class="card-title">User</h5>
                        <p class="card-text">Manage application users.</p>
                        <a href="{{ route('user.index') }}" class="btn btn-dark">Manage</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="card-title">Activity Log</h5>
                        <p class="card-text">View history.</p>
                        <a href="{{ route('activity_log.index') }}" class="btn btn-dark">View</a>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-sm-6 col-md-4">
                <div class="card card-menu h-100">
                    <div class="card-body text-center">
                        <div class="card-icon"><i class="bi bi-ticket"></i></div>
                        <h5 class="card-title">Ticket</h5>
                        <p class="card-text">Manage your tickets.</p>
                        <a href="{{ route('ticket.index') }}" class="btn btn-dark">Manage</a>
                    </div>
                </div>
            </div>


        </div>
    </main>
@endsection
