<?php

namespace Larafolio\Http\Requests;

use Illuminate\Http\Request;

class PageRequest extends ResourceRequest
{
    /**
     * Type of resource.
     *
     * @var string
     */
    protected $resourceType = 'page';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $nameRule = 'required_with:type,links,lines,blocks|unique:pages,name';

        return $this->processRules($request, $nameRule);
    }
}
