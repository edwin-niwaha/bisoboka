@include('layouts/layout')
<div class="container">
{!! Form::open(['action'=>['PostsController@update',$posts->id],'method'=>'POST']) !!}
<div class="form-group">
{{Form::label('title','Title')}}
{{Form::text('title',$posts->Title,['class'=>'form-control'])}}
</div>
<div class="form-group">
        {{Form::label('title','Posts')}}
        {{Form::textarea('post',$posts->Posts,['class'=>'form-control'])}}
        </div>
{{Form::submit('Post it',['class'=>'btn btn-primary'])}}
{{Form::hidden('_method','PUT')}}
{{Form::close()}}

</div>