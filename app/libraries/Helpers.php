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

}
