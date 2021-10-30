<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone'
    ];
    protected $casts = [
        'phone' => 'array',
    ];

    protected $gaurded=[];
    
    //one to many relation with orders
    public function orders(){
        return $this->hasMany(Order::class);
    }
}
