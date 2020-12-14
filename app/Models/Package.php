<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    public function package_galleries()
    {
        return $this->hasMany(PackageGallery::class);
    }

    public function package_gallery()
    {
        return $this->hasOne(PackageGallery::class);
    }

    public function resort_items()
    {
        return $this->belongsToMany(Resort::class);
    }
}
