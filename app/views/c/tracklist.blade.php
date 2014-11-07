@extends('layout.front')

@section('content')
    <div class="track-list-item">
        <p>Klik untuk melihat rincian
        </p>
    </div>

<div class="row">
    <div class="login-form">
        <h3>Summary</h3>
        <hr />
        <p>
            Tanggal : {{ $reportdate }}<br />
            Total : {{ $total }}<br />
            Delivered : {{ $total_delivered }}<br />
            Pending : {{ $total_pending }}<br />
            Order with Photo : {{ $total_pics }}<br />
            Order with Sign : {{ $total_sign }}<br />
            Order with Note : {{ $total_notes }}<br />
        </p>
    </div>
</div>

@foreach($order as $r)
    <div class="track-list-item">
        <a href="{{ URL::to('item/'.$r['delivery_id'].'/'.$device.'/'.$more ) }}">
            {{-- print_r($r) --}}
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