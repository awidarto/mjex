@extends('layout.front')

@section('content')
      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array');
      ?>
    <div class="track-list-item">
        <div class="text-center">
          {{ $ad_1['html'] }}
        </div>
    </div>
    <div class="track-list-item">
        <p>Klik untuk melihat rincian
        </p>
    </div>
@foreach($order as $r)
    <div class="track-list-item">
        <a href="{{ URL::to('item/'.$r['delivery_id'].'/'.$phone.'/'.$more ) }}">
            {{-- print_r($r) --}}
            <span class="tdate">{{ $r['assignment_date']}}</span>
            <span class="tmerchant">{{ $r['merchantname']}}</span>
            <span class="tid"> Kode Toko (akhiran) : {{ short_id($r['merchant_trans_id']) }}</span>
            <span class="tid">{{ $r['delivery_type']}}</span>
        </a>
    </div>
@endforeach
@if(!is_null($more))
    <div class="track-list-item">
        <a href="{{ URL::to('track/'.$phone.'/more') }}">Show more</a>
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