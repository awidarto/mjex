@extends('layout.front')

@section('content')

<div class="row">
    <div class="suraido-container" data-suraido>
        <ul>
          <li style="background:url(/assets/img/1.jpg)">
          <div class="caption">
            <p>Posasda Villa de la Aldea, San Miguel de Allende, México.</p>
          </div>
          </li>
          <li style="background:url(/assets/img/2.jpg)">
          <div class="caption">
            <p>San Miguel de Allende Nightlife</p>
          </div>
          </li>
          <li style="background:url(/assets/img/3.jpg)">
          <div class="caption">
            <p>La Plaza Principal, San Miguel de Allende.</p>
          </div>
          </li>
          <li style="background:url(/assets/img/4.jpg)">
          <div class="caption">
            <p>San Miguel de Allende Cathedral.</p>
          </div>
          </li>
          <li style="background:url(/assets/img/5.jpg)">
          <div class="caption">
            <p>Rosewood Hotel, San Miguel de Allende.</p>
          </div>
          </li>
        </ul>
    </div>
</div>
  <div class="row">
    <div class="text-center">
      <?php
        $ad_1 = Jayonad::ad('random',null,'redir','array','top1');
        $ad_2 = Jayonad::ad('random',$ad_1['id'],'redir','array','bottom1');
        $ad_3 = Jayonad::ad('random',$ad_2['id'],'redir','array','bottom2');
      ?>
      {{ $ad_1['html'].'<br />'.$ad_2['html'].'<br />'.$ad_3['html'] }}
    </div>

    <div class="suraido-container" data-suraido>
        <ul>
            <li>
              {{ $ad_1['html'] }}
            </li>
            <li>
              {{ $ad_2['html'] }}
            </li>
            <li>
              {{ $ad_3['html'] }}
            </li>
        </ul>
    </div>

  </div>

@stop