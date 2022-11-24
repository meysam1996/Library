<?php

namespace App\Http\Controllers;

use App\Borrow;
use App\Http\Requests\StoreBorrowRequest;
use App\Http\Requests\UpdateBorrowRequest;
use App\Http\Requests\UpdateStatusBorrowRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BorrowController extends Controller
{
    /**
     * Index simple get json list of Borrow
     * @return Json
     *
     */
    public function index()
    {
        $books = Borrow::with(['users', 'books'])->get();
        return response()->json($books);
    }

    /**
     * Create a Borrow
     * permission for user and not admin
     * validation in StoreBorrowRequest
     * @param StoreBorrowRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(StoreBorrowRequest $request)
    {
        $borrow = new Borrow();
        $borrow->user_id = Auth::user()->id;
        $borrow->status = "reserved";
        $borrow->deadline = $request->input("deadline");

        $borrow->save();

        # ManyToMany attach
        $borrow->books()->attach($request->input("book_ids"));

        return response()->json($borrow);

    }

    /**
     * Update a Borrow
     * permission for admin and owner user
     * @param UpdateBorrowRequest $request
     * @param Borrow $borrow
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBorrowRequest $request, Borrow $borrow)
    {
        Validator::make($borrow->toArray(), [
            "delivered_at" => [Rule::in(null)],
            "returned_at" => [Rule::in(null)],
        ],[
            'delivered_at.in' => "You can not edit because book is delivered.",
            'returned_at.in' => 'You can not edit because book is returned.',
        ])->validate();

        $borrow->user_id = Auth::user()->isAdmin() ? $request->input("user_id") : Auth::id();

        # ManyToMany attach
        $borrow->books()->sync($request->input("book_ids"));

        $borrow->deadline = $request->input("deadline");

        $borrow->save();
        return response()->json($borrow);
    }

    public function delivering(UpdateStatusBorrowRequest $request, Borrow $borrow)
    {
        # Validate Request
        Validator::make($request->all(), [
            'status' => [Rule::in(['delivered'])]
        ])->validate();

        # Check Borrow object status in reserved
        Validator::make($borrow->toArray(), [
            'status' => [Rule::in(['reserved'])],
        ])->validate();

        $borrow->status = $request->input("status");
        $borrow->delivered_at = Carbon::now();

        $borrow->save();
        return response()->json($borrow);
    }

    public function returning(UpdateStatusBorrowRequest $request, Borrow $borrow)
    {
        # Validate Request
        Validator::make($request->all(), [
            'status' => [Rule::in(['returned'])]
        ])->validate();

        # Check Borrow object status in reserved
        Validator::make($borrow->toArray(), [
            'status' => [Rule::in(['delivered'])],
        ])->validate();

        $borrow->status = $request->input("status");
        $borrow->returned_at = Carbon::now();

        $borrow->save();
        return response()->json($borrow);
    }

    public function delete(Borrow $borrow)
    {
        if (Auth::user()->isAdmin() or $borrow->id == Auth::id()) {
            $borrow->delete();
        }else{
            abort(403);
        }
    }
}
