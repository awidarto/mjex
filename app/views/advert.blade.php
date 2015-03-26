@extends('layout.front')

@section('content')
<style type="text/css">
  .arrow {
    font-size: 48px;
  }

  .advert-body{
    padding: 15px;
    text-align: center;
  }

  .advert-body p{
    text-align: justify;
  }
</style>
<div class="row">
  <div class="text-center">
    <?php
      $ad1 = Jayonad::ad($ad->merchantId, $ad->_id, 'redir', 'array','top1' );
      $ad2 = Jayonad::ad($ad->merchantId, $ad->_id, 'redir', 'array','bottom1' );
    ?>
    {{-- $ad1['html'] --}}
  </div>
</div>
<div class="row">
    <div class="text-justify advert-body">
      @if($ad)
        <h3>{{ $ad->itemDescription }}</h3>
        <?php
          if(isset($advert->useImage) && $advert->useImage == 'linked'){
              $banner = $ad->extImageURL;
          }else{
              $banner = $ad->defaultpictures['thumbnail_url'];
          }
        ?>
        <img src="{{ $banner }}" alt="{{ $ad->itemDescription }}" >
        {{ (isset($ad->advertorial) )?$ad->advertorial:'' }}
      @endif
    </div>
</div>

<div class="row">
  <div class="text-center">
    {{ $ad2['html'] }}
  </div>
</div>

@stop