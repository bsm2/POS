<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $gaurded=[];

    protected $fillable = [
        'total_price'
    ];

    public function Client(){

        return $this->belongsTo(Client::class);

    }

    public function products(){
        return $this->belongsToMany(Product::class,'product_order');
    }
}
