<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'phone', 'email','password', 'city', 'role'];

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
//     public function isAdmin(): bool
// {
//     return $this->role === UserRole::ADMIN;
// }


    // التحقق مما إذا كان المستخدم يمتلك الدور المطلوب
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // التحقق من أدوار متعددة
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }


}
