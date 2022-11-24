<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublisherRequest;
use App\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublisherController extends Controller
{
    /**
     * Index simple get json list of Publishers
     * @return Json
     *
     */
    public function index()
    {
        $publishers = Publisher::all();
        return response()->json($publishers);
    }

    /**
     * Create new object of publisher with post method and save to database
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePublisherRequest $request)
    {
        $publisher = new Publisher();
        $publisher->name = $request->input("name");
        $publisher->save();
        return response()->json($publisher);
    }

    /**
     * Get a publisher
     * @param Publisher $publisher
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Publisher $publisher)
    {
        return response()->json($publisher);
    }

    /**
     * Edit a publisher
     * @param Request $request
     * @param Publisher $publisher
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StorePublisherRequest $request, Publisher $publisher)
    {
        $publisher->name = $request->input("name");
        $publisher->save();
        return response()->json($publisher);
    }

    /**
     * Delete a publisher
     * @param Publisher $publisher
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Publisher $publisher)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $publisher->delete();
        return redirect()->route("publishers.delete");
    }
}
