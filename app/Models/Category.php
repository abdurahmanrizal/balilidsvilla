<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function resort_item()
    {
        return $this->hasOne(ResortItem::class);
    }
    public function resort_category()
    {
        return $this->hasMany(Resort::class);
    }
}
