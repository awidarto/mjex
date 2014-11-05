@extends('layout.front')

@section('content')

<div class="row">
  <div class="login-form">
    <h3>Sign in</h3>
    {{--
    <hr />
    <ul class="button-group">
      <li><a href="#" class="button primary"><i class="fa fa-twitter"></i> Twitter</a></li>
      <li><a href="#" class="button"><i class="fa fa-github"></i> Github</a></li>
      <li><a href="#" class="button danger"><i class="fa fa-google-plus"></i> Google</a></li>
    </ul>
    <br />
    --}}
    <form action="{{ URL::to('login')}}" method="post" accept-charset="utf-8">
      <label for="phone">Phone / Mobile Number / No Invoice Toko:</label>
      <input type="text" name="" id="phone" value="" />
      <label for="password">Password:</label>
      <input type="password" name="" id="password" value="" />
      <input type="submit" name="" id="" value="Sign In" class="button" />
    </form>
    <p>Don't have a password ? <a href="{{ URL::to('signup')}}">Sign Up</a> here.</p>
  </div>
</div>

@stop