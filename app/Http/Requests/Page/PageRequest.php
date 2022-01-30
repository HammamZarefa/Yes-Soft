<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
            'page.*.title'=>'required',
            'page.*.text'=>'required|min:8|max:255',
        ];
    }
    public function messages()
    {
        return [
            'required'=>'this field is required',
            'page.*.text.min' => 'Your Page\'s description  Is Too Short',
            'page.*.title.required' => 'the title is required',
            'page.*.text.required' => 'the text is required',

        ];
    }
}
