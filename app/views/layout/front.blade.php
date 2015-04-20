<!DOCTYPE html>
<html lang="en">
  <head>
    <!--includes meta tags, title and more header definitions-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
    {{ HTML::style('fonts/OpenSans.css') }}

    <title>{{ Config::get('site.name')}}</title>

    <!-- Furatto core CSS -->
    {{ HTML::style('css/normalize.css') }}
    {{ HTML::style('css/furatto.min.css') }}
    {{ HTML::style('css/examples.css') }}
    {{ HTML::style('css/style.css') }}

    {{ HTML::script('js/jquery-1.11.0.min.js')}}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

<body>
    <nav class="navigation-bar ">
       <ul class="brand-section">
         <li class="brand-name">
          <a href="#" class="menu-trigger" id="trigger">{{ Config::get('site.name')}}</a>
         </li>
         <li class="menu-toggle">
          <a href="#"></a>
         </li>
       </ul>
       <ul class="pull-right">
         <li class="divider"></li>
         <li><a href="{{ URL::to('shops') }}">Shops</a></li>
         <li class="divider"></li>
         <li><a href="{{ URL::to('track') }}">Track Order</a></li>
         {{--
         <li class="divider"></li>
         <li><a href="#" class="button danger three-d">Sign Up</a></li>
         --}}
       </ul>
    </nav>

    @yield('content')


<!--includes javascript at the bottom so the page loads faster-->
{{ HTML::script('js/furatto.min.js')}}

</body>
</html>