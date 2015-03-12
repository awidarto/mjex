@extends('layout.front')

@section('content')

<div class="row">
  <div class="login-form">
    <h3>Order Status</h3>
    <hr />
    <form action="{{ URL::to('track')}}" method="post" accept-charset="utf-8">
      <label for="phone">Phone / Mobile Number / No Invoice Toko:</label>
      <input type="text" name="phone" id="phone" value="" />
      <input type="submit" name="track" id="" value="Track" class="button" />
      <p style="font-size:11px;">Untuk AWB / Nomor Paket, mohon tuliskan persis sesuai yang tertera di invoice / resi</p>
    </form>
  </div>
</div>
<div class="row">
  <div class="text-center">
    {{ Jayonad::ad('random') }}
  </div>
</div>

@stop