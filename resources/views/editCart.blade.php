@extends('shop')
   
@section('content')
    <form action="{{ route('update.sopping.cart') }}" method="post">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $cart['id'] }}">
        <input type="hidden" name="book_id" value="{{ $cart['book_id'] }}">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="{{ $cart['quantity'] }}" min="1">
        <button type="submit">Update Cart</button>
    </form>
@endsection