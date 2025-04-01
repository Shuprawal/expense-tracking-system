<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'role',
        'email',
        'password',
        'role_id',
    ];

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
    public function categories()
    {
        return $this->belongsToMany(Category::class)->withPivot('percentage','date')->distinct();
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function budgets()
    {
        return $this->hasOne(Budget::class);
    }

    public function totalBudget()
    {
        return $this->budgets()->sum('amount');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return$this->roles()->with('permissions');
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }
    public function isAdmin()
    {
        return $this->roles->contains('name', 'admin');
    }

    public function scopeIsNotAdmin($query)
    {
     $query->whereHas('roles', function ($query) {
         $query->where('name','!=', 'admin');
     });
    }

    public function getRole(): ?Role
    {


        return $this->roles()->first();
    }

}
