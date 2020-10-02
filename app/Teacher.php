<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    public function chatroom(){
        return $this->hasMany(ChatRoom::class );
    }
}
