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

Route::group(array('prefix'=>'c'),function(){

    Route::get('/', function()
    {
        return View::make('c.track');
    });

    Route::get('track/{id?}/{more?}',function($id = null,$more = null){
        if(is_null($id)){
            return View::make('c.track')->with('ordernumber',$id);
        }else{

            $idvar = trim($id);

            if(date('G',time()) <= 3){
                $asdate = date( 'Y-m-d',time() - ( 3 * 60 * 60 )  );
            }else{
                $asdate = date('Y-m-d',time());
            }

            //print_r($idvar);
            //$sql = "`delivery_order_active`.`phone` LIKE  '%s%' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' ";
            $sql = "`delivery_order_active`.`assignment_date` = '%s' AND (`devices`.`identifier` LIKE  '%s' OR  `couriers`.`fullname` LIKE  '%s' ) ";

            $sql = sprintf($sql, $asdate, '%'.$idvar.'%','%'.$idvar.'%');

            $order = Order::whereRaw($sql)
                        ->leftJoin('devices', 'devices.id', '=', 'device_id')
                        ->leftJoin('couriers', 'couriers.id', '=', 'courier_id')
                        ->leftJoin('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->get()->toArray();

            $total = 0;
            $total_delivered = 0;
            $total_pending = 0;
            $total_other = 0;

            $total_delivered_pics = 0;
            $total_pending_pics = 0;

            $total_delivered_sign = 0;
            $total_pending_sign = 0;

            $total_delivered_notes = 0;
            $total_pending_notes = 0;

            $total_delivered_coord = 0;
            $total_pending_coord = 0;

            $total_pics = 0;
            $total_sign = 0;
            $total_notes = 0;
            $total_coord = 0;

            $total_no_pics = 0;
            $total_no_sign = 0;
            $total_no_notes = 0;
            $total_no_coord = 0;

            $total_other_pics = 0;
            $total_other_sign = 0;
            $total_other_notes = 0;
            $total_other_coord = 0;

            for($i = 0;$i < count($order);$i++){
                $total++;
                $order[$i]['sign'] = '';
                $order[$i]['pics'] = '';
                $order[$i]['has_sign'] = false;
                $order[$i]['has_pic'] = false;
                $order[$i]['has_coord'] = false;
                $order[$i]['has_note'] = false;
                $order[$i]['near_origin'] = false;

                if( Helpers::nearOrigin( $order[$i]['latitude'], $order[$i]['longitude'])){
                    $order[$i]['near_origin'] = true;
                }

                $thumbs = Helpers::get_multifullpic($order[$i]['delivery_id']);
                $order[$i]['thumb'] = $thumbs[0];

                if($order[$i]['status'] == 'delivered'){
                    $total_delivered++;
                }elseif($order[$i]['status'] == 'pending'){
                    $total_pending++;
                }else{
                    $total_other++;
                }

                if($p = Helpers::picexists($order[$i]['delivery_id'])){
                    $order[$i]['pics'] = $p;
                    $total_pics++;
                    if($order[$i]['status'] == 'delivered'){
                        $total_delivered_pics++;
                    }elseif($order[$i]['status'] == 'pending'){
                        $total_pending_pics++;
                    }else{
                        $total_other_pics++;
                    }
                    $order[$i]['has_pic'] = true;
                }else{
                    $order[$i]['pics'] = 'Tidak ada';
                    $total_no_pics++;
                }

                if(Helpers::signexists($order[$i]['delivery_id'])){
                    $order[$i]['sign'] = 'Ada';
                    $total_sign++;
                    if($order[$i]['status'] == 'delivered'){
                        $total_delivered_sign++;
                    }elseif($order[$i]['status'] == 'pending'){
                        $total_pending_sign++;
                    }else{
                        $total_other_sign++;
                    }
                    $order[$i]['has_sign'] = true;
                }else{
                    $order[$i]['sign'] = 'Tidak Ada';
                    $total_no_sign++;
                }

                if($order[$i]['delivery_note'] != ''){
                    $total_notes++;
                    if($order[$i]['status'] == 'delivered'){
                        $total_delivered_notes++;
                    }elseif($order[$i]['status'] == 'pending'){
                        $total_pending_notes++;
                    }else{
                        $total_other_notes++;
                    }
                    $order[$i]['has_note'] = true;
                }else{
                    $total_no_notes++;
                }

                if( ($order[$i]['latitude'] != '' && $order[$i]['longitude'] != '') || ($order[$i]['latitude'] != 0 && $order[$i]['longitude'] != 0)  ){
                    $total_coord++;
                    if($order[$i]['status'] == 'delivered'){
                        $total_delivered_coord++;
                    }elseif($order[$i]['status'] == 'pending'){
                        $total_pending_coord++;
                    }else{
                        $total_other_coord++;
                    }
                    $order[$i]['has_coord'] = true;
                }else{
                    $total_no_coord++;
                }

            }

            $queries = DB::getQueryLog();

            $log = array_merge(array( 'c'=>'tracklist','device'=>$idvar ));
            Helpers::log($log);

            return View::make('c.tracklist')
                ->with('reportdate',$asdate)
                ->with('order',$order)
                ->with('total', $total )
                ->with('total_delivered',$total_delivered )
                ->with('total_pending',$total_pending )
                ->with('total_other',$total_other )

                ->with('total_delivered_pics',$total_delivered_pics )
                ->with('total_pending_pics',$total_pending_pics )

                ->with('total_delivered_sign',$total_delivered_sign )
                ->with('total_pending_sign',$total_pending_sign )

                ->with('total_delivered_notes',$total_delivered_notes )
                ->with('total_pending_notes',$total_pending_notes )

                ->with('total_delivered_coord',$total_delivered_coord )
                ->with('total_pending_coord',$total_pending_coord )

                ->with('total_other_pics',$total_other_pics )
                ->with('total_other_sign',$total_other_sign )
                ->with('total_other_notes',$total_other_notes )
                ->with('total_other_coord',$total_other_coord )

                ->with('total_pics',$total_pics )
                ->with('total_sign',$total_sign )
                ->with('total_notes',$total_notes )
                ->with('total_coord',$total_coord )

                ->with('total_no_pics',$total_no_pics )
                ->with('total_no_sign',$total_no_sign )
                ->with('total_no_notes',$total_no_notes )
                ->with('total_no_coord',$total_no_coord )

                ->with('device',$idvar)
                ->with('more',null);
        }
    });

    Route::post('track',function(){
        $in = Input::get();

        $idvar = trim($in['device']);

        if(date('G',time()) <= 3){
            $asdate = date( 'Y-m-d',time() - ( 3 * 60 * 60 )  );
        }else{
            $asdate = date('Y-m-d',time());
        }

        //test only
            $asdate = '2014-03-05';

        //print_r($idvar);
        //$sql = "`delivery_order_active`.`phone` LIKE  '%s%' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' ";
        $sql = "`delivery_order_active`.`assignment_date` = '%s' AND (`devices`.`identifier` LIKE  '%s' OR  `couriers`.`fullname` LIKE  '%s' ) ";

        $sql = sprintf($sql, $asdate, '%'.$idvar.'%','%'.$idvar.'%');

        $order = Order::whereRaw($sql)
                    ->leftJoin('devices', 'devices.id', '=', 'device_id')
                    ->leftJoin('couriers', 'couriers.id', '=', 'courier_id')
                    ->leftJoin('members', 'members.id', '=', 'merchant_id')
                    ->orderBy('assignment_date','desc')
                    ->get()->toArray();

        $total = 0;
        $total_delivered = 0;
        $total_pending = 0;
        $total_other = 0;

        $total_delivered_pics = 0;
        $total_pending_pics = 0;

        $total_delivered_sign = 0;
        $total_pending_sign = 0;

        $total_delivered_notes = 0;
        $total_pending_notes = 0;

        $total_delivered_coord = 0;
        $total_pending_coord = 0;

        $total_pics = 0;
        $total_sign = 0;
        $total_notes = 0;
        $total_coord = 0;

        $total_no_pics = 0;
        $total_no_sign = 0;
        $total_no_notes = 0;
        $total_no_coord = 0;

        $total_other_pics = 0;
        $total_other_sign = 0;
        $total_other_notes = 0;
        $total_other_coord = 0;

        for($i = 0;$i < count($order);$i++){
            $total++;
            $order[$i]['sign'] = '';
            $order[$i]['pics'] = '';
            $order[$i]['has_sign'] = false;
            $order[$i]['has_pic'] = false;
            $order[$i]['has_coord'] = false;
            $order[$i]['has_note'] = false;
            $order[$i]['near_origin'] = false;

            if( Helpers::nearOrigin( $order[$i]['latitude'], $order[$i]['longitude'])){
                $order[$i]['near_origin'] = true;
            }

            $thumbs = Helpers::get_multifullpic($order[$i]['delivery_id']);
            $order[$i]['thumb'] = $thumbs[0];

            if($order[$i]['status'] == 'delivered'){
                $total_delivered++;
            }elseif($order[$i]['status'] == 'pending'){
                $total_pending++;
            }else{
                $total_other++;
            }

            if($p = Helpers::picexists($order[$i]['delivery_id'])){
                $order[$i]['pics'] = $p;
                $total_pics++;
                if($order[$i]['status'] == 'delivered'){
                    $total_delivered_pics++;
                }elseif($order[$i]['status'] == 'pending'){
                    $total_pending_pics++;
                }else{
                    $total_other_pics++;
                }
                $order[$i]['has_pic'] = true;
            }else{
                $order[$i]['pics'] = 'Tidak ada';
                $total_no_pics++;
            }

            if(Helpers::signexists($order[$i]['delivery_id'])){
                $order[$i]['sign'] = 'Ada';
                $total_sign++;
                if($order[$i]['status'] == 'delivered'){
                    $total_delivered_sign++;
                }elseif($order[$i]['status'] == 'pending'){
                    $total_pending_sign++;
                }else{
                    $total_other_sign++;
                }
                $order[$i]['has_sign'] = true;
            }else{
                $order[$i]['sign'] = 'Tidak Ada';
                $total_no_sign++;
            }

            if($order[$i]['delivery_note'] != ''){
                $total_notes++;
                if($order[$i]['status'] == 'delivered'){
                    $total_delivered_notes++;
                }elseif($order[$i]['status'] == 'pending'){
                    $total_pending_notes++;
                }else{
                    $total_other_notes++;
                }
                $order[$i]['has_note'] = true;
            }else{
                $total_no_notes++;
            }

            if( ($order[$i]['latitude'] != '' && $order[$i]['longitude'] != '') || ($order[$i]['latitude'] != 0 && $order[$i]['longitude'] != 0)  ){
                $total_coord++;
                if($order[$i]['status'] == 'delivered'){
                    $total_delivered_coord++;
                }elseif($order[$i]['status'] == 'pending'){
                    $total_pending_coord++;
                }else{
                    $total_other_coord++;
                }
                $order[$i]['has_coord'] = true;
            }else{
                $total_no_coord++;
            }

        }

        $queries = DB::getQueryLog();

        $log = array_merge($in, array( 'c'=>'trackdetail' ));
        Helpers::log($log);

        return View::make('c.tracklist')
            ->with('reportdate',$asdate)
            ->with('order',$order)
            ->with('total', $total )
            ->with('total_delivered',$total_delivered )
            ->with('total_pending',$total_pending )
            ->with('total_other',$total_other )

            ->with('total_delivered_pics',$total_delivered_pics )
            ->with('total_pending_pics',$total_pending_pics )

            ->with('total_delivered_sign',$total_delivered_sign )
            ->with('total_pending_sign',$total_pending_sign )

            ->with('total_delivered_notes',$total_delivered_notes )
            ->with('total_pending_notes',$total_pending_notes )

            ->with('total_delivered_coord',$total_delivered_coord )
            ->with('total_pending_coord',$total_pending_coord )

            ->with('total_other_pics',$total_other_pics )
            ->with('total_other_sign',$total_other_sign )
            ->with('total_other_notes',$total_other_notes )
            ->with('total_other_coord',$total_other_coord )

            ->with('total_pics',$total_pics )
            ->with('total_sign',$total_sign )
            ->with('total_notes',$total_notes )
            ->with('total_coord',$total_coord )

            ->with('total_no_pics',$total_no_pics )
            ->with('total_no_sign',$total_no_sign )
            ->with('total_no_notes',$total_no_notes )
            ->with('total_no_coord',$total_no_coord )

            ->with('device',$idvar)
            ->with('more',null);
    });

    Route::get('item/{did}/{phone}/{more?}',function($did,$phone,$more = null){
        $order = Order::where('delivery_id',$did)->first()->toArray();

        $log = array(
            'c'=>'trackdetail',
            'delivery_id'=>$did
             );
        Helpers::log($log);

        return View::make('c.trackresult')->with('order',$order)->with('phone',$phone)->with('more',$more);
    });

});

Route::get('track/{id?}/{more?}',function($id = null,$more = null){
    if(is_null($id)){
        return View::make('track')->with('ordernumber',$id);
    }else{

        if(is_null($more)){
            $idvar = phonenumber( trim($id),'21','62' );
            //print_r($idvar);

            $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' OR  `delivery_order_active`.`delivery_id` = '%s'  ";

            $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%',trim($id));

            $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();

            $ordercount = Order::whereRaw($sql)->count();
            $more = ($ordercount <= 3)?null:'more';

        }else{

            $idvar = phonenumber( trim($id),'21','62' );
    //print_r($idvar);

            $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' OR  `delivery_order_active`.`delivery_id` = '%s'  ";

            $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%',trim($id));

            $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->get()->toArray();

            $ordercount = Order::whereRaw($sql)->count();
            $more = null;

        }

        return View::make('tracklist')->with('order',$order)->with('phone',$id)->with('more',$more);
    }
});

Route::post('track',function(){
    $in = Input::get();

    $idvar = normalphone(trim($in['phone']),'all');

    $idvar = phonenumber( trim($in['phone']),'21','62' );
    //print_r($idvar);
    $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' OR  `delivery_order_active`.`delivery_id` = '%s'   ";

    $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%', trim($in['phone']));

    $order = Order::whereRaw($sql)
                ->leftJoin('members', 'members.id', '=', 'merchant_id')
                ->orderBy('assignment_date','desc')
                ->take(3)
                ->skip(0)
                ->get()->toArray();

    $queries = DB::getQueryLog();

    $ordercount = Order::whereRaw($sql)->count();
    $more = ($ordercount <= 3)?null:'more';

    $log = array_merge($in, array( 'c'=>'trackdetail' ));
    Helpers::log($log);


    return View::make('tracklist')
        ->with('order',$order)
        ->with('phone',$idvar)
        ->with('more',$more);
});

Route::get('item/{did}/{phone}/{more?}',function($did,$phone,$more = null){
    $order = Order::where('delivery_id',$did)->first()->toArray();

    $log = array(
        'c'=>'trackdetail',
        'delivery_id'=>$did
         );
    Helpers::log($log);

    if(in_array($order['status'], Config::get('ks.inprocess') )){
        $order['status'] = 'Dalam proses pengiriman';
    }

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

