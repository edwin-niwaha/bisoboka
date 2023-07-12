@include('layouts/layout')
<h3>Edit</h3>
<div class="container">
<a href="/posts/{{$posts->id}}/edit" class="btn btn-primary" >Edit</a>
<div class="well">
   
<h2>{{$posts->Title}}</h2>
<small>{{$posts->Posts}}<small>
<p>{{$posts->created_at}}</p>
</div>

</div>