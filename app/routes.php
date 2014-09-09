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

Route::get('track/{id?}/{more?}',function($id = null,$more = null){
    if(is_null($id)){
        return View::make('track')->with('ordernumber',$id);
    }else{

        $idvar = '%'.$id.'%';

        if(is_null($more)){
            $order = Order::where('delivery_order_active.phone','like', $idvar)
                        ->orWhere('delivery_order_active.mobile1','like',$idvar)
                        ->orWhere('delivery_order_active.mobile2','like',$idvar)
                        ->join('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->take(3)
                        ->skip(0)
                        ->get()->toArray();
        }else{
            $order = Order::where('delivery_order_active.phone','like', $idvar)
                        ->orWhere('delivery_order_active.mobile1','like',$idvar)
                        ->orWhere('delivery_order_active.mobile2','like',$idvar)
                        ->join('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->get()->toArray();
        }

        return View::make('tracklist')->with('order',$order)->with('phone',$id)->with('more',$more);
    }
});

Route::post('track',function(){
    $in = Input::get();

    $idvar = '%'.$in['phone'].'%';

    $order = Order::where('delivery_order_active.phone','like', $idvar)
                ->orWhere('delivery_order_active.mobile1','like',$idvar)
                ->orWhere('delivery_order_active.mobile2','like',$idvar)
                ->join('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();

    return View::make('tracklist')->with('order',$order)->with('phone',$in['phone'])->with('more',null);
});

Route::get('item/{did}/{phone}/{more?}',function($did,$phone,$more = null){
    $order = Order::where('delivery_id',$did)->first()->toArray();
    return View::make('trackresult')->with('order',$order)->with('phone',$phone)->with('more',$more);
});

Route::get('login',function($id = null){
    return View::make('login');
});

Route::post('login',function(){

});

function short_id($id){
    if(strlen($id) > 10){
        return substr($id, -10);
    }else{
        return $id;
    }
}

