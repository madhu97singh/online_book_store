<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommonAuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::group(['middleware'=>['beforeLogin']], function (){
    Auth::routes();
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::group(['middleware'=>['beforeLogin']], function (){
    Route::match(['get','post'],'/', [CommonAuthController::class,'Login'])->name('login');
    Route::match(['get','post'],'/login', [CommonAuthController::class,'Login'])->name('login');
});

Route::group(['middleware'=>['afterLogin']], function (){
    Route::match(['get','post'], '/logout', [CommonAuthController::class,'logout'])->name('logout');
    Route::match(['get','post'], '/adminLogout', [CommonAuthController::class,'adminLogout'])->name('adminLogout');
});
Route::group(['middleware'=>['afterLogin','admin']],function () {
    // Route::get('/home', [BookController::class, 'index']);  
    Route::get('/dashboard', [PurchaseController::class, 'dashboard'])->name('dashboard');
    Route::get('/books-list', [PurchaseController::class, 'index'])->name('books-list');
    Route::get('/shopping-cart', [PurchaseController::class, 'bookCart'])->name('shopping.cart');
    Route::get('/book/{id}', [PurchaseController::class, 'addBooktoCart'])->name('addbook.to.cart');
    Route::GET('/edit/{id}/cart', [PurchaseController::class, 'editCart'])->name('edit.sopping.cart');
    Route::patch('/update-shopping-cart', [PurchaseController::class, 'updateCart'])->name('update.sopping.cart');
    Route::delete('/delete-cart-product', [PurchaseController::class, 'deleteProduct'])->name('delete.cart.product');
    Route::post('reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');

    Route::GET('/create', [App\Http\Controllers\BookController::class, 'create'])->name('create');
    Route::POST('/store', [App\Http\Controllers\BookController::class, 'store'])->name('store');
    Route::GET('/book/show/{id}', [App\Http\Controllers\BookController::class, 'show'])->name('book.show');
    Route::GET('/book/{id}/edit', [App\Http\Controllers\BookController::class, 'edit'])->name('book.edit');
    Route::PATCH('/book/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('book.update');
    Route::post('/books/{books}', [App\Http\Controllers\BookController::class, 'status']);
    Route::GET('/book/status/{id}', [App\Http\Controllers\BookController::class, 'status'])->name('book.status');
    Route::delete('/book/delete/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('book.delete');
    Route::post('/books/{books}', [App\Http\Controllers\BookController::class, 'status']);
    Route::GET('/book/status/{id}', [App\Http\Controllers\BookController::class, 'status'])->name('book.status');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});
Route::group(['middleware'=>['afterLogin','user']],function () {  
    Route::get('/dashboard', [PurchaseController::class, 'dashboard'])->name('dashboard');
    Route::get('/books-list', [PurchaseController::class, 'index'])->name('books-list');
    Route::get('/shopping-cart', [PurchaseController::class, 'bookCart'])->name('shopping.cart');
    Route::get('/book/{id}', [PurchaseController::class, 'addBooktoCart'])->name('addbook.to.cart');
    Route::GET('/edit/{id}/cart', [PurchaseController::class, 'editCart'])->name('edit.sopping.cart');
    Route::patch('/update-shopping-cart', [PurchaseController::class, 'updateCart'])->name('update.sopping.cart');
    Route::delete('/delete-cart-product', [PurchaseController::class, 'deleteProduct'])->name('delete.cart.product');
    Route::post('reviews/{id}', [ReviewController::class, 'store'])->name('reviews.store');
});
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
