<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PackageGallery extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'package_id',
        'url',
        'type'
    ];

    public function packages()
    {
        return $this->belongsTo(Package::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
