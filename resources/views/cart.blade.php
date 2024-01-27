@extends('shop')
   
@section('content')
<table id="cart" class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0 @endphp
        @if(session('cart_' . auth()->id()) && is_array(session('cart_' . auth()->id())))
            @foreach(session('cart_' . auth()->id()) as $id => $details)
                 
                <tr rowId="{{ $id }}">
                    <td data-th="Product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"><img src="{{\Illuminate\Support\Facades\Storage::url($details['image'])}}" class="card-img-top" style="width: 50%;max-height:50vh;"/></div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    @if(isset($details['total']))
                    <td data-th="Price">${{ $details['total'] }}</td>
                    @else
                    <td data-th="Price">${{ $details['price'] }}</td>
                    @endif
                    <td>{{ $details['quantity'] }}</td>
                    <td class="actions">
                        <a class="btn btn-outline-danger btn-sm delete-product" data-id="{{ $details['id'] }}"><i class="fa fa-trash-o"></i></a>
                        <?php $cart = \App\Models\Purchase::select('id')->where(['book_id' => $details['id']])->first(); ?>
                        <a href="/edit/{{$cart->id}}/cart" class="btn btn-outline-success btn-sm edit-product"><i class="fa fa-pencil"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right">
                <a href="{{ url('/books-list') }}" class="btn btn-primary"><i class="fa fa-angle-left"></i> Continue Shopping</a>
                <button class="btn btn-danger">Checkout</button>
            </td>
        </tr>
    </tfoot>
</table>
@endsection
   
@section('scripts')
<script type="text/javascript">
   
    $(".edit-cart-info").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('update.sopping.cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("rowId"), 
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });
   
    $(".delete-product").click(function (e) {
        e.preventDefault();
   
        // var ele = $(this);
        var bookId = $(this).attr('data-id');
        if(confirm("Do you really want to delete?")) {
            $.ajax({
                url: '{{ route('delete.cart.product') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    // id: ele.parents("tr").attr("rowId"),
                    bookId:bookId
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
   
</script>
@endsection