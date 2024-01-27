@extends('shop')
   
@section('content')
    <div class="container">
        <?php $user = auth()->user(); 
            if ($user->status == 1) {
                $status = "Active";
            }else{
                $status = "Inactive";
            }
        ?>
        <h1>User Profile</h1>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Status:</strong> {{ $status }}</p>
        <p><strong>Role:</strong> {{ $user->roles->name }}</p>

        <h2>Recommended Books</h2>
        @forelse($recommendedBooks as $book)
            <div>
                <h3>{{ $book->title }}</h3>
            </div>
        @empty
            <p>No recommended books at the moment.</p>
        @endforelse

    </div>
@endsection
