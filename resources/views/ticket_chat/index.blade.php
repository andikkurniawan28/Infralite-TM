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
                    Chat
                </li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span>Chat Ticket #{{ $ticket->id }} - {{ $ticket->title }}</span>
                <a href="{{ route('ticket.index') }}" class="btn btn-light btn-sm">← Back</a>
            </div>

            <div class="px-3 py-2 border-bottom bg-light">
                <strong>Description:</strong>
                <p class="mb-0 text-muted">{{ $ticket->description }}</p>
            </div>

            <div class="card-body" id="chat-box" style="max-height: 500px; overflow-y: auto;">
                <div class="text-muted text-center">Loading chat...</div>
            </div>

            <div class="card-footer">
                <form id="chat-form" action="{{ route('ticket_chat.process', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="message" class="form-control" placeholder="Type a message..." autofocus required>
                        <button class="btn btn-primary" type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const ticketId = {{ $ticket->id }};

    function fetchChat() {
        $.get(`/ticket_chat/data/${ticketId}`, function (data) {
            let chatHtml = '';
            if (data.length === 0) {
                chatHtml = '<div class="text-muted text-center">No chat available.</div>';
            } else {
                data.forEach(chat => {
                    const name = chat.user?.name || 'Unknown';
                    const role = getRoleLabel(chat.user?.role_id);
                    const time = new Date(chat.created_at).toLocaleTimeString();

                    chatHtml += `
                        <div class="mb-3 border-bottom pb-2">
                            <div><strong>${name}</strong> <small class="text-muted">(${role}) • ${time}</small></div>
                            <div>${chat.message}</div>
                        </div>
                    `;
                });
            }

            $('#chat-box').html(chatHtml);
        });
    }

    function getRoleLabel(roleId) {
        switch (roleId) {
            case 1: return 'Admin';
            case 2: return 'Technician';
            case 3: return 'User';
            default: return 'Unknown';
        }
    }

    $('#chat-form').on('submit', function (e) {
        e.preventDefault();
        const message = $(this).find('input[name="message"]').val();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                message: message
            },
            success: function () {
                $('#chat-form')[0].reset(); // Kosongkan input
                fetchChat(); // Refresh chat box
            },
            error: function () {
                alert('Failed to send message.');
            }
        });
    });


    $(document).ready(function () {
        fetchChat(); // initial load
        setInterval(fetchChat, 10000); // every 10 seconds
    });
</script>
@endsection

