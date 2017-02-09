<?php

namespace Larafolio\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

abstract class ResourceRequest extends FormRequest
{
    /**
     * Type of resource.
     *
     * @var string
     */
    protected $resourceType = 'resource';

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
    protected function processRules(Request $request, $nameRule)
    {
        $id = $request->input('id');

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
        $type = ucfirst($this->resourceType);

        return [
            'name.required_with'     => $type.' name is required.',
            'name.unique'            => $type.' name is already taken.',
            'blocks.*.text.required' => 'All blocks must have text.'
        ];
    }
}
