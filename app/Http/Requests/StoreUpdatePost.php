<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdatePost extends FormRequest
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
        $id = $this->segment(2); // Pegando o id do post na URL (pasta/pasta/id) na posição 2

        $rules = [
            // 'title'   => 'required|min:3|max:160|unique:posts',
            // 'title'   => ['required', 'min:3', 'max:160', "unique:posts,title,{$id},id"], //O tít é único campo title tab posts onde $id != de id
            
            'title' => [
                'required',
                'min:3',
                'max:160',
                Rule::unique('posts')->ignore($id),
            ],
            'image'   => ['required', 'image'],
            'content' => ['nullable', 'min:5', 'max:10000']
        ];

        //Se for verbo put e não tiver imagem, ok, se tiver, valida como image
        if($this->method() == 'PUT') {
            $rules['image'] = ['nullable', 'image'];
        }

        return $rules;
    }
}
