<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'read_at',
        'message',
    ];


    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    //check if message has been read
    public function isRead()
    {
        return $this->read_at != null;
    }
}
