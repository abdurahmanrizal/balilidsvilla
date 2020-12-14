<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resort extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'price',
        'location'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function resort_item_gallery()
    {
        return $this->hasMany(ResortItemGallery::class,'resort_item_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class);
    }

    public function resort_gallery()
    {
        return $this->hasOne(ResortItemGallery::class,'resort_item_id');
    }
}
