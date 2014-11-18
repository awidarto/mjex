<?php
class Helpers {
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
        $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'.jpg';

        if(file_exists($fullpath)){
            $thumbnail = URL::to('/').Config::get('ks.picture_path').$delivery_id.'.jpg';
            //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
        }else{
            //$thumbnail = 'nopic';
            $thumbnail = URL::to('img/th_nopic.jpg');
        }

        //$thumbnail = URL::to('/').'/storage/receiver_thumb/th_'.$delivery_id.'.jpg';

        return $thumbnail;
    }

    public static function get_multifullpic($delivery_id){

        $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'*.jpg';

        $files = glob($fullpath);

        $thumbnail = array();

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

        return $thumbnail;
    }

    public static function get_signpic($delivery_id){
        $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign.jpg';

        if(file_exists($fullpath)){
            $thumbnail = URL::to('/').Config::get('ks.picture_path').$delivery_id.'_sign.jpg';
            //$thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
        }else{
            //$thumbnail = 'nopic';
            $thumbnail = URL::to('img/th_nopic.jpg');
        }

        //$thumbnail = URL::to('/').'/storage/receiver_thumb/th_'.$delivery_id.'.jpg';

        return $thumbnail;
    }

    public static function picexists($delivery_id)
    {
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

    public static function signexists($delivery_id)
    {
            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign*.jpg';
            $files = glob($fullpath);

            $count = 0;
            if(is_array($files) && count($files) > 0){
                return count($files);
            }else{
                return false;
            }

    }

    public static function get_multisign($delivery_id)
    {
            $fullpath = public_path().Config::get('ks.picture_path').$delivery_id.'_sign*.jpg';
            $files = glob($fullpath);

            $thumbnail = array();

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

            return $thumbnail;

    }

    public static function log($input){
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
            if($d < 3000){
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
