<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    const ADMIN = 1;
    const CUSTOMER = 2;
    const VENDOR = 3;
    const DELIVERY_BOY = 4;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    static public function getUsers()
    {
        $users = User::orderBy('name', 'ASC')
            ->where('status', '=', '1')
            ->where('is_deleted', '!=', '1')
            ->get();

        return $users;
    }

    static public function getUserByRole($role)
    {
        $users = User::orderBy('name', 'ASC')
            ->where('status', '=', '1')
            ->where('role_id', '=', $role)
            ->where('is_deleted', '!=', '1')
            ->get();

        return $users;
    }

    static public function findById($id)
    {
        return self::where('id', $id)->first();
    }
}
