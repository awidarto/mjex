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
            $idvar = phonenumber( trim($id),'21','62' );
            //print_r($idvar);

            $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s'  ";

            $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%');

            $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();
        }else{

            $idvar = phonenumber( trim($id),'21','62' );
    //print_r($idvar);

            $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s'  ";

            $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%');

            $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->get()->toArray();
        }

        return View::make('tracklist')->with('order',$order)->with('phone',$id)->with('more',$more);
    }
});

Route::post('track',function(){
    $in = Input::get();

    $idvar = normalphone(trim($in['phone']),'all');

    $idvar = phonenumber( trim($in['phone']),'21','62' );
    //print_r($idvar);
    $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' ";

    $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%');

    $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();

    $queries = DB::getQueryLog();

    return View::make('tracklist')->with('order',$order)->with('phone',$idvar)->with('more',null);
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
    $numbers = array('0896756456','+62896756456','62896756456','0215841281','+62215841281','62215841281');

    foreach($numbers as $number){
        print($number .' -> '. phonenumber( $number,'21','62' ))."\r\n";
    }

});

Route::get('tpath/{delivery_id}',function($delivery_id){
    $fullpath = public_path().Config::get('ks.thumb_path').'th_'.$delivery_id.'.jpg';
    print $fullpath;
    if(file_exists($fullpath)){
        print 'exist';
    }else{
        print 'not exist';
    }
});

function short_id($id){
    if(strlen($id) > 10){
        return substr($id, -10);
    }else{
        return $id;
    }
}

function phonenumber($phone, $local = '0', $country = '62')
{
    $phone = str_replace('+', '', $phone);
    $count = 1;

    $phone = preg_replace('/^0/', '', $phone);
    $phone = preg_replace('/^'.$country.'/', '', $phone);
    $phone = preg_replace('/^'.$local.'/', '', $phone);

    return $phone;
}

function normalphone($phone,$type = 'international', $country = '+62')
{
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
