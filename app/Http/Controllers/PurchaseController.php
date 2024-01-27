<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Purchase;
use Auth;
use Session;
class PurchaseController extends Controller
{
    public function index()
    {
        $books = Book::where(['status' => 'active'])->get();
        return view('products', compact('books'));
    }

    public function dashboard()
    {
        $user = auth()->user();
        $userPurchases = Purchase::where('user_id', $user->id)->pluck('book_id');
        $similarUsers = Purchase::whereIn('book_id', $userPurchases)
            ->where('user_id', '<>', $user->id)
            ->pluck('user_id')
            ->unique();

        $recommendedBooks = Purchase::whereIn('user_id', $similarUsers)
            ->whereNotIn('book_id', $userPurchases)
            ->groupBy('book_id')
            ->orderByRaw('count(*) desc')
            ->pluck('book_id');
        $recommendedBooks = Book::whereIn('id', $recommendedBooks)->get();

        return view('dashboard', compact('recommendedBooks'));
    }

    public function bookCart()
    {   
        // session()->flush();
        return view('cart');
    }
    public function addBooktoCart($id)
    {
        $book = Book::findOrFail($id);
        $cartKey = 'cart_' . auth()->id();
        $cart = session()->get($cartKey, []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
            $cart[$id]['total'] = $cart[$id]['quantity'] * $book->price;
        } else {
            $cart[$id] = [
                "id" => $book->id,
                "name" => $book->name,
                "quantity" => 1,
                "price" => $book->price,
                "image" => $book->image,
                "total" => $book->price,
            ];
        }
        $user = auth()->user();
        $book_purchase = Purchase::where(['book_id' => $id, 'user_id' => auth()->id()])
                        ->where('user_id', $user->id)->first();

        if ($book_purchase) {
            $book_purchase->increment('quantity');
        } else {
            $book_purchase = new Purchase();
            $book_purchase->user_id = $user->id;
            $book_purchase->book_id = $id;
            $book_purchase->price = $book->price;
            $book_purchase->total = $book->price;
            $book_purchase->quantity = 1;
            $book_purchase->save();
        }
        
        session()->put($cartKey, $cart);
        return redirect()->back()->with('success', 'Book has been added to cart!');
    }

    public function editCart($id){
        // $cart = session()->get('cart', []);
        // $item = $cart[$id] ?? null;
        // if (!$item) {
        //     abort(404);
        // }
        // return view('editCart', compact('item'));
        $cart = Purchase::find($id);
        return view('editCart',compact('cart'));
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cartKey = 'cart_' . auth()->id();
            $cart = session()->get($cartKey);

            if (isset($cart[$request->book_id])) {
                $cart[$request->book_id]["quantity"] = $request->quantity;
                $cart[$request->book_id]["total"] = $request->quantity * $cart[$request->book_id]["price"];
            
                $purchase = Purchase::find($request->id);
                if ($purchase) {
                    $purchase->quantity = $request->quantity;
                    $purchase->price = $cart[$request->book_id]['price'];
                    $purchase->total = $cart[$request->book_id]['price'] * $request->quantity;
                    $purchase->save();
                } else {
                    session()->flash('error', 'Item not found in the cart.');
                }

                session()->put($cartKey, $cart);
                session()->flash('success', 'Cart updated successfully.');
            } else {
                session()->flash('error', 'Item not found in the cart.');
            }
        }

        return redirect()->route('shopping.cart');
    }
   
   public function deleteProduct(Request $request)
    {
        if ($request->bookId) {
            $user = auth()->user();
            $cartKey = 'cart_' . $user->id;
            $cart = session()->get($cartKey);

            if (isset($cart[$request->bookId])) {
                $purchase = Purchase::where(['book_id' => $request->bookId, 'user_id' => $user->id])->first();

                if ($purchase) {
                    $purchase->delete();
                }

                unset($cart[$request->bookId]);
                session()->put($cartKey, $cart);

                session()->flash('success', 'Book successfully deleted.');
            } else {
                session()->flash('error', 'Book not found in the cart.');
            }
        } else {
            session()->flash('error', 'Invalid book ID.');
        }
        return response()->json(['message' => 'Item deleted successfully']);
    }


    public function logout() {
        Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
