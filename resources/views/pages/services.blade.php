@extends('layout/layout')
denis is going a great work 
<div class="container">
<ol class="list-group">
    @foreach($services as $data1)
<li class="list-group-item">{{$data1}}</li>

        @endforeach
</div>
   