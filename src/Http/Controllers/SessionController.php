<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Store data in the session.
     *
     * @param  Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        if ($request->get('type') === 'flash') {
            return session()->flash($request->get('key'), $request->get('value'));
        }
    }
}
