@extends('layout.front')

@section('content')

<div class="row">
      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array','top1');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array','bottom1');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array','bottom2');

        if(isset($_COOKIE['jextrack'])){
          $last_keyword = $_COOKIE['jextrack'];
        }else{
          $last_keyword = '';
        }

      ?>
  <div class="login-form">
    <div class="text-center">
      {{-- $ad_1['html'] --}}
    </div>
    <h3>Order Status</h3>
    <hr />
    <form action="{{ URL::to('track')}}" method="post" accept-charset="utf-8">
      <label for="phone">Phone / Mobile Number / No Invoice Toko:</label>
      <input type="text" name="phone" id="phone" value="{{ $last_keyword }}" />
      @if($errors->has('phone'))
        {{ $errors->first('phone', '<div class="alert"><a href="#" class="close">x</a>:message</div>'); }}
      @endif
      <input type="submit" name="track" id="" value="Track" class="button" />
      <p style="font-size:11px;">Untuk AWB / Nomor Paket, mohon tuliskan persis sesuai yang tertera di invoice / resi</p>
    </form>
  </div>
</div>
<div class="row">
    <div class="track-list-item">
      <p>
        Shops
      </p>
    </div>

<?php $cat = '';
    //print_r($shops);
 ?>
@foreach($shops as $r)
    @if($cat != $r->shopcategoryLink)
      <div class="track-list-item merchant-cat logo">
          <span class="tmerchant">{{ $r->shopcategory }}</span>
      </div>
    @endif
    <div class="track-list-item logo-item">
        <a href="{{ URL::to('shop/'.$r->shopcategoryLink.'/'.$r['id'] ) }}">
            @if(isset($r->defaultpictures) && is_array($r->defaultpictures) )
                <img class="logo" src="{{$r->defaultpictures['medium_url']}}" alt="{{ $r->merchantname}}" />
            @else
              <span class="tmerchant">{{ $r->merchantname }}</span>
            @endif
            {{--<span class="tid">{{ $r['street'].' '.$r['city'] }}</span>--}}
        </a>
    </div>
    <?php $cat = $r->shopcategoryLink ?>
@endforeach

{{--
@foreach($shops as $r)

    <div class="track-list-item">
        <a href="{{ URL::to('shops/'.$r['slug'] ) }}">
            <span class="tmerchant">{{ $r['name']}}</span>
            <span class="tid">{{ $r['description'] }}</span>
        </a>
    </div>

@endforeach
--}}


  <div class="text-center">
      {{-- $ad_2['html'].'<br />'.$ad_3['html'] --}}
  </div>

</div>

@stop