<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
        'category_id',
        'user_id',
        'product_name',
        'desc',
        'price',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
