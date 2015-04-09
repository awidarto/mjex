@extends('layout.front')

@section('content')

<div class="row">
      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array','top1');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array','bottom1');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array','bottom2');

        if(isset($_COOKIE['jextrack'])){
          $last_keyword = $_COOKIE['jextrack'];
        }else{
          $last_keyword = '';
        }

      ?>
  <div class="login-form">
    <div class="text-center">
      {{-- $ad_1['html'] --}}
    </div>
    <h3>Order Status</h3>
    <hr />
    <form action="{{ URL::to('track')}}" method="post" accept-charset="utf-8">
      <label for="phone">Phone / Mobile Number / No Invoice Toko:</label>
      <input type="text" name="phone" id="phone" value="{{ $last_keyword }}" />
      @if($errors->has('phone'))
        {{ $errors->first('phone', '<div class="alert"><a href="#" class="close">x</a>:message</div>'); }}
      @endif
      <input type="submit" name="track" id="" value="Track" class="button" />
      <p style="font-size:11px;">Untuk AWB / Nomor Paket, mohon tuliskan persis sesuai yang tertera di invoice / resi</p>
    </form>
  </div>
</div>
<div class="row">
  <div class="text-center">
      {{-- $ad_2['html'].'<br />'.$ad_3['html'] --}}
  </div>

</div>

@stop