<?php

namespace App\Rules;

use App\Book;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CheckBookReturn implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        dd(Book::query()
//            ->whereIn("id", $value)
//            ->where(function (Builder $query) {
//                $query
//                    ->orWhereDoesntHave("borrows", function (Builder $query) {
//                        $query
//                            ->where("status", "!=", "returned")
//                            ->withoutGlobalScope("user_id");
//                    });
//            })->get()->toArray());
        return array_intersect($value, Book::query()->whereHas("borrows", function (Builder $query) {
            $query->where("user_id", "!=", Auth::id())
                ->where("status", "!=", "returned")
                ->withoutGlobalScope("user_id");
        })->pluck("id")->all()) ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This :attribute book is not available.';
    }
}
