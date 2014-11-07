@extends('layout.front')

@section('content')

<div class="row">
  <div class="login-form">
    <h3>Daily Delivery Summary</h3>
    <hr />
    <form action="{{ URL::to('c/track')}}" method="post" accept-charset="utf-8">
      <label for="device">Device Name / Courier Name:</label>
      <input type="text" name="device" id="device" value="" />
      <input type="submit" name="track" id="" value="View" class="button" />
    </form>
  </div>
</div>

@stop