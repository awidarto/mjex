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
        </a>
    </div>
@endforeach
@if(is_null($more))
    <div class="track-list-item">
        <a href="{{ URL::to('track/'.$phone.'/more') }}">Show more</a>
    </div>
@endif

@stop