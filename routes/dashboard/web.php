<?php

//use Client\OrderController;
//use App\Models\Client;
use Illuminate\Support\Facades\Route;




Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){
        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function(){
    
            Route::get('/', 'DashboardController@index')->name('index');
            Route::resource('users', UserController::class); // route name dashboard.users.methodName
            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('clients', ClientController::class);
            Route::resource('clients.orders', Client\OrderController::class);
            Route::resource('orders', OrderController::class)->except(['showProducts']);
            Route::get('/orders/{order}/products', 'OrderController@showProducts')->name('orders.products');
        });

});



?>