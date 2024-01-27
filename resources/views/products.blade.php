@extends('shop')
    
@section('content')
     
<div class="row">
    @foreach($books as $book)
        <div class="col-md-3 col-6 mb-4">
            <div class="card">
                <img src="{{\Illuminate\Support\Facades\Storage::url($book->image)}}" class="card-img-top" style="width: 50%;max-height:50vh;" class="card-img-top"/>
                <div class="card-body">
                    <h4 class="card-title">{{ $book->name }}</h4>
                    <p>{{ $book->author }}</p>
                    <p class="card-text"><strong>Price: </strong> ${{ $book->price }}</p>
                    <p class="btn-holder"><a href="{{ route('addbook.to.cart', $book->id) }}" class="btn btn-outline-danger">Add to cart</a> </p>
                    <form id="reviewForm" action="{{ route('reviews.store', $book->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment:</label>
                            <textarea name="comment" id="comment" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                    <!-- <div id="reviews"> -->
                        @if ($book->reviews)
                        @foreach ($book->reviews as $review)
                            <div class="mb-3">
                                <strong>{{ $review->user->name }}:</strong>
                                <p>Rating: {{ $review->rating }}</p>
                                <p>{{ $review->comment }}</p>
                            </div>
                        @endforeach
                        @else
                            <p>No reviews available for this book.</p>
                        @endif
                    <!-- </div> -->

                </div>
            </div>
        </div>
    @endforeach
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#reviewForm').submit(function (e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#reviews').html(response);
                        $('#reviewForm')[0].reset();
                        alert('Review added successfully.');
                    },
                    error: function (error) {
                        console.log(error);
                        alert('Error adding review.');
                    }
                });
            });
        });
    </script>
@endsection