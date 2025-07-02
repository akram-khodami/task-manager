<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();

    }

    public function assignRole($role)
    {
        if (is_string($role)) {

            $role = Role::where('name', $role)->firstOrFail()->id;

        } elseif ($role instanceof Role) {

            $role = $role->id;

        }

        return $this->roles()->syncWithoutDetaching([$role]);
    }

    public function removeRole($role)
    {
        if (is_string($role)) {

            $role = Role::where('name', $role)->firstOrFail()->id;

        } elseif ($role instanceof Role) {

            $role = $role->id;

        }

        return $this->roles()->detach($role);
    }

    public function getRoleNames()
    {
        return $this->roles()->pluck('name')->toArray();
    }

    public function getRoleIds()
    {
        return $this->roles()->pluck('id')->toArray();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->roles()->where('name', 'admin')->exists();//note2:admin should be enum
    }


}
