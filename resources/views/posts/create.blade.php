@include('layouts/layout')
<div class="container">
{!! Form::open(['action'=>'PostsController@store','method'=>'POST']) !!}
<div class="form-group">
{{Form::label('title','Title')}}
{{Form::text('title','',['class'=>'form-control'])}}
</div>
<div class="form-group">
        {{Form::label('title','Posts')}}
        {{Form::textarea('post','',['class'=>'form-control'])}}
        </div>
{{Form::submit('Post it',['class'=>'btn btn-primary'])}}
{{Form::close()}}

</div>