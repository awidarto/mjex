@extends('layout.front')

@section('content')
<style type="text/css">
  .arrow {
    font-size: 48px;
  }
</style>
<div class="row">
  <div class="text-center">
    <?php
      $ad1 = Jayonad::ad($order['merchant_id'], null, 'redir', 'array','top1' );
      $ad2 = Jayonad::ad($order['merchant_id'], null, 'redir', 'array','top1' );
    ?>
    {{ $ad1['html'] }}
  </div>
</div>
<div class="row">
  <div class="login-form">
    <h3>Order Status</h3>
    <hr />
    <p>
      No Kode Toko :<br />
      {{ short_id($order['merchant_trans_id']) }}
      <br />
      Jayon Express ID :<br />
      {{ $order['delivery_id'] }}
      <br />
      Delivered to :<br />
      {{ $order['recipient_name'] }}<br />
      {{ $order['shipping_address'] }}
    </p>
    <p>
      Delivery Type :<br />
      <b>{{ $order['delivery_type'] }}</b><br />

<?php
      $gt = 0;

      $details = Orderitem::where('delivery_id',$order['delivery_id'])->orderBy('unit_sequence','asc')->get();

      $details = $details->toArray();

      $d = 0;
      $gt = 0;

      foreach($details as $value => $key)
      {

        //$u_total = str_replace(array(',','.'), '', $key['unit_total']);
        //$u_discount = str_replace(array(',','.'), '', $key['unit_discount']);
        $u_total =  $key['unit_total'];
        $u_discount =  $key['unit_discount'];
        $gt += (is_nan((double)$u_total))?0:(double)$u_total;
        $d += (is_nan((double)$u_discount))?0:(double)$u_discount;

      }

      $total = $order['total_price'];
      $total = (is_nan((double)$total))?0:(double)$total;

      $dsc = $order['total_discount'];
      $tax = $order['total_tax'];
      $dc = $order['delivery_cost'];
      $cod = $order['cod_cost'];

      $dsc = (is_nan((double)$dsc))?0:(double)$dsc;
      $tax = (is_nan((double)$tax))?0:(double)$tax;
      $dc = (is_nan((double)$dc))?0:(double)$dc;
      $cod = (is_nan((double)$cod))?0:(double)$cod;

      if($gt == 0){
          $gt = $total;
      }

      if($order['delivery_bearer'] == 'merchant'){
          $dc = 0;
      }


      if($order['cod_bearer'] == 'merchant'){
          $cod = 0;
      }

      if($order['delivery_type'] == 'COD' || $order['delivery_type'] == 'CCOD'){
          $chg = ($gt - $dsc) + $tax + $dc + $cod;
      }else{
          $cod = 0;
          $chg = $dc;
      }

      if($order['delivery_type'] == 'COD' || $order['delivery_type'] == 'CCOD'){
          $cclass = ' bigtype';
      }else{
          $cclass = '';
      }

?>


      @if($order['delivery_type'] == 'COD' || $order['delivery_type'] == 'CCOD')
        Total Charge :<br />
        <b>IDR {{ Helpers::idr( $chg )}}</b>
      @endif
    </p>
    <p>
      Delivery Schedule :<br />
      {{ $order['assignment_date']}}<br />
      Delivery Status :<br />
      {{ $order['status'] }}<br />
      {{ $order['deliverytime'] }}
    </p>
    <p>
      Package Status :<br />
      {{ $order['pickup_status'] }} dari toko online
    </p>
    <p>
      Recipient & Note :<br />
      {{ $order['delivery_note'] }}<br />
      @if( $order['status'] == 'delivered' || ($order['status'] == 'pending' && $order['pending_count'] > 0) )
        <?php

          $pics = Helpers::get_multifullpic($order['delivery_id']);

        ?>
        @if(count($pics) <= 1)
          <img class="responsive" src="{{ Helpers::get_fullpic($order['delivery_id']) }}" alt="{{ $order['delivery_id'] }}">
        @elseif(count($pics) > 1)
        <div class="suraido-container" data-suraido>
          <ul>
          @foreach($pics as $pic)
              <li style="background:url({{ $pic }})">
                <div class="caption">
                  <p>{{ str_replace(URL::to('/').'/storage/receiver_pic/', '', $pic) }}</p>
                </div>
              </li>
          @endforeach
          </ul>
        </div>
        @endif
        <br />
      <br />
      Signature :<br />
      <img class="responsive" src="{{ Helpers::get_signpic($order['delivery_id']) }}" alt="{{ $order['delivery_id'] }}"><br />

      @endif
      @if( $order['latitude'] != '' && $order['longitude'] )
      <?php
        $point = $order['latitude'].','.$order['longitude']
      ?>
      <br />
      <img class="responsive" src="https://maps.googleapis.com/maps/api/staticmap?center={{$point}}&zoom=13&size=600x300&maptype=roadmap&markers=color:green%7C{{$point}}&key={{ Config::get('ks.static_map_key')}}" alt="{{ $order['delivery_id'] }}"><br />
      <p class="disclaimer">
        <strong>Disclaimer :</strong><br />
        Location accuracy within 500 meters radius, depending on device GPS, telecom provider network, and map provider data accuracy.
      </p>
      @endif

    </p>

  </div>
</div>

<div class="row">
  <div class="text-center">
    {{ $ad2['html'] }}
  </div>
</div>

{{--
array (size=74)
  'id' => int 2135
  'created' => string '2012-10-15 19:59:47' (length=19)
  'ordertime' => string '2012-10-15 12:59:47' (length=19)
  'buyerdeliverytime' => string '2012-10-16 00:00:00' (length=19)
  'buyerdeliveryslot' => int 1
  'buyerdeliveryzone' => string 'Tebet' (length=5)
  'buyerdeliverycity' => string 'Jakarta Selatan' (length=15)
  'assigntime' => string '2012-10-15 15:03:06' (length=19)
  'deliverytime' => string '2012-10-16 12:16:43' (length=19)
  'assignment_date' => string '2012-10-16' (length=10)
  'assignment_timeslot' => int 1
  'assignment_zone' => string '0' (length=1)
  'assignment_city' => string 'Jakarta Selatan' (length=15)
  'assignment_seq' => int 0
  'delivery_id' => string '000298-15-102012-00002135' (length=25)
  'delivery_cost' => string '6000' (length=4)
  'cod_cost' => string '0' (length=1)
  'width' => int 11
  'height' => int 1
  'length' => int 9
  'weight' => float 6000
  'actual_weight' => null
  'delivery_type' => string 'Delivery Only' (length=13)
  'currency' => string 'IDR' (length=3)
  'total_price' => string '73800' (length=5)
  'fixed_discount' => int 1
  'total_discount' => string '0' (length=1)
  'total_tax' => string '' (length=0)
  'chargeable_amount' => string '79800' (length=5)
  'delivery_bearer' => string '' (length=0)
  'cod_bearer' => string '' (length=0)
  'cod_method' => null
  'ccod_method' => null
  'application_id' => int 25
  'application_key' => string 'afa18ec7e1a3bab9a61ee54d56f039d4849f77b4' (length=40)
  'buyer_id' => int 1
  'merchant_id' => int 298
  'merchant_trans_id' => string 'TRX_298_0812148001350280787' (length=27)
  'courier_id' => int 0
  'device_id' => int 20
  'pickup_dev_id' => string '' (length=0)
  'pickup_person' => string '' (length=0)
  'buyer_name' => string 'Afsdyah Eky Vitalina (61040)' (length=28)
  'email' => string 'andrew@bukukita.com' (length=19)
  'recipient_name' => string 'Afsdyah Eky Vitalina (61040)' (length=28)
  'shipping_address' => string ' PT. PPA Consultants
Jalan Tebet Timur Raya No. 57' (length=50)
  'shipping_zip' => string '12820' (length=5)
  'directions' => string 'Tebet' (length=5)
  'dir_lat' => null
  'dir_lon' => null
  'phone' => string '0218305857 # 08151895587' (length=24)
  'mobile1' => null
  'mobile2' => null
  'status' => string 'delivered' (length=9)
  'laststatus' => null
  'change_actor' => null
  'actor_history' => null
  'delivery_note' => string 'govar' (length=5)
  'reciever_name' => null
  'reciever_picture' => string 'nopic' (length=5)
  'pic_address' => null
  'pic_1' => null
  'pic_2' => null
  'pic_3' => null
  'undersign' => null
  'latitude' => string '-6.242530900000' (length=15)
  'longitude' => string '106.854437400000' (length=16)
  'reschedule_ref' => null
  'revoke_ref' => null
  'reattemp' => int 0
  'show_merchant' => int 1
  'show_shop' => int 1
  'is_pickup' => int 0
  'is_import' => int 0

--}}

@stop