<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'connected_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function connectedUser()
    {
        return $this->belongsTo(User::class, 'connected_user_id');
    }

    // public function commonConnections($userId)
    // {
    //     return $this->whereHas('connectedUser.connections', function($query) use ($userId) {
    //         $query->where('connected_user_id', $userId);
    //     });
    // }
}
