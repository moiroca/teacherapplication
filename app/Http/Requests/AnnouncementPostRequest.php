<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'document'      => 'required|mimes:pdf,jpg,jpeg,png,gif|max:2048',
            'announcement'  => 'required'
        ];
    }



    public function messages()
    {
        return [
            'document.mimes' => 'Only accepts PDF and image file formats (jpg,jpeg,png,gif)'
        ];
    }
}
