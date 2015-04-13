@extends('layout.front')

@section('content')
      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array', 'top1');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array', 'bottom1');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array', 'bottom2');
    /*
    <div class="track-list-item">
        <div class="text-center">
          {{ $ad_1['html'] }}
        </div>
    </div>
    */
      ?>
    <div class="login-form">
        <div class="text-center">
            <form action="{{ URL::to('track')}}" method="post" accept-charset="utf-8">
                <label for="phone">Search</label>
                <input type="text" name="keyword" id="phone" value="" />
                @if($errors->has('keyword'))
                {{ $errors->first('keyword', '<div class="alert"><a href="#" class="close">x</a>:message</div>'); }}
                @endif
                <input type="submit" name="search" id="" value="Search" class="button" />
                <p style="font-size:11px;">Untuk AWB / Nomor Paket, mohon tuliskan persis sesuai yang tertera di invoice / resi</p>
            </form>
        </div>
    </div>
    <div class="track-list-item">
        <p>Klik untuk melihat detail toko
        </p>
    </div>
    <?php $cat = '' ?>
@foreach($shops as $r)
    @if($cat != $r->shopcategoryLink)
    <div class="track-list-item merchant-cat">
        <span class="tmerchant">{{ $r->shopcategory }}</span>
    </div>
    @endif
    <div class="track-list-item">
        <a href="{{ URL::to('shop/'.$r['id'] ) }}">
            <span class="tmerchant">{{ $r['merchantname']}}</span>
            <span class="tid">{{ $r['street'].' '.$r['city'] }}</span>
        </a>
    </div>
    <?php $cat = $r->shopcategoryLink ?>
@endforeach
@if(!is_null($more))
    <div class="track-list-item">
        <a href="{{ URL::to('shops/'.$keyword.'/more') }}">Show more</a>
    </div>
@endif

    <div class="track-list-item">
        <div class="text-center">
          {{ $ad_2['html'].'<br />'.$ad_3['html'] }}
        </div>
    </div>
    <div class="track-list-item" style="display:block;height:25px;">

    </div>

@stop