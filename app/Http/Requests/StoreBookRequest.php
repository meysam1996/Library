<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name" => ['max:255','regex:/[a-z0-9\s\w]/', 'required'],
            "summary" => ['string', 'max:1000'],
            "description" => ['string', 'max:1000'],
            "printer_key" => ['numeric', 'required'],
            "serial_number" => ['numeric', 'unique:books', 'required_if:'.$this->route()->getName() == "books.store"],
            "publisher_id" => ['numeric', 'nullable'],
            "subject_id" => ['numeric', 'nullable'],
            "author_ids" => ['nullable'],
            'file_upload' => ['file','mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
}
