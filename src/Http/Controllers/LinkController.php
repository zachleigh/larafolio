<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Link;
use Illuminate\Http\Request;
use Larafolio\Http\HttpValidator\HttpValidator;

class LinkController extends Controller
{
    /**
     * Remove a link.
     *
     * @param Larafolio\Models\Link $link Link to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        $deleted = $this->user->removeLink($link);

        return response()->json($deleted);
    }

    /**
     * Check the status of a link and return json http code.
     *
     * @param Request       $request       Http request with 'url'.
     * @param HttpValidator $httpValidator Instance of http validator class.
     *
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request, HttpValidator $httpValidator)
    {
        $url = $request->input('url');

        $httpCode = $httpValidator->validate($url);

        return response()->json(['httpCode' => $httpCode]);
    }
}
