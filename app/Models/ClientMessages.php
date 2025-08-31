<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientMessages extends Model
{
    //
    
    protected $fillable = [
        'message',
        'user_id',
        'is_read'
    ];



    function clientMessage() {
        return $this->belongsTo(User::class);
    }
    


}
