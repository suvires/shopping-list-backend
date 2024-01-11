<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'stock',
        'default_stock',
    ];

    protected $attributes = [
        'status' => 0,
        'stock' => 1,
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }
}
