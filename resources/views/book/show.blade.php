
@extends('layouts.app')
@section('content')
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="mt-5">{{$book->title}}</h1><hr>
        <p>Author : {{$book->author}}</p><hr>
        <img class="img-fluid rounded" src="{{\Illuminate\Support\Facades\Storage::url($book->image)}}" alt="" style="width: 30%;max-height:30vh;" width="200" height="200"><hr>
        <p class="lead">Price : {{$book->price}}</p>
        <p class="lead">Available Quantity : {{$book->quantity_available}}</p>
        <hr>
    <div>
    </div>
</div>
@endsection