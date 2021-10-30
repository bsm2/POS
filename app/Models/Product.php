<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class Product extends Model
{
    use HasFactory;
    use Translatable;

    protected $gaurded=[];
    protected $fillable = ['image','purchase_price','sale_price','stock','category_id'];
    public $translatedAttributes = ['name','description'];

    protected $appends =['image_path','profit_percent'];

    public function category(){

        return $this->belongsTo(Category::class);

    }

    public function getImagePathAttribute(){

        return asset('uploads/productsImages/'.$this->image);

    }

    public function getProfitPercentAttribute(){
        
        $profit= $this->sale_price-$this->purchase_price;
        $profit_percent = $profit*100/$this->purchase_price;
        return number_format( $profit_percent,2);
    }

    public function orders(){
        return $this->belongsToMany(Order::class,'product_order');
    }
}
