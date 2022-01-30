<?php

namespace App\Http\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
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
            'portfolio.*.name'=>'required',
            'category'=>'required',
            'mobileImage'=>'required',
            'cover'=>'required',
            'date'=>'required',
            'portfolio.*.desc'=>'required|min:8|max:255',
            'portfolio.*.client'=>'required',
        ];
    }
    public function messages()
    {
        return [

            'required'=>'this field is required',
            'portfolio.*.desc.min' => 'Your Portfolio\'s description  Is Too Short',
            'category.array' => 'Your Portfolio\'s pcategory is required array',
            'category.required' => 'Your Portfolio\'s pcategory is required',
            'portfolio.*.name.required' => 'the name is required',
            'portfolio.*.desc.required' => 'the description is required',
            'portfolio.*.client.required' => 'the client is required'
        ];
    }
}
