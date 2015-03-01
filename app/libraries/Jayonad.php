<?php
class Jayonad {

    public static function ad($merchant_id = 'random', $baseurl = 'redir' ,$format = 'html')
    {

        if($merchant_id == 'random'){

            $count = Ad::count();
            $rand = mt_rand(0,$count);

            $ad = Ad::take(1)
                    ->skip($rand)
                    ->orderBy('createdDate','desc')
                    ->get();
        }else{
            $ad = Ad::where('merchantId',$merchant_id)
                    ->orderBy('createdDate','desc')
                    ->get();
        }

        if( count($ad->toArray() ) > 0){
           //print_r($ad);
           $advert = $ad[0];
        }else{
            $advert = Ad::where('isDefault','yes')
                    ->orderBy('createdDate','desc')
                    ->first();
           //print_r($ad);
        }

        if($baseurl == 'redir'){
            $baseurl = URL::to('ad/redir').'/'.$advert->_id;
        }else{
            $baseurl = $advert->extURL;
        }

        self::logview($advert);

        if($format == 'html'){
            $html = sprintf('<a style="border:none;display:inline-block;margin:auto;padding:4px;" class="text-center-sm  text-center-md  text-center-lg" href="%s"  ><img src="%s" alt="%s" /></a>', $baseurl, $advert->defaultpictures['thumbnail_url'], $advert->merchantName );
            return $html;
        }

    }

    public static function logview($ad)
    {
        $ad = $ad->toArray();
        $ad['adId'] = $ad['_id'];
        unset($ad['_id']);
        $ad['viewedAt'] = new MongoDate();

        $httpobj = array_merge($_SERVER, $_GET );

        $ad['http'] = $httpobj;

        Adview::insert($ad);

        return true;
    }

    public static function logclick($ad)
    {
        $ad = $ad->toArray();
        $ad['adId'] = $ad['_id'];
        unset($ad['_id']);
        $ad['clickedAt'] = new MongoDate();

        $httpobj = array_merge($_SERVER, $_GET );

        $ad['http'] = $httpobj;

        Adclick::insert($ad);

        return true;
    }

}