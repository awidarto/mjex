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

                $signs = Helpers::get_multisign($order[$i]['delivery_id']);
                $order[$i]['signpic'] = $signs[0];

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
            //$asdate = '2014-03-05';

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

            $signs = Helpers::get_multisign($order[$i]['delivery_id']);
            $order[$i]['signpic'] = $signs[0];

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


Route::get('/', function()
{
    homecrumb();

    $shops = Shopcategory::orderBy('name','asc')->get();

    $shoplist = Shop::where('status','active')
                    ->orderBy('shopcategoryLink','asc')
                    ->orderBy('name','asc')
                    ->get();


    $log = array_merge(array( 'c'=>'hometrac' ));
    Helpers::log($log);

    return View::make('track')
        ->with('shops',$shoplist);

	//return View::make('track');
});

Route::group(array('prefix'=>'api'),function(){

    Route::post('track',function(){

        $sout = array();

        if(Input::has('q')){

            $in = Input::get();

            $idvar = normalphone(trim($in['q']),'all');

            $idvar = phonenumber( trim($in['q']),'21','62' );
            //print_r($idvar);
            $sql = "`delivery_order_active`.`phone` LIKE  '%s' OR  `delivery_order_active`.`mobile1` LIKE  '%s' OR  `delivery_order_active`.`mobile2` LIKE  '%s' OR  `delivery_order_active`.`merchant_trans_id` LIKE  '%s' OR  `delivery_order_active`.`delivery_id` = '%s'   ";

            $sql = sprintf($sql, '%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%','%'.$idvar.'%', trim($in['q']));

            $order = Order::whereRaw($sql)
                        ->leftJoin('members', 'members.id', '=', 'merchant_id')
                        ->orderBy('assignment_date','desc')
                        ->get();

            $queries = DB::getQueryLog();

            $ordercount = Order::whereRaw($sql)->count();

            $log = array_merge($in, array( 'c'=>'trackdetail', 'p'=>$in['q'] ));
            Helpers::log($log);


            if(count($order) > 0){
                for($i = 0;$i < count($order); $i++){

                    $sout[$i]['assignmentDate'] = $order[$i]->assignment_date;
                    $sout[$i]['merchantname'] = $order[$i]->merchantname;
                    $sout[$i]['merchantTrans_id'] = $order[$i]->merchant_trans_id;
                    $sout[$i]['deliveryType'] = $order[$i]->delivery_type;
                    $sout[$i]['deliveryId'] = $order[$i]->delivery_id;

                }
                return Response::json( $sout );
            }else{

                return Response::json( $sout );
            }


        }else{
            return Response::json( $sout );

        }
    });

    Route::get('shops',function(){
            $shops = Shop::where('status','active')
                        ->orderBy('shopcategory','asc')
                        ->orderBy('merchantname','asc')->get();

            $sout = array();
            for($i = 0;$i < count($shops);$i++){
                $sout[$i]['extId'] = $shops[$i]->_id;
                $sout[$i]['district']= $shops[$i]->district;
                $sout[$i]['email']= $shops[$i]->email;
                $sout[$i]['extImage'] = $shops[$i]->extImage;
                $sout[$i]['fullname ']= $shops[$i]->fullname;
                $sout[$i]['groupId'] = $shops[$i]->group_id;
                $sout[$i]['identifier ']= $shops[$i]->identifier;
                $sout[$i]['legacyId'] = $shops[$i]->legacyId;
                $sout[$i]['mcCity'] = $shops[$i]->mc_city;
                $sout[$i]['mcCountry'] = $shops[$i]->mc_country;
                $sout[$i]['mcDistrict'] = $shops[$i]->mc_district;
                $sout[$i]['mcEmail'] = $shops[$i]->mc_email;
                $sout[$i]['mcFirst'] = $shops[$i]->mc_first;
                $sout[$i]['mcLast'] = $shops[$i]->mc_last;
                $sout[$i]['mcMobile'] = $shops[$i]->mc_mobile;
                $sout[$i]['mcPhone'] = $shops[$i]->mc_phone;
                $sout[$i]['mcPickup'] = $shops[$i]->mc_pickup;
                $sout[$i]['mcProvince'] = $shops[$i]->mc_province;
                $sout[$i]['mcStreet'] = $shops[$i]->mc_street;
                $sout[$i]['mcToscan'] = $shops[$i]->mc_toscan;
                $sout[$i]['mcUnlimited'] = $shops[$i]->mc_unlimited;
                $sout[$i]['mcUrl'] = $shops[$i]->mc_url;
                $sout[$i]['mcZip'] = $shops[$i]->mc_zip;
                $sout[$i]['merchantname'] = $shops[$i]->merchantname;
                $sout[$i]['mobile'] = $shops[$i]->mobile;
                $sout[$i]['mobile1'] = $shops[$i]->mobile1;
                $sout[$i]['mobile2'] = $shops[$i]->mobile2;
                $sout[$i]['phone'] = $shops[$i]->phone;
                $sout[$i]['province'] = $shops[$i]->province;
                //$sout[$i]['shopDescription'] = $shops[$i]->shopDescription;
                $sout[$i]['shopcategory'] = $shops[$i]->shopcategory;
                $sout[$i]['shopcategoryLink'] = $shops[$i]->shopcategoryLink;
                $sout[$i]['shortcode'] = $shops[$i]->shortcode;
                $sout[$i]['status'] = $shops[$i]->status;
                $sout[$i]['street'] = $shops[$i]->street;
                $sout[$i]['url'] = $shops[$i]->url;
                $sout[$i]['useImage'] = $shops[$i]->useImage;
                $sout[$i]['username'] = $shops[$i]->username;
                $sout[$i]['zip'] = $shops[$i]->zip;

                if(isset($shops[$i]->defaultpictures) && $shops[$i]->defaultpictures != '' ){
                    $dp = $shops[$i]->defaultpictures;
                    $sout[$i]['thumbnailUrl'] = $dp['thumbnail_url'];
                    $sout[$i]['largeUrl'] = $dp['large_url'];
                    $sout[$i]['mediumUrl'] = $dp['medium_url'];
                    $sout[$i]['fullUrl'] = $dp['full_url'];
                    $sout[$i]['fileUrl'] = $dp['fileurl'];
                }else{
                    $sout[$i]['thumbnailUrl'] = '';
                    $sout[$i]['largeUrl'] = '';
                    $sout[$i]['mediumUrl'] = '';
                    $sout[$i]['fullUrl'] = '';
                    $sout[$i]['fileUrl'] = '';
                }
            }

        return Response::json($sout);

    });

});

Route::get('testad/{mid}',function($mid){
    print(Jayonad::ad($mid));
});

Route::get('ad/redir/{id}',function($id){
    $ad = Ad::find($id);
    //print($ad->extURL);
    $u = Input::get('u');
    $s = Input::get('s');
    if(isset($u) && $u != ''){
        $extURL = base64_decode($u);
        if(preg_match('/^http:\/\//', $extURL) == false){
            $extURL = 'http://'.$extURL;
        }
    }else{

        if(preg_match('/^http:\/\//', $ad->extURL) == false){
            $extURL = 'http://'.$ad->extURL;
        }else{
            $extURL = $ad->extURL;
        }
    }

    Jayonad::logclick($ad,$s);

    return Redirect::to($extURL);
});



//tracker
Route::get('track/{id?}/{more?}',function($id = null,$more = null){

    homecrumb();

    if(is_null($more) || $more == ''){
        Breadcrumbs::addCrumb('Track Result','track/'.$id);
    }else{
        Breadcrumbs::addCrumb('Track Result','track/'.$id.'/'.$more);
    }

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

        $log = array_merge(array( 'c'=>'tracklist','buyer'=>$idvar ));
        Helpers::log($log);



        return View::make('tracklist')->with('order',$order)->with('phone',$id)->with('more',$more);
    }
});

Route::post('track',function(){

    homecrumb();

    $rule = array('phone'=>'required');

    $validation = Validator::make(Input::all(), $rule);

    if($validation->fails()){

        $log = array_merge(array( 'c'=>'track','s'=>'validation failed' ));

        Helpers::log($log);

        return Redirect::to('track')->withInput(Input::all())->withErrors($validation);

    }else{

        $in = Input::get();

        setcookie(
            'jextrack',
            $in['phone'],
            time() + (10 * 365 * 24 * 60 * 60)
        );

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

        $log = array_merge($in, array( 'c'=>'trackdetail', 'p'=>$in['phone'] ));
        Helpers::log($log);

        Breadcrumbs::addCrumb('Track Result',URL::to('/'));

        return View::make('tracklist')
            ->with('order',$order)
            ->with('phone',$idvar)
            ->with('more',$more);

    }



});


//offer
Route::get('offers/{keyword?}/{more?}',function($keyword = null,$more = null){

    if(is_null($keyword)){
        $offers = Offer::orderBy('shopcategory','asc')->orderBy('merchantId','asc')->get();
    }else{
        $offers = Offer::orderBy('shopcategory','asc')->orderBy('merchantId','asc')->get();
    }

    $offers2 = array();

    foreach($offers as $of){

        $baseurl = URL::to('ad/redir').'/'.$of->_id;

        if(isset($of->useImage) && $of->useImage == 'linked'){
            $of->banner = $of->extImageURL;
        }else{
            $of->banner = $of->defaultpictures['thumbnail_url'];
        }

        if(isset($of->externalLink) && $of->externalLink == 'yes'){
            $of->baseurl = $baseurl.'?u='.base64_encode($of->extURL).'&s='.$spot;
        }else{
            $of->baseurl = $baseurl.'?u='.base64_encode( URL::to( 'advert/'.$of->_id ) ).'&s=list';
        }

        $of->html = sprintf('<a style="border:none;display:inline-block;margin:auto;padding:4px;" class="jayon-ad" href="%s"  ><img src="%s" alt="%s" /></a>', $of->baseurl, $of->banner, $of->merchantName );

        $offers2[] = $of;
    }

    //print_r($offers2);

    $log = array_merge(array( 'c'=>'offerlist' ));
    Helpers::log($log);

    return View::make('offerlist')
        ->with('offers',$offers2)
        ->with('keyword',$keyword)
        ->with('more',$more);
});

//shops
Route::get('shops/{catlink}/{keyword?}/{more?}',function($catlink,$keyword = null,$more = null){

    $keyword = Input::get('keyword');
    $category = Input::get('cat');

    $is_search = false;

    if(is_null($keyword) || $keyword == ''){
        if($category != ''){
            $shops = Shop::where('shopcategoryLink',$catlink)
                        ->where('status','active')
                        ->orderBy('shopcategory','asc')
                        ->orderBy('merchantname','asc')->get();
        }else{
            $shops = Shop::where('status','active')
                        ->where('shopcategoryLink',$catlink)
                        ->orderBy('shopcategory','asc')
                        ->orderBy('merchantname','asc')->get();
        }
    }else{

        $is_search = true;

        if($category == ''){
            $shops = Shop::where('status','active')
                //->where('shopcategoryLink',$catlink)
                ->where(function($query) use($keyword){
                    $query->where('merchantname','like','%'.$keyword.'%')
                    ->orWhere('street','like','%'.$keyword.'%')
                    ->orWhere('mc_street','like','%'.$keyword.'%');
                })
                ->orderBy('shopcategory','asc')->orderBy('merchantname','asc')->get();
        }else{
            $shops = Shop::where('status','active')
                //->where('shopcategoryLink',$catlink)
                ->where(function($query) use($keyword){
                    $query->where('merchantname','like','%'.$keyword.'%')
                        ->orWhere('street','like','%'.$keyword.'%')
                        ->orWhere('mc_street','like','%'.$keyword.'%');
                })
                ->orderBy('shopcategory','asc')->orderBy('merchantname','asc')->get();
        }
    }

    $log = array_merge(array( 'c'=>'shoplist' ));
    Helpers::log($log);

    if(isset($shops) && count($shops) > 0){
        $catname = $shops[0]->shopcategory;
    }else{
        $catname = 'Shops';
    }

    homecrumb();
    if($is_search){
        Breadcrumbs::addCrumb('Search Result','shops/'.$catlink.'/'.$keyword.'/'.$more);
    }else{
        Breadcrumbs::addCrumb($catname,'shops/'.$catlink.'/'.$keyword.'/'.$more);
    }

    return View::make('shoplist')
        ->with('shops',$shops)
        ->with('is_search',$is_search)
        ->with('keyword',$keyword)
        ->with('category',$catlink)
        ->with('more',$more);
});

Route::post('shops/{catlink}/{keyword?}/{more?}',function($catlink,$keyword = null,$more = null){

    if(is_null($keyword)){
        $shops = Shop::where('shopcategoryLink',$catlink)->orderBy('shopcategory','asc')->orderBy('merchantname','asc')->get();
    }else{
        $shops = Shop::where('shopcategoryLink',$catlink)->get();
    }

    $log = array_merge(array( 'c'=>'shoplist' ));
    Helpers::log($log);

    homecrumb();
    Breadcrumbs::addCrumb('Shops','shops/'.$catlink.'/'.$keyword.'/'.$more);

    return View::make('shoplist')
        ->with('shops',$shops)
        ->with('keyword',$keyword)
        ->with('more',$more);
});

Route::get('shopcat',function(){

    //$shops = Jayonad::getShopCategory();

    $shops = Shopcategory::orderBy('name','asc')->get();


    $log = array_merge(array( 'c'=>'shopcatlist' ));
    Helpers::log($log);

    return View::make('shopcatlist')
        ->with('shops',$shops);
});

Route::get('shop/{catlink}/{id?}',function($catlink,$id = null){
//Route::get('shop/{catlink}/{id?}',function($catlink,$id = null){
    if(is_null($id)){
        $shop = false;
    }else{
        //$shop = Shop::where('id', intval($id) )->whereOr('id', strval($id))->first();
        //$shop = Shop::where('id', strval($id))->first();
        $shop = Shop::find($id);
    }

    $logshop = ($shop && isset($shop->_id))?$shop->_id:$shop;

    $log = array_merge(array( 'c'=>'shopdetail', 'sid'=>$id, 'msid'=>$logshop ));
    Helpers::log($log);

    homecrumb();
    //Breadcrumbs::addCrumb($shop->shopcategory,'shops/'.$catlink);
    Breadcrumbs::addCrumb($shop->merchantname,'shop/'.$catlink.'/'.$id);

    return View::make('shopdetail')
        ->with('category',$catlink)
        ->with('shop',$shop);
});

Route::get('advert/{id}',function($id){

    $ad = Ad::find($id);

    if($ad){

    }else{
        $ad = false;
    }

    return View::make('advert')
        ->with('ad',$ad);
});

Route::get('item/{did}/{phone}/{more?}',function($did,$phone,$more = null){
    homecrumb();
    //    <a href="{{ URL::to('track/'.$phone.'/'.$more) }}">&laquo; Back to Track List</a>

    if(is_null($more) || $more == ''){
        Breadcrumbs::addCrumb('Track Result','track/'.$phone);
    }else{
        Breadcrumbs::addCrumb('Track Result','track/'.$phone.'/'.$more);
    }

    //Breadcrumbs::addCrumb('Track Result','track/'.$phone.'/'.$more);

    Breadcrumbs::addCrumb('Order Status','/');
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

function homecrumb(){
        Breadcrumbs::setDivider('/');
        Breadcrumbs::setCssClasses('breadcrumb');
        Breadcrumbs::addCrumb('Home',URL::to('/'));
}
