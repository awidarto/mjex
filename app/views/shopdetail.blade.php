@extends('layout.front')

@section('content')

<div class="row">
  <div class="login-form">
    <div class="text-center">
      {{-- print_r($shop) --}}
    </div>

    @if($shop)

    <h3>{{ $shop->merchantname }}</h3>
      <table>
         <tbody>
           <tr>
             <td>{{ $shop->shopcategory }}</td>
           </tr>
           <tr>
             <td>{{$shop->mc_phone}}</td>
           </tr>
           <tr>
             <td>{{$shop->mc_street}}<br /> {{ $shop->mc_district}}<br /> {{ $shop->mc_city}} {{ $shop->mc_zip}} </td>
           </tr>
           <tr>
             <td>{{-- $shop->url --}}</td>
           </tr>
         </tbody>
      </table>
    <div class="description">
        @if( isset($shop->description) && $shop->description != '' )
        {{ $shop->description }}
        @else
          <p>
            Halvah candy cupcake. I love bonbon tiramisu cake oat cake I love cupcake tootsie roll caramels. Caramels caramels tart pudding. Chupa chups candy canes muffin sweet liquorice. I love gummi bears powder toffee sweet danish sweet. Jelly beans macaroon donut oat cake gummies ice cream.
          </p>
          <p>
            Croissant biscuit tiramisu chocolate jelly beans gummies.
            I love cake donut. Tiramisu lemon drops chocolate bar biscuit sesame snaps. Marzipan lollipop danish topping gummi bears marzipan jelly beans. Cupcake icing wafer sugar plum jelly beans gummies wafer. Tart chocolate cake marzipan sesame snaps pastry I love I love. I love tootsie roll chocolate cake carrot cake lemon drops.
          </p>
          <p>
            Caramels soufflé biscuit. Muffin tiramisu I love I love liquorice. Jujubes icing jelly jelly. Topping cake cotton candy cupcake. Sugar plum oat cake macaroon marzipan danish I love liquorice donut I love. Cupcake candy canes biscuit marzipan.
          </p>
        @endif
    </div>

    @else
      <p>No detail found for this shop</p>>
    @endif
  </div>
</div>
<div class="row">
  <div class="text-center">
      {{-- $ad_2['html'].'<br />'.$ad_3['html'] --}}
  </div>

</div>

@stop