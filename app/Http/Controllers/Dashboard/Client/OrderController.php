<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Client $client)
    {
        $categories=Category::all();
        $orders= $client->orders()->latest()->paginate(3);
        return view('dashboard.clients.orders.create',compact('categories','client','orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Client $client)
    {

        $request->validate([
            'products'=>'required|array'
        ]);
        //add order to the client
        $order=$client->orders()->create([]);
        //dd($order);
        $order->products()->attach($request->products);
        $total_price = 0;
        foreach ($request->products as $id=>$quantity_arr) {

            $product = Product::FindOrFail($id);
            $total_price +=$product->sale_price * $quantity_arr['quantity'];
            
            $product->update([
                'stock'=> $product->stock - $quantity_arr['quantity']
            ]);
        }

        $order->update([
            'total_price'=>$total_price
        ]);

        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client,Order $order)
    {
        $categories=Category::all();
        return view('dashboard.clients.orders.edit',compact('order','categories','client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Client $client, Order $order)
    {
        //dd($request->all());

        $request->validate([
            'products'=>'required|array'
        ]);

        foreach ($order->products as $product) {
            //dd($product->stock + $product->pivot->quantity);
            $product->update([
                'stock'=>$product->stock + $product->pivot->quantity

            ]);
        }
        $order->delete();
        //add order to the client

        $order=$client->orders()->create([]);
        //dd($order);
        $order->products()->attach($request->products);
        $total_price = 0;
        foreach ($request->products as $id=>$quantity_arr) {

            $product = Product::FindOrFail($id);
            $total_price +=$product->sale_price * $quantity_arr['quantity'];
            
            $product->update([
                'stock'=> $product->stock - $quantity_arr['quantity']
            ]);
        }

        $order->update([
            'total_price'=>$total_price
        ]);

        session()->flash('success',__('site.added_successfully'));
        return redirect()->route('dashboard.orders.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order,Client $client)
    {
        //
    }
}
