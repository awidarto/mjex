@extends('layout.front')

@section('content')

@foreach($order as $r)
    <div class="track-list-item">
        <a href="{{ URL::to('item/'.$r['delivery_id']) }}">
            {{-- print_r($r) --}}
            <span class="tdate">{{ $r['assignment_date']}}</span>
            <span class="tmerchant">{{ $r['merchantname']}}</span>
            <span class="tid"> ID ( ends with ) : {{ $r['merchant_trans_id'] }}</span>
        </a>
    </div>
@endforeach

@stop