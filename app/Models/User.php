<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    protected $fillable = ['name', 'username', 'role_id', 'password', 'created_by', 'deleted_by'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 150, 150)
            ->nonQueued();
    }

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Check if the user's role has a specific permission
    public function hasPermissionTo($permissionName)
    {
        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    public function createdProducts()
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    public function deletedProducts()
    {
        return $this->hasMany(Product::class, 'deleted_by');
    }

    public function createdSuppliers()
    {
        return $this->hasMany(Supplier::class, 'created_by');
    }

    public function deletedSuppliers()
    {
        return $this->hasMany(Supplier::class, 'deleted_by');
    }

    public function createdCategories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function deletedCategories()
    {
        return $this->hasMany(Category::class, 'deleted_by');
    }
}
