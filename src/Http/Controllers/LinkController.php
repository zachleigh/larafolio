<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Link;

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
}
