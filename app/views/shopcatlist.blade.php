@extends('layout.front')

@section('content')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#category-filter').on('change',function(){
                $('#search-form').submit();
            });
        });
    </script>

      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array', 'top1');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array', 'bottom1');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array', 'bottom2');
    /*
    <div class="track-list-item">
        <div class="text-center">
          {{ $ad_1['html'] }}
        </div>
    </div>
    */
      ?>
    <div class="track-list-item">
        <p>Klik untuk melihat list toko
        </p>
    </div>

@foreach($shops as $r)

    <div class="track-list-item">
        <a href="{{ URL::to('shops/'.$r['slug'] ) }}">
            <span class="tmerchant">{{ $r['name']}}</span>
            <span class="tid">{{ $r['description'] }}</span>
        </a>
    </div>

@endforeach

    <div class="track-list-item" style="display:block;height:25px;">

    </div>

@stop