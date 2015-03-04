@extends('layout.front')

@section('content')
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
        <div class="text-justify text-center-sm  text-center-md  text-center-lg">
          {{ Jayonad::ad('random') }}
        </div>
    </div>
    <div class="track-list-item" style="display:block;height:25px;">

    </div>

@stop