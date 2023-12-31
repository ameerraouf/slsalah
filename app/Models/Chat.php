<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:m:s',
        'is_open' => "boolean"
    ];

    protected $with = ['sender', 'receiver' ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function getFileAttribute($value)
    {
        if($value){
            return url('uploads/'. $value);
        }

        return  null;
    }

    public function getAudioAttribute($value)
    {
        if($value){
            return url('uploads/'. $value);
        }

        return  null;
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'chat_id');
    }
}
