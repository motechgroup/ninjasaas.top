<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EnvatoItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'item_id', 'url'];

    public function purchases()
    {
        return $this->hasMany(EnvatoPurchase::class);
    }
}
