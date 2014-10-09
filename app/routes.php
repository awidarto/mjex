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
	return View::make('track');
});

Route::get('track/{id?}/{more?}',function($id = null,$more = null){
    if(is_null($id)){
        return View::make('track')->with('ordernumber',$id);
    }else{

        if(is_null($more)){
            $idvar = normalphone(trim($id),'all');

            $order = Order::where('delivery_order_active.phone','like', $idvar['international'])
                        ->orWhere('delivery_order_active.mobile1','like',$idvar['international'])
                        ->orWhere('delivery_order_active.mobile2','like',$idvar['international'])

                        ->orWhere('delivery_order_active.phone','like', $idvar['local'])
                        ->orWhere('delivery_order_active.mobile1','like',$idvar['local'])
                        ->orWhere('delivery_order_active.mobile2','like',$idvar['local'])

                        ->orWhere('delivery_order_active.phone','like', '%'.$idvar['number'].'%')
                        ->orWhere('delivery_order_active.mobile1','like','%'.$idvar['number'].'%')
                        ->orWhere('delivery_order_active.mobile2','like','%'.$idvar['number'].'%')
                        ->leftJoin('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->take(3)
                        ->skip(0)
                        ->get()->toArray();
        }else{

            $idvar = normalphone($id,'all');

            $order = Order::where('delivery_order_active.phone','like', $idvar['international'])
                        ->orWhere('delivery_order_active.mobile1','like',$idvar['international'])
                        ->orWhere('delivery_order_active.mobile2','like',$idvar['international'])

                        ->orWhere('delivery_order_active.phone','like', $idvar['local'])
                        ->orWhere('delivery_order_active.mobile1','like',$idvar['local'])
                        ->orWhere('delivery_order_active.mobile2','like',$idvar['local'])

                        ->orWhere('delivery_order_active.phone','like', '%'.$idvar['number'].'%')
                        ->orWhere('delivery_order_active.mobile1','like','%'.$idvar['number'].'%')
                        ->orWhere('delivery_order_active.mobile2','like','%'.$idvar['number'].'%')
                        ->leftJoin('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->get()->toArray();
        }

        return View::make('tracklist')->with('order',$order)->with('phone',$id)->with('more',$more);
    }
});

Route::post('track',function(){
    $in = Input::get();

    //$idvar = '%'.$in['phone'].'%';

    $idvar = normalphone(trim($in['phone']),'all');

    //print_r($idvar);

    $order = Order::where('delivery_order_active.phone','like', $idvar['international'])
                ->orWhere('delivery_order_active.mobile1','like',$idvar['international'])
                ->orWhere('delivery_order_active.mobile2','like',$idvar['international'])

                ->orWhere('delivery_order_active.phone','like', $idvar['local'])
                ->orWhere('delivery_order_active.mobile1','like',$idvar['local'])
                ->orWhere('delivery_order_active.mobile2','like',$idvar['local'])

                ->orWhere('delivery_order_active.phone','like', '%'.$idvar['number'].'%')
                ->orWhere('delivery_order_active.mobile1','like','%'.$idvar['number'].'%')
                ->orWhere('delivery_order_active.mobile2','like','%'.$idvar['number'].'%')

                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();

    $queries = DB::getQueryLog();

    //print_r($queries);

    return View::make('tracklist')->with('order',$order)->with('phone',$idvar['number'])->with('more',null);
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

Route::get('phonetest',function(){
    $numbers = array('85275520101','0543536536546','6276876875687','+62896756456');
    foreach($numbers as $number){
        print(normalphone($number))."\r\n";
        print(normalphone($number,'local'))."\r\n";
        print_r(normalphone($number,'all'))."\r\n";
    }
});

function short_id($id){
    if(strlen($id) > 10){
        return substr($id, -10);
    }else{
        return $id;
    }
}

function normalphone($phone,$type = 'international', $country = '+62'){
    $countrynum = str_replace('+', '', $country);
    //print($countrynum);
    if($type == 'international'){
        $phone = preg_replace('/^[0]/', $country, $phone);
        return $phone;
    }else if($type == 'local'){
        $phone = preg_replace('/^\+['.$countrynum.']|^['.$countrynum.']/', '0', $phone);
        return $phone;
    }else if($type == 'all'){
        $phones = array();
        $phones['international'] = preg_replace('/^0/', $country, $phone);

        $count = 1;

        $phones['local'] = str_replace(array($country,$countrynum), '0', $phone,$count);
        //$phones['number'] = preg_replace('/^\+'.$countrynum.'|^'.$countrynum.'/', '', $phone);
        $phones['number'] = str_replace(array($country,$countrynum), '', $phone,$count);
        return $phones;
    }

}
