@extends('layouts.app')

@section('content')

    <div class="container">
        <table class="table" border="1">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Total Purchases</th>
                    <th>Total Orders</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Price</th>
                    <th>Quantity Available</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                <tr>
                    <td>{{$book->id}}</td>
                    <!-- <td>{{$book->image}}</td> -->
                    <td>
                        <img class="img-fluid rounded" width="696" height="522" src="{{\Illuminate\Support\Facades\Storage::url($book->image)}}" style="width: 30%;max-height:30vh; " alt="">
                    </td>
                   <td>{{$book->title}}</td>
                   <td>{{ $book->total_purchases }}</td>
                   <td>{{ $book->total_orders }}</td>
                    <td>{{$book->author}}</td>
                    <td>{{$book->genre}}</td>
                    <td>{{$book->price}}</td>
                    <td>{{$book->quantity_available}}</td>
                    <td>
                        @if ($book->status === 'active')
                        <button class="btn btn-sm btn-success toggle-status" data-user="{{ $book->id }}">
                            Click to Inactivate
                        </button>
                        @else
                        <button class="btn btn-sm btn-danger toggle-status" data-user="{{ $book->id }}">
                            Click to Activate
                        </button>
                        @endif
                    <td>
                   
                        <a href="{{route('book.show',$book->id)}}" class="btn btn-info"><span class="fa fa-eye">View</span></a>
                        <a href="/book/{{$book->id}}/edit" class="btn btn-success"><span class="fa fa-pencil">Edit</span></a>
                        <form action="{{route('book.delete',$book->id)}}" enctype="multipart/form-data" method="POST">
                          @method('DELETE')
                          @csrf
                          <input type="submit" name="" class="btn btn-danger" style="color: #fff" onclick="return confirm('Are you sure you want to delete this book');" value="delete">
                        </form>
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<script type="text/javascript">
    $('.toggle-status').click(function() {
        var bookId = $(this).attr('data-user');
        $.ajax({
            type: 'POST',
            url: '/books/' + bookId ,
            data: {
                _token: '{{ csrf_token() }}',
                bookId : bookId
            },
            success: function(response) {
                location.reload();
            },
            error: function(error) {
                console.error(error);
                alert('Error toggling user status.');
            }
        });
    });
</script>
@endsection
