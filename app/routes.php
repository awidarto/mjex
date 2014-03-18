<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

Route::get('track/{id?}',function($id = null){
    return View::make('track')->with('ordernumber',$id);
});

Route::post('track',function(){
    $in = Input::get();

    $idvar = '%'.$in['ordernumber'];

    $order = Order::where('delivery_id','like', $idvar)->first();



    return View::make('trackresult')->with('order',$order);
});

Route::get('login',function($id = null){
    return View::make('login');
});