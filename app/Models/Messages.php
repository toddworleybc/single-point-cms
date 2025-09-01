<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Messages extends Model
{
    //



    protected $fillable = [
        'title',
        'message',
        'is_read',
        'message_to',
        'message_from',
        'replied_to',
        'user_id',
    ];

   

    public function user()
    {
        return $this->belongsTo(User::class, $foreighKey = 'user_id', $ownerKey = 'id');
    }


}
