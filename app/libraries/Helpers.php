<?php
class Helpers {
    public static function get_thumbnail($delivery_id){

        if(file_exists(Config::get('ks.picture_path').$delivery_id.'.jpg')){
            if(file_exists(Config::get('ks.thumb_path').'th_'.$delivery_id.'.jpg')){
                $thumbnail = URL::to('/').'/public/receiver_thumbs/th_'.$delivery_id.'.jpg';
                $thumbnail = sprintf('<img style="cursor:pointer;" class="thumb" alt="'.$delivery_id.'" src="%s?'.time().'" />',$thumbnail);
            }else{
                $thumbnail = 'nopic';
            }
        }else{
            $thumbnail = 'nopic';
        }

        return $thumbnail;
    }
}
