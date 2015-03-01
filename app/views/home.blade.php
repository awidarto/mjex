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
    <div class="text-justify text-center-sm  text-center-md  text-center-lg">
      {{ Jayonad::ad('random') }}
    </div>
  </div>

@stop