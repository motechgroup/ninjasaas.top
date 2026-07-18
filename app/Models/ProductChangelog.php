<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductChangelog extends Model
{
    use HasFactory;

    protected $fillable = ['product_version_id', 'type', 'description'];

    public function version()
    {
        return $this->belongsTo(ProductVersion::class, 'product_version_id');
    }
}
