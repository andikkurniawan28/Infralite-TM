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

            @if (Auth::user()->role->name === 'Admin')
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
                        <div class="card-icon position-relative">
                            <i class="bi bi-ticket"></i>
                            {{-- Badge untuk unassigned (Admin only) --}}
                            <span id="badge-unassigned"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Tiket belum di-assign">
                                0
                            </span>

                            {{-- Badge untuk open (Admin only) --}}
                            <span id="badge-open"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark d-none"
                                style="margin-left: 2.0rem;" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Tiket status Open">
                                0
                            </span>

                            {{-- Badge untuk assigned_to_me (Admin + Technician) --}}
                            <span id="badge-assigned"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info text-dark d-none"
                                style="margin-left: 3.2rem;" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Tiket untuk Anda">
                                0
                            </span>

                        </div>
                        <h5 class="card-title">Ticket</h5>
                        <p class="card-text">Manage your tickets.</p>
                        <a href="{{ route('ticket.index') }}" class="btn btn-dark">Manage</a>
                    </div>
                </div>
            </div>

        </div>
    </main>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ Auth::id() }};
            const userRole = "{{ Auth::user()->role->name }}";

            // Ambil elemen badge
            const badgeUnassigned = document.getElementById('badge-unassigned');
            const badgeOpen = document.getElementById('badge-open');
            const badgeAssigned = document.getElementById('badge-assigned');

            fetch(`/notification/${userId}`)
                .then(response => response.json())
                .then(data => {
                    // Tampilkan masing-masing badge jika diperlukan
                    if (userRole === 'Admin') {
                        if (data.unassigned > 0) {
                            badgeUnassigned.textContent = data.unassigned;
                            badgeUnassigned.classList.remove('d-none');
                            new bootstrap.Tooltip(badgeUnassigned);
                        }

                        if (data.open > 0) {
                            badgeOpen.textContent = data.open;
                            badgeOpen.classList.remove('d-none');
                            new bootstrap.Tooltip(badgeOpen);
                        }
                    }

                    if (userRole === 'Admin' || userRole === 'Technician') {
                        if (data.assigned_to_me > 0) {
                            badgeAssigned.textContent = data.assigned_to_me;
                            badgeAssigned.classList.remove('d-none');
                            new bootstrap.Tooltip(badgeAssigned);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching ticket notifications:', error);
                });
        });
    </script>
@endsection
