<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Link;
use Illuminate\Http\Request;

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
     * Check the status of a url and return json http code.
     *
     * @param Request $request Http request with 'url'.
     *
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $url = $request->input('url');

        $handle = curl_init($url);

        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);

        curl_setopt($handle, CURLOPT_NOBODY, true);

        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);

        curl_setopt($handle, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        curl_close($handle);

        return response()->json(['httpCode' => $httpCode]);
    }
}
