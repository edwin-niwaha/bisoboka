@include('layouts/layout')
<div class="container">
    <a href="posts/create" class="btn btn-primary">Create Post</a>
<div class="well">
@foreach($posts as $post)
<h2><a href="posts/{{$post->id}}">{{$post->Title}}</a></h2>
<small>{{$post->created_at}}</small>

@endforeach
</div>

</div>

    </body>
    </html>