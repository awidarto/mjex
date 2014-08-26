@extends('layout.front')

@section('content')

@foreach($order as $r)
    <div>
        {{-- print_r($r) --}}
        {{ $r['merchant_trans_id']}}<br />
        {{ $r['merchantname']}}<br />
    </div>
@endforeach

@stop