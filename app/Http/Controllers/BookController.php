<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Auth;
use Session;
use Illuminate\Support\Facades\Storage;
class BookController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $books = Book::all();
        $books = Book::with(['purchases','reviews'])->get();
        return view('book.create',compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'author' => 'required',
            'genre' => 'required',
            'price' => 'numeric|required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity_available' => 'numeric|required',
            'image' => 'mimes:jpeg,jpg,png,gif|required',
        ]);
        if ($request->hasFile('image')){
            $image = Storage::putFile('public/',$request->image);
        }
        $book = new Book();
        $book->title=$request->title;
        $book->author=$request->author;
        $book->genre=$request->genre;
        $book->price=$request->price;
        $book->quantity_available=$request->quantity_available;
        $book->image=$request->file('image')->store('public');
        $book->save();
        return redirect('home')->with('success','Book created succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        return view('book.show',compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $book = Book::find($id);
        return view('book.edit',compact('book'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title' => 'required|min:3',
            'author' => 'required',
            'genre' => 'required',
            'price' => 'numeric|required|regex:/^\d+(\.\d{1,2})?$/',
            'quantity_available' => 'numeric|required',
            'image' => 'mimes:jpeg,jpg,png,gif|required',
        ]);
        // update product
        $book = Book::find($id);

        $book->title=$request->title;
        $book->author=$request->author;
        $book->genre=$request->genre;
        $book->price=$request->price;
        $book->quantity_available=$request->quantity_available;
        $book->image=$request->file('image')->store('public');
        $book->update();
        return redirect('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $books =Book::find($id);
        $books->delete();
        return redirect('home');
    }

    public function status($bookId)
    {
        $book = Book::find($bookId);
        if ($book->status == 'active') {
            $book->status = 'inactive';
            $book->save();
        }else{
            $book->status = 'active';
            $book->save();
        }
        return response()->json(['message' => 'Book status updated successfully']);
    }
}
