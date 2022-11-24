<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Message;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function index()
    {
//        $users = User::query()->where("id", "!=", Auth::id())->where(function (Builder $builder) {
//            $builder->whereHas("inboxMessages", function (Builder $builder) {
//                $builder->where("receiver", "=", Auth::id())->orWhere("sender", "=", Auth::id());
//            });
        $users = User::query()
            ->where("id", "!=", Auth::id())
            ->where(function (Builder $builder) {
            $builder->whereHas("sentMessages", function (Builder $builder) {
                $builder->where("receiver", "=", Auth::id());
            })->orWhereHas("inboxMessages", function (Builder $builder) {
                $builder->where("sender", "=", Auth::id());
            });
        })->with("inboxMessages", "sentMessages")->get();

        return response()->json($users);
    }

    /**
     * Create new message
     * @param StoreMessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(StoreMessageRequest $request)
    {
        $message = new Message();
        $message->body = $request->input("body");
        $message->sender = Auth::id();
        $message->receiver = Auth::user()->isAdmin() ? $request->input("receiver") : User::where("username", "=", "admin")->first()->id;

        $message->save();

        return response()->json($message);
    }

    public function delete(Message $message)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $message->delete();
        return response()->json("delete message success!");
    }


}
