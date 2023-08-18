<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {   if(request()->routeIs('post.store')){
            $imageRules = 'image|required';
        }else{
            $imageRules = 'image|sometimes';
        }
        return [
            'title' => 'string|required',
            'content' => 'required',
            'image' => $imageRules,
            'category' => 'required'
        ];
    }
}
