@extends('layout.front')

@section('content')
    <div class="track-list-item">
        <p>Klik untuk melihat rincian
        </p>
    </div>

    <div>
        <h6>Summary</h6>
        Tanggal : {{ $reportdate }}<br />
    </div>
@foreach($order as $r)
    <div class="track-list-item">
        <a href="{{ URL::to('item/'.$r['delivery_id'].'/'.$phone.'/'.$more ) }}">
            {{-- print_r($r) --}}
            <?php
                $pics = (Helpers::picexists($r['delivery_id']))?Helpers::picexists($r['delivery_id']):'Tidak ada';
                $sign = (Helpers::signexists($r['delivery_id']))?'Ada':'Tidak Ada';
            ?>
            <span class="tdate">{{ $r['assignment_date']}}</span>
            <span class="tmerchant">{{ $r['merchantname']}}</span>
            <span class="tid"> Kode Toko (akhiran) : {{ short_id($r['merchant_trans_id']) }}</span>
            <span class="tdate">{{ $r['status']}}</span>
            <span class="tdate">Photo: {{ $pics }}</span>
            <span class="tdate">Sign: {{ $sign }}</span>
            <span class="tdate">Note: {{ $r['delivery_note'] }}</span>
        </a>
    </div>
@endforeach
{{--
@if(is_null($more))
    <div class="track-list-item">
        <a href="{{ URL::to('c/track/'.$phone.'/more') }}">Show more</a>
    </div>
@endif
--}}
@stop