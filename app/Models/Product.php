<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name', 'description', 'unit_price', 'discount_percentage'];


    public function merchant()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
