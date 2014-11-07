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
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>Delivered</th>
                        <th>Pending</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Order</td>
                        <td>{{ $total }}</td>
                        <td>{{ $total_delivered }}</td>
                        <td>{{ $total_pending }}</td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td>{{ $total_pics }}</td>
                        <td>{{ $total_delivered_pics }}</td>
                        <td>{{ $total_pending_pics }}</td>
                    </tr>
                    <tr>
                        <td>Sign</td>
                        <td>{{ $total_sign }}</td>
                        <td>{{ $total_delivered_sign }}</td>
                        <td>{{ $total_pending_sign }}</td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>{{ $total_notes }}</td>
                        <td>{{ $total_delivered_notes }}</td>
                        <td>{{ $total_pending_notes }}</td>
                    </tr>
                </tbody>
            </table>
            {{--
            Total : {{ $total }}<br />
            Delivered : {{ $total_delivered }}<br />
            Pending : {{ $total_pending }}<br />
            Order with Photo : {{ $total_pics }}<br />
            Order with Sign : {{ $total_sign }}<br />
            Order with Note : {{ $total_notes }}<br />
            --}}
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
            <span class="tdate">Photo: {{ $r['pics'] }}</span>
            <span class="tdate">Sign: {{ $r['sign'] }}</span>
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