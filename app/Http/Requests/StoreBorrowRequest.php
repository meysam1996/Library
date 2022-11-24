<?php

namespace App\Http\Requests;

use App\Borrow;
use App\Rules\CheckBookReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBorrowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return (!Auth::user()->isAdmin() and Borrow::query()->where("status", "!=", "returned")->count() == 0);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'deadline' => ['numeric', 'required'],
            'book_ids' => ['array', 'min:1','max:4', 'required', new CheckBookReturn],
//            'book_ids.*' => ['numeric', 'required'],
        ];
    }
}
