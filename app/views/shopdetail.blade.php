@extends('layout.front')

@section('content')

<div class="row">
  <div class="login-form">
    <p>
      <a href="{{ URL::to('shops/'.$category)}}">&laquo; Back to Shops</a>
    </p>

    <div class="text-center">
      {{-- print_r($shop) --}}
    </div>

    @if($shop)

    <h3>{{ $shop->merchantname }}</h3>
      <table>
         <tbody>
           <tr>
              <td>Category</td>
             <td>{{ $shop->shopcategory }}</td>
           </tr>
           <tr>
              <td>Phone</td>
             <td>{{$shop->mc_phone}}</td>
           </tr>
           <tr>
              <td>Address</td>
             <td>{{$shop->mc_street}}<br /> {{ $shop->mc_district}}<br /> {{ $shop->mc_city}} {{ $shop->mc_zip}} </td>
           </tr>
           <tr>
              <td>Website</td>
             <td>
                @if( isset($shop->url) && $shop->url != '' )
                  {{ $shop->url }}
                @endif
            </td>
           </tr>
         </tbody>
      </table>
    <div class="description">
        @if( isset($shop->shopDescription) && $shop->shopDescription != '' )
        {{ $shop->shopDescription }}
        @else
          <p>
              Description coming soon
          </p>
        @endif
    </div>

    @else
        <h3> No Name Shop </h3>
        <table>
           <tbody>
             <tr>
              <td>Category</td>
               <td> - </td>
             </tr>
             <tr>
              <td>Phone</td>
               <td> - </td>
             </tr>
             <tr>
              <td>Address</td>
               <td> - </td>
             </tr>
             <tr>
              <td>Website</td>
               <td> - </td>
             </tr>
           </tbody>
        </table>
        <p>
            Description coming soon
        </p>
    @endif
  </div>
</div>
<div class="row">
  <div class="text-center">
      {{-- $ad_2['html'].'<br />'.$ad_3['html'] --}}
  </div>

</div>

@stop