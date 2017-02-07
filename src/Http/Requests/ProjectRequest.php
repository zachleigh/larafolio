<?php

namespace Larafolio\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $id = $request->input('id');

        $nameRule = 'required_with:type,links,lines,blocks|unique:projects,name';

        if ($id) {
            $nameRule .= ','.$id;
        }

        return [
            'name'          => $nameRule,
            'blocks.*.text' => 'required',
        ];
    }

    /**
     * Error messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required_with'     => 'Project name is required.',
            'name.unique'            => 'Project name is already taken.',
            'blocks.*.text.required' => 'All blocks must have text.'
        ];
    }
}
