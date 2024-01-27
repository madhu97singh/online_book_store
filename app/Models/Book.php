<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author', 'genre', 'price', 'quantity_available'];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function getTotalPurchasesAttribute()
    {
        return $this->purchases()->sum('quantity');
    }

    public function getTotalOrdersAttribute()
    {
        return $this->purchases()->sum('quantity');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
