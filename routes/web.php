<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return view('welcome');
})->name("index");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix("publishers")->group(function () {
    Route::get("/", "PublisherController@index")->name("publishers.index");
    Route::post("/", "PublisherController@store")->name("publishers.store");
    Route::get("/{publisher}", "PublisherController@show")->name("publishers.show");
    Route::post("/update/{publisher}", "PublisherController@update")->name("publishers.update");
    Route::post("/delete/{publisher}", "PublisherController@delete")->name("publishers.delete");
});

Route::prefix("subjects")->group(function () {
    Route::get("/", "SubjectController@index")->name("subjects.index");
    Route::post("/", "SubjectController@store")->name("subjects.store");
    Route::get("/{subject}", "SubjectController@show")->name("subjects.show");
    Route::post("/update/{subject}", "SubjectController@update")->name("subjects.update");
    Route::post("/delete/{subject}", "SubjectController@delete")->name("subjects.delete");
});

Route::prefix("authors")->group(function () {
    Route::get("/", "AuthorController@index")->name("authors.index");
    Route::post("/", "AuthorController@store")->name("authors.store");
    Route::get("/{author}", "AuthorController@show")->name("authors.show");
    Route::post("/update/{author}", "AuthorController@update")->name("authors.update");
    Route::post("/delete/{author}", "AuthorController@delete")->name("authors.delete");
});

Route::prefix("books")->group(function () {
    Route::get("/", "BookController@index")->name("books.index");
    Route::post("/", "BookController@store")->name("books.store");
    Route::get("/{book}", "BookController@show")->name("books.show");
    Route::post("/update/{book}", "BookController@update")->name("books.update");
    Route::post("/delete/{book}", "BookController@delete")->name("books.delete");
});

Route::prefix("borrows")->middleware('auth')->group(function () {
    Route::get("/", "BorrowController@index")->name("borrows.index");
    Route::post("/", "BorrowController@store")->name("borrows.store");
    Route::get("/{borrow}", "BorrowController@show")->name("borrows.show");
    Route::post("/update/{borrow}", "BorrowController@update")->name("borrows.update");
    Route::post("/delete/{borrow}", "BorrowController@delete")->name("borrows.delete");
    Route::post("/update/delivered/{borrow}", "BorrowController@delivering")
        ->name("borrows.deliver");
    Route::post("/update/returned/{borrow}", "BorrowController@returning")
        ->name("borrows.return");
});

Route::prefix("posts")->group(function () {
    Route::get("/", "PostController@index")->name("posts.index");
    Route::post("/", "PostController@store")->name("posts.store")->middleware('auth');
    Route::get("/{post}", "PostController@show")->name("posts.show");
    Route::post("/update/{post}", "PostController@update")->name("posts.update")->middleware('auth');
    Route::post("/delete/{post}", "PostController@delete")->name("posts.delete")->middleware('auth');
});

Route::prefix("messages")->middleware("auth")->group(function () {
    Route::get("/", "MessageController@index")->name("messages.index");
    Route::post("/", "MessageController@send")->name("messages.send");
    Route::post("/delete/{message}", "MessageController@delete")->name("posts.delete");
});