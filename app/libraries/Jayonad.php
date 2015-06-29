<?php
class Jayonad {

    public static $shopcategory;

    public static function getShopCategory(){
        $c = Shopcategory::get();
        self::$shopcategory = $c;
        return new self;
    }

    public function ShopCatToSelection($value, $label, $all = true)
    {
        if($all){
            $ret = array(''=>'All');
        }else{
            $ret = array();
        }

        foreach (self::$shopcategory as $c) {
            $ret[$c->{$value}] = $c->{$label};
        }


        return $ret;
    }

    public function shopcatToArray()
    {
        return self::$shopcategory;
    }


    public static function ad($merchant_id = 'random', $exclude = null ,$baseurl = 'redir' ,$format = 'html',$spot = null)
    {

        if(Config::get('site.ad') ){

            if(is_null($spot)){
                $spot = 'def';
            }

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

               if($advert->_id === $exclude || $advert->_id == new MongoId($exclude)){
                    $advert = Ad::where('isDefault','yes')
                            ->orderBy('createdDate','desc')
                            ->first();
               }

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

            self::logview($advert, $spot);

            if(isset($advert->defaultpictures['thumbnail_url']) && $advert->defaultpictures['thumbnail_url'] != ''){
                $banner = $advert->defaultpictures['thumbnail_url'];
            }else{
                $advert = Ad::where('isDefault','yes')
                        ->orderBy('createdDate','desc')
                        ->first();
            }

            if(isset($advert->useImage) && $advert->useImage == 'linked'){
                $banner = $advert->extImageURL;
            }else{
                $banner = $advert->defaultpictures['thumbnail_url'];
            }

            if(isset($advert->externalLink) && $advert->externalLink == 'yes'){
                $baseurl = $baseurl.'?u='.base64_encode($advert->extURL).'&s='.$spot;
            }else{
                $baseurl = $baseurl.'?u='.base64_encode( URL::to( 'advert/'.$advert->_id ) ).'&s='.$spot;
            }

            $html = sprintf('<a style="border:none;display:inline-block;margin:auto;padding:4px;" class="jayon-ad" href="%s"  ><img src="%s" alt="%s" /></a>', $baseurl, $banner, $advert->merchantName );

            if($format == 'html'){
                return $html;
            }elseif($format == 'array'){
                return array('id'=>$advert->_id, 'html'=>$html, 'url'=>$banner);
            }
        }else{
            if($format == 'html'){
                return '';
            }elseif($format == 'array'){
                return array('id'=>0, 'html'=>'', 'url'=>'');
            }
        }

    }

    public static function logview($ad, $spot)
    {
        $ad = $ad->toArray();
        $ad['adId'] = $ad['_id'];
        $ad['spot'] = $spot;
        unset($ad['_id']);
        $ad['viewedAt'] = new MongoDate();

        $httpobj = array_merge($_SERVER, $_GET );

        $ad['http'] = $httpobj;

        $ad['pageUri'] = isset($httpobj['REDIRECT_URL'])?$httpobj['REDIRECT_URL']:'';

        Adview::insert($ad);

        return true;
    }

    public static function logclick($ad, $spot)
    {
        $ad = $ad->toArray();
        $ad['adId'] = $ad['_id'];
        $ad['spot'] = $spot;
        unset($ad['_id']);
        $ad['clickedAt'] = new MongoDate();

        $httpobj = array_merge($_SERVER, $_GET );

        $ad['http'] = $httpobj;

        $ad['pageUri'] = isset($httpobj['REDIRECT_URL'])?$httpobj['REDIRECT_URL']:'';

        Adclick::insert($ad);

        return true;
    }

}