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

    $idvar = '%'.$in['phone'].'%';

    $order = Order::where('delivery_order_active.phone','like', $idvar)
                ->orWhere('delivery_order_active.mobile1','like',$idvar)
                ->orWhere('delivery_order_active.mobile2','like',$idvar)
                ->join('members', 'members.id', '=', 'merchant_id')
                ->get()->toArray();

    return View::make('tracklist')->with('order',$order);
});

Route::get('login',function($id = null){
    return View::make('login');
});

Route::post('login',function(){

});

