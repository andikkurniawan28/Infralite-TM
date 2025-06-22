<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ticketCategory(){
        return $this->belongsTo(TicketCategory::class);
    }

    public function ticketStatus(){
        return $this->belongsTo(TicketStatus::class);
    }

    public function ticketPriorities(){
        return $this->belongsTo(TicketPriorities::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function chats()
    {
        return $this->hasMany(TicketChat::class);
    }
}
