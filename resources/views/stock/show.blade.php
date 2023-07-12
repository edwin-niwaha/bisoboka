@include('layouts/layout')
<div class="container">
<div class="well">
<h3>{{$stock->stockname}}</h3>
<small>{{$stock->created_at}}</small>
</div>
<a href="{{$stock->id}}/edit" class="btn btn-primary">Edit</a>
{{Form::open(['action'=>['StocksController@destroy',$stock->id],'method'=>'POST'])}}


{{Form::submit('Delete',['class'=>'btn btn-danger pull right'])}}
{{Form::hidden('_method','DELETE')}}
{{Form::close()}}
</div>