@extends('layout.front')

@section('content')
    <style type="text/css">
        table{
            color: #000;
            font-size: 14px;
            font-weight: bold;
        }

        table thead tr th{
            font-weight: bold;
        }

        .orange{
            background-color: orange;
            color: black;
        }

        .dark_green{
            background-color: #006400;
            color: white;
        }

        .green{
            background-color: #008000;
            color: white;
        }

        .red, .redblock{
            background-color: red;
            color: white;
        }

        .block{
            padding: 2px 4px;
        }

        .tdate{
            margin-top: 5px;
            padding: 2px;
        }

    </style>
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
            Device / Courier : {{ $device }}<br />
            <table class="responsive">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th colspan="3">Status</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th>Tidak Ada</th>
                        <th>Delivered</th>
                        <th>Pending</th>
                        <th>Status Lain</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Order</td>
                        <td>{{ $total }}</td>
                        <td>-</td>
                        <td>{{ $total_delivered }}</td>
                        <td>{{ $total_pending }}</td>
                        <td>{{ $total_other }}</td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td>{{ $total_pics }}</td>
                        <td>{{ $total_no_pics }}</td>
                        <td>{{ $total_delivered_pics }}</td>
                        <td>{{ $total_pending_pics }}</td>
                        <td>{{ $total_other_pics }}</td>
                    </tr>
                    <tr>
                        <td>Sign</td>
                        <td>{{ $total_sign }}</td>
                        <td>{{ $total_no_sign }}</td>
                        <td>{{ $total_delivered_sign }}</td>
                        <td>{{ $total_pending_sign }}</td>
                        <td>{{ $total_other_sign }}</td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>{{ $total_notes }}</td>
                        <td>{{ $total_no_notes }}</td>
                        <td>{{ $total_delivered_notes }}</td>
                        <td>{{ $total_pending_notes }}</td>
                        <td>{{ $total_other_notes }}</td>
                    </tr>
                    <tr>
                        <td>Kordinat Lokasi</td>
                        <td>{{ $total_coord }}</td>
                        <td>{{ $total_no_coord }}</td>
                        <td>{{ $total_delivered_coord }}</td>
                        <td>{{ $total_pending_coord }}</td>
                        <td>{{ $total_other_coord }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                </tfoot>
            </table>
            <p>
                Geser tabel untuk melihat lebih lengkap
            </p>
            <hr />
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
        <a href="{{ URL::to('c/item/'.$r['delivery_id'].'/'.$device.'/'.$more ) }}">
            {{-- print_r($r) --}}
            <span class="tdate">{{ $r['assignment_date']}}</span>
            <span class="tmerchant">{{ $r['merchantname']}}</span>
            <span class="tmerchant">Pembeli: {{ $r['buyer_name']}}</span>
            <span class="tmerchant">Ditujukan : {{ $r['recipient_name']}}</span>
            <span class="tid"> Kode Toko (akhiran) : {{ short_id($r['merchant_trans_id']) }}</span>
            <span class="tdate">Status: <span class=" {{ ($r['status'] == 'pending')?'orange block':'' }}">{{ $r['status'] }}</span></span>
            <span class="tdate">Photo: <span class=" {{ ($r['has_pic'])?'':'dark_green block' }}">{{ $r['pics'] }}</span></span>
            <span class="tdate">Sign: <span class=" {{ ($r['has_pic'])?'':'dark_green block' }}">{{ $r['sign'] }}</span></span>
            <span class="tdate">Note: {{ $r['delivery_note'] }}</span>
            <span class="tdate">Kordinat Lokasi: <span class=" {{ ($r['near_origin'])?'red block':'' }}">{{ $r['latitude'],$r['longitude'] }}</span></span>
            <img src="{{ $r['thumb'] }}" style="width:50%;height:auto;" alt="{{ $r['thumb'] }}" />
            @if($r['signpic'] != false)
                <img src="{{ $r['signpic'] }}" style="width:50%;height:auto;" alt="{{ $r['sign'] }}" />
            @endif
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