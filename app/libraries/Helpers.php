<?php
class Helpers {

    public static function getNotes($delivery_id, $as_array = true)
    {
        $notes = Deliverynote::where('deliveryId','=',$delivery_id)
                    ->orderBy('mtimestamp','desc')
                    ->get();

        if($as_array){
            return $notes->toArray();
        }else{
            $list = '<ul class="note_list">';
            foreach($notes as $note){
                $list .= '<li>';
                $list .= '<b>'.$note->status.'</b><br />';
                $list .= $note->datetimestamp.'<br />';
                $list .= $note->note;
                $list .= '</li>';
            }

            $list .= '</ul>';

            return $list;
        }

    }

    public static function getLoc($order){

        $delilat = $order['latitude'];
        $delilon = $order['longitude'];

        $statuses = array('cr_assigned','delivered','pending');

        $locs = Geolog::where('deliveryId','=',$d)
            ->where(function($qx){
                $qx->where('status','=','delivered')
                    ->orWhere(function($qc){
                        $qc->where('event','=','capture location photo take')
                            ->where('status','=','cr_assigned');
                    })
                    ->orWhere(function($qp){
                        $qp->where('event','=','capture location photo take')
                            ->where('status','=','pending');
                    });
            })
            //->whereIn('status',$statuses)
            ->where('latitude','!=',0.0)
            ->where('longitude','!=',0.0)
            ->orderBy('mtimestamp','desc')
            ->orderBy('status','desc')
            ->get( array('datetimestamp','status','event','latitude','longitude'));

        $fo = false;
        $clat = 0;
        $clon = 0;
        foreach($locs as $l){
            print $l->datetimestamp.' '.$l->status.' '.$l->event.' '.$l->latitude.' '.$l->longitude."<br />\r\n";
            if($l->event == 'capture location photo take' && $fo == false){
                $clat = $l->latitude;
                $clon = $l->longitude;
                $fo = true;
            }

        }

        $lat = ($clat == 0)?$delilat:$clat;
        $lon = ($clon == 0)?$delilon:$clon;


        return array('latitude'=>$lat,'longitude'=>$lon);
    }

    public static function idr($in){
        return number_format((double) $in,2,',','.');
    }

    public static function get_thumbnail($delivery_id){
        $fullpath = public_path().Config::get('ks.thumb_path').'th_'.$delivery_id.'.jpg';

        if(file_exists($fullpath)){
            $thumbnail = URL::to('/').Config::get('ks.thumb_path').'th_'.$delivery_id.'.jpg';
            //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
        }else{
            //$thumbnail = 'nopic';
            $thumbnail = URL::to('img/th_nopic.jpg');
        }

        //$thumbnail = URL::to('/').'/storage/receiver_thumb/th_'.$delivery_id.'.jpg';

        return $thumbnail;
    }

    public static function get_fullpic($delivery_id){


        $pics_db = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',0)
                            ->orWhere('is_signature','=',strval(0));
                    })
                    ->first();

        $out_db = false;
        if($pics_db){
            if(isset($pics_db->full_url)){
                $thumbnail = $pics_db->full_url;
            }else{
                $out_db = true;
            }
        }else{
                $out_db = true;
        }

        if($out_db){
            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'.jpg';

            if(file_exists($fullpath)){
                $thumbnail = URL::to('/').Config::get('ks.picture_path').$delivery_id.'.jpg';
                //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
            }else{
                //$thumbnail = 'nopic';
                $thumbnail = URL::to('img/th_nopic.jpg');
            }
        }

        //$thumbnail = URL::to('/').'/storage/receiver_thumb/th_'.$delivery_id.'.jpg';

        return $thumbnail;
    }

    public static function get_multifullpic($delivery_id){

        $pic_count = 0;

        $sign_count = 0;

        $app = 'app v 1.0';


        $thumbnail = array();

        $pics_db = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',0)
                            ->orWhere('is_signature','=',strval(0));
                    })
                    ->get();

        if($pics_db){

            if(count($pics_db->toArray()) > 0){
                foreach($pics_db as $pic){
                    $pic_count++;
                    $thumbnail[] = $pic->full_url;
                }
            }

        }

        if($pic_count == 0 ){

            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'*.jpg';

            $files = glob($fullpath);

            if(is_array($files) && count($files) > 0){
                foreach($files as $file){
                    if(preg_match('/_sign/', $file) == false){
                        $file = str_replace(public_path(), '', $file);
                        $thumbnail[] = URL::to('/').$file;
                    }
                }

                if(count($thumbnail) == 0){
                    $thumbnail[] = URL::to('img/th_nopic.jpg');
                }

            }else{

                $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'.jpg';

                if(file_exists($fullpath)){
                    $thumbnail[] = URL::to('/').Config::get('ks.picture_path').$delivery_id.'.jpg';
                    //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
                }else{
                    //$thumbnail = 'nopic';
                    $thumbnail[] = URL::to('img/th_nopic.jpg');
                }

            }

        }

        return $thumbnail;
    }

    public static function get_signpic($delivery_id){

        $pics_db = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',1)
                            ->orWhere('is_signature','=',strval(1));
                    })
                    ->first();

        $out_db = false;
        if($pics_db){
            if(isset($pics_db->full_url)){
                $thumbnail = $pics_db->full_url;
            }else{
                $out_db = true;
            }
        }else{
                $out_db = true;
        }

        if($out_db){
            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign.jpg';

            if(file_exists($fullpath)){
                $thumbnail = URL::to('/').Config::get('ks.picture_path').$delivery_id.'_sign.jpg';
                //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
            }else{
                //$thumbnail = 'nopic';
                $thumbnail = URL::to('img/th_nopic.jpg');
            }
        }

        //$thumbnail = URL::to('/').'/storage/receiver_thumb/th_'.$delivery_id.'.jpg';

        return $thumbnail;
    }

    public static function picexists($delivery_id)
    {
        $pics_count = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',0)
                            ->orWhere('is_signature','=',strval(0));
                    })
                    ->count();

        if($pics_count > 0){
            return $pics_count;
        }else{

            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'*.jpg';
            $files = glob($fullpath);

            $count = 0;
            if(is_array($files) && count($files) > 0){
                foreach($files as $file){
                    if(preg_match('/_sign/', $file) == false){
                        $count++;
                    }
                }

                if($count == 0){
                    return false;
                }else{
                    return $count;
                }

            }else{
                return false;
            }


        }


    }

    public static function signexists($delivery_id)
    {
        $pics_count = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',1)
                            ->orWhere('is_signature','=',strval(1));
                    })
                    ->count();

        if($pics_count > 0){
            return $pics_count;
        }else{


            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign*.jpg';
            $files = glob($fullpath);

            $count = 0;
            if(is_array($files) && count($files) > 0){
                return count($files);
            }else{
                return false;
            }
        }

    }

    public static function get_multisign($delivery_id)
    {
        $pic_count = 0;

        $sign_count = 0;

        $app = 'app v 1.0';


        $thumbnail = array();

        $pics_db = Uploaded::where('parent_id','=',$delivery_id)
                    ->where(function($q){
                        $q->where('is_signature','=',1)
                            ->orWhere('is_signature','=',strval(1));
                    })
                    ->get();

        if($pics_db){
            /*
            if(count($pics_db->toArray()) > 0){


                foreach($pics_db as $pic){
                    $sign_count++;
                    $thumbnail[] = $pic->full_url;
                }

            }else{
                $thumbnail[] = URL::to('img/th_nopic.jpg');
            }
            */

            if(count($pics_db->toArray()) > 0){
                foreach($pics_db as $pic){
                    $pic_count++;
                    $thumbnail[] = $pic->full_url;
                }
            }

        }

        if($pic_count == 0){

            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign*.jpg';
            $files = glob($fullpath);

            if(is_array($files) && count($files) > 0){
                foreach($files as $file){
                    $file = str_replace(public_path(), '', $file);
                    $thumbnail[] = URL::to('/').$file;
                }
            }elseif (is_array($files) && count($files) == 0) {
                $thumbnail[] = false;
            }else{

                $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign.jpg';

                if(file_exists($fullpath)){
                    $thumbnail[] = URL::to('/').Config::get('ks.picture_path').$delivery_id.'_sign.jpg';
                    //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
                }else{
                    //$thumbnail = 'nopic';
                    $thumbnail[] = false;
                }

            }

        }

        return $thumbnail;

    }

    public static function log($input){
        $input['timestamp'] = new MongoDate();
        Logger::insert($input);
    }

    public static function colorizelatlon($lat, $lon, $field = 'lat'){

        $d = 0;
        $loc_set = true;

        if($lat == 'Set Loc'){
            $loc_set = false;
        }else{
            $d = self::vincentyGreatCircleDistance( Config::get('ks.origin_lat'), Config::get('ks.origin_lon'), $lat, $lon );
        }

        if($d < 3000 && $loc_set == true){

            if($field == 'lat'){
                return sprintf('<span class="%s">%s</span>','redblock',$lat);
            }elseif ($field == 'lon') {
                return sprintf('<span class="%s">%s</span>','redblock',$lon);
            }else{
                return sprintf('<span class="%s">%s</span>','redblock',$lat.','.$lon);
            }
        }else{
            if($field == 'lat'){
                return $lat;
            }elseif ($field == 'lon') {
                return $lon;
            }else{
                return $lat.','.$lon;
            }

        }

    }

    public static function nearOrigin($lat, $lon)
    {
        if(($lat != 0 && $lat != 0) || ($lat != ''  && $lat != '' ) ){
            $d = self::vincentyGreatCircleDistance( Config::get('ks.origin_lat'), Config::get('ks.origin_lon'), $lat, $lon );
            if($d < 1000){
                return true;
            }else{
               return false;
            }
        }else{
            return false;
        }
    }


    public static function vincentyGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
      // convert from degrees to radians
      $latFrom = deg2rad($latitudeFrom);
      $lonFrom = deg2rad($longitudeFrom);
      $latTo = deg2rad($latitudeTo);
      $lonTo = deg2rad($longitudeTo);

      $lonDelta = $lonTo - $lonFrom;
      $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
      $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

      $angle = atan2(sqrt($a), $b);
      return $angle * $earthRadius;
    }



}
