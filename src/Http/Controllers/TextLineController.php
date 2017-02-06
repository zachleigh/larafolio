<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\TextLine;

class TextLineController extends Controller
{
    /**
     * Remove a text line.
     *
     * @param \Larafolio\Models\TextLine $line Text line to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TextLine $line)
    {
        $deleted = $this->user->removeTextLine($line);

        return response()->json($deleted);
    }
}
