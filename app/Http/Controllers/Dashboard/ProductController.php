<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products=Product::when($request->search,function($q) use($request){
            return $q->whereTranslationLike('name','%'.$request->search.'%');
        })
        ->when($request->category_id,function($query) use($request){
            return $query->where('category_id',$request->category_id);
        })
        ->latest()->paginate(4);

        $categories = Category::all();

        return view('dashboard.products.index',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories= Category::all();
        return view('dashboard.products.create')->with('categories',$categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules =[
            'category_id'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'stock'=>'required',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules +=[$locale.'.name'=>'required|unique:product_translations,name'];
            $rules +=[$locale.'.description'=>'required'];
        }

        $request->validate($rules);

        $product_data=$request->all();

        if ($request->image) {
            
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/productsImages/'.$request->image->hashName()));
            $product_data['image']=$request->image->hashName();
            
        };
        
        Product::create($product_data);
        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Product $product)
    {
       

        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories=Category::all();
        return view('dashboard.products.edit',compact('product','categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules =[
            'category_id'=>'required',
            'purchase_price'=>'required',
            'sale_price'=>'required',
            'stock'=>'required',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules +=[$locale.'.name'=>['required',Rule::unique('product_translations','name')->ignore($product->id)]];
            $rules +=[$locale.'.description'=>'required'];
        }

        $product_data=$request->except('image');

        if ($request->image) {

            if ($request->image !='default.png') {
                Storage::disk('public_uploads')->delete('/productsImages/'.$product->image);
            }
            
            Image::make($request->image)->resize(null, 200, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/productsImages/'.$request->image->hashName()));
            $product_data['image']=$request->image->hashName();
            
        };

        $product->update($product_data);
        session()->flash('success',__('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image !='default.png') {
            Storage::disk('public_uploads')->delete('/productsImages/'.$product->image);
        }
        $product->delete();
        session()->flash('success',__('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
