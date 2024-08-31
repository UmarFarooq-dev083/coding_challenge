<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function sentRequests()
    {
        return $this->hasMany(ConnectionRequest::class, 'sender_id');
    }

    public function receivedRequests()
    {
        return $this->hasMany(ConnectionRequest::class, 'receiver_id');
    }

    public function connections()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connected_user_id');
    }

    // Get users who are not connected with the authenticated user
    public function getSuggestions()
    {
        return User::whereNotIn('id', function($query) {
            $query->select('receiver_id')
                  ->from('connection_requests')
                  ->where('sender_id', $this->id);
        })->where('id', '<>', $this->id)->get();
    }

    // Get users who have sent connection requests to the authenticated user
    public function getReceivedRequests()
    {
        return $this->receivedRequests()->where('status', 'pending')->with('sender')->get();
    }


    // Get user list who receive connection request form loged in user
    public function getSentRequests()
    {
        return $this->sentRequests()->where('status', 'pending')->with('receiver')->get();
    }


    // Get connections for login user
    public function getConnections()
    {
        return $this->connections;
    }

    // Get common connections for current user
    public function getCommonConnections()
    {
        $userIds = $this->connections->pluck('id');

        return User::whereIn('id', function($query) use ($userIds) {
            $query->select('connected_user_id')
                  ->from('connections')
                  ->whereIn('user_id', $userIds);
        })->whereNotIn('id', $userIds)->get();
    }

    public function getCommonConnectionAndCount()
    {
        $user = auth()->user();
            $connections = Connection::with('connectedUser')
            ->where('user_id', $user->id)
            ->get();

            $result = $connections->map(function ($connection) use ($user) {
            $commonConnectionsCount = Connection::where('user_id', $connection->connected_user_id)
                ->whereIn('connected_user_id', function ($query) use ($user) {
                    $query->select('connected_user_id')
                        ->from('connections')
                        ->where('user_id', $user->id);
                })
                ->count();
                return [
                    'user_id' => $connection->connected_user_id,
                    'common_connections_count' => $commonConnectionsCount,
                ];
            });
            return $result;
    }
}
