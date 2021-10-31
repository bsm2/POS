<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $products_count=Product::count();
        $users_count=User::count();
        $clients_count=Client::count();
        $categories_count=Category::count();
        return view ('dashboard.index',compact('products_count','users_count','clients_count','categories_count'));
    }
}
