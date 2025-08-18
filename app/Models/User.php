<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'phone', 'email', 'password', 'city', 'role', 'national_id'];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * علاقة المستخدم مع العقارات
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * التحقق مما إذا كان المستخدم يمتلك الدور المطلوب
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * التحقق من أدوار متعددة
     */
    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    /**
     * للبحث عن المستخدم باستخدام الرقم الوطني
     */
    public static function findByNationalId($national_id)
    {
        return static::where('national_id', $national_id)->first();
    }

    /**
     * scope للبحث بالرقم الوطني
     */
    public function scopeByNationalId($query, $national_id)
    {
        return $query->where('national_id', $national_id);
    }

    /**
     * Boot method لإعداد المستخدم
     */
    protected static function boot()
    {
        parent::boot();

        // تعيين كلمة المرور تلقائياً عند الإنشاء
        static::creating(function ($user) {
            if (empty($user->password) && !empty($user->national_id)) {
                $user->password = bcrypt($user->national_id);
            }
        });

        // تحديث كلمة المرور عند تغيير الرقم الوطني
        static::updating(function ($user) {
            if ($user->isDirty('national_id')) {
                $user->password = bcrypt($user->national_id);
            }
        });
    }

    // لا نضيف getAuthIdentifierName() لأنه يسبب مشاكل مع تسجيل دخول الإدمن
    // الموديل سيستخدم الـ default auth identifier وهو 'id'

    // لا نضيف validateForPassportPasswordGrant() إلا إذا كنت تستخدم Laravel Passport
}
