<?php

namespace App\Http\Requests;

use App\Rules\CheckBookReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBorrowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            'book_ids' => ['array', 'min:1','max:4', 'required',new CheckBookReturn],
//            'book_ids.*' => ['numeric', 'required'],
        ];
    }
}
