<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    public function members(){
        return $this->belongsToMany(User::class, 'room_user')
                    ->withTimeStamps()
                    ->withPivot('joined_at');
    }

    public function lastMessage(){
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }
}
