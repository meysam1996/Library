<?php

namespace App\Http\Controllers;

use App\Book;
use App\File;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\StorePostRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Index simple get json list of Books
     * @return Json
     *
     */
    public function index()
    {
        $books = Book::with(['publisher', 'subject', 'authors'])->get();
        return response()->json($books);
    }

    /**
     * Create new object of Book
     * @param StoreBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookRequest $request)
    {
        $book = new Book();
        $book->name = $request->input("name");
        $book->summary = $request->input("summary");
        $book->description = $request->input("description");
        $book->printer_key = $request->input("printer_key");
        $book->serial_number = $request->input("serial_number");
        $book->publisher_id = $request->input("publisher_id");
        $book->subject_id = $request->input("subject_id");

        # store in database
        $book->save();

        # Upload File if file is coming
        if ($request->file("file_upload")) {
            # Upload File
            $dateTime = Carbon::now();
            $image = $request->file("file_upload");
            $file_name = "{$book->serial_number}-{$dateTime}";
            $file_ext = $image->extension();
            $file_path = "public/books";
            $file_fullName = "{$file_name}.{$file_ext}";
            $image->storeAs($file_path, $file_fullName);

            # Create and store File model to database
            $file = new File();
            $file->name = $file_name;
            $file->ext = $file_ext;
            $file->path = $file_path;
            $file->owner_id = $book->id;
            $file->owner_type = Book::class;
            $file->save();
        }

        # ManyToMany attach
        $book->authors()->attach($request->input("authors_id"));

        return response()->json($book);
    }

    /**
     * Get a Book
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Edit a Book
     * @param StoreBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StorePostRequest $request, Book $book)
    {
        $book->name = $request->input("name");
        $book->summary = $request->input("summary");
        $book->description = $request->input("description");
        $book->printer_key = $request->input("printer_key");
        $book->publisher_id = $request->input("publisher_id");
        $book->subject_id = $request->input("subject_id");

        # store in database
        $book->save();

        # Upload File if file is coming
        if ($request->file("file_upload")) {
            # Upload File
            $dateTime = Carbon::now();
            $image = $request->file("file_upload");
            $file_name = "{$book->serial_number}-{$dateTime}";
            $file_ext = $image->extension();
            $file_path = "public/books";
            $file_fullName = "{$file_name}.{$file_ext}";
            $image->storeAs($file_path, $file_fullName);

            # Create and store File model to database
            $file = new File();
            $file->name = $file_name;
            $file->ext = $file_ext;
            $file->path = $file_path;
            $file->owner_id = $book->id;
            $file->owner_type = Book::class;
            $file->save();
        }


        # ManyToMany attach
        if ($book->authors()->pluck("id")->diff($request->input("author_ids"))->isNotEmpty()) {
            $book->authors()->sync($request->input("author_ids"));
        }

        return response()->json($book);
    }

    /**
     * Delete a Book
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Book $book)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $book->delete();
        return redirect()->route("books.index");
    }
}
