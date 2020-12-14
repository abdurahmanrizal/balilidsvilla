<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResortItemGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'resort_item_id',
      'url',
      'type'
    ];

    public function resort_item()
    {
        return $this->belongsTo(Resort::class,'resort_item_id');
    }

    public function resort_one_item()
    {
        return $this->belongsTo(Resort::class,'resort_item_id');
    }
}
