<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Link;

class LinkController extends Controller
{
    /**
     * Remove a link.
     *
     * @param Larafolio\Models\Link $link Link to remove.
     */
    public function destroy(Link $link)
    {
        $this->user->removeLink($link);
    }
}
