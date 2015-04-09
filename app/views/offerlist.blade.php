@extends('layout.front')

@section('content')
    <div class="track-list-item">
        <p>Klik untuk melihat detail promo
        </p>
    </div>
    <?php $mid = 0 ?>
@foreach($offers as $r)
    @if($mid != $r->merchantId)
    <div class="track-list-item">
        <p>
            <span class="tmerchant">{{ $r->merchantName }}</span>
        </p>
    </div>
    @endif
    <div class="track-list-item">
        <div class="offer">
            <span class="tid">{{ $r->itemDescription }}</span>
            {{ $r->html }}
        </div>
    </div>
    <?php $mid = $r->merchantId ?>
@endforeach
@if(!is_null($more))
    <div class="track-list-item">
        <a href="{{ URL::to('shops/'.$keyword.'/more') }}">Show more</a>
    </div>
@endif
    <div class="track-list-item" style="display:block;height:25px;">

    </div>

@stop