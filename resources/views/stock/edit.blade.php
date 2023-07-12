@include('layouts/layout')
<div class="container">
{!!Form::open(['action'=>['StocksController@update',$stocks->id],'method'=>'Post'])!!}
<div class="form-group">
{{Form::label('title','StockName')}}
{{Form::text('stockname',$stocks->stockname,['class'=>'form-control'])}}
</div>
<div class="form-group">
        {{Form::label('title','StockCatogory')}}
       {{Form::select('stockcategory',['animals'=>'Animal','plants'=>'Plants'],['class'=>'form-control'])}}
        </div>
  {{Form::submit('Save',['class'=>'btn btn-primary'])}}   
  {{Form::hidden('_method','PUT')}}  

{{Form::close()}}

</div>