<?php

namespace App\Http\Controllers;

use App\Author;
use App\Http\Requests\StoreAuthorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Index simple get json list of Authors
     * @return Json
     *
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json($authors);
    }

    /**
     * Create new object of Author
     * @param StoreAuthorRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAuthorRequest $request)
    {
        $author = new Author();
        $author->first_name = $request->input("first_name");
        $author->last_name = $request->input("last_name");
        $author->save();
        return response()->json($author);
    }

    /**
     * Get a Author
     * @param Author $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Author $author)
    {
        return response()->json($author);
    }

    /**
     * Edit a Author
     * @param StoreAuthorRequest $request
     * @param Author $author
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreAuthorRequest $request, Author $author)
    {
        $author->first_name = $request->input("first_name");
        $author->last_name = $request->input("last_name");
        $author->save();
        return response()->json($author);
    }

    /**
     * Delete a Author
     * @param Author $author
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Author $author)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $author->delete();
        return redirect()->route("authors.index");
    }
}
