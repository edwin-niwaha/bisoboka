@include('layouts/layout')
<div class="container">


@foreach($stock as $stocks)
<div class="well">
<h3><a href="stock/{{$stocks->id}}">{{$stocks->stockname}}</a></h3>
<small>{{$stocks->stockcatogery}}
    </div>
@endforeach




</div>

</div>
