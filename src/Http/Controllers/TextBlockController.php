<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\TextBlock;

class TextBlockController extends Controller
{
    /**
     * Remove a text block.
     *
     * @param \Larafolio\Models\TextBlock $block Text block to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TextBlock $block)
    {
        $deleted = $this->user->removeTextBlock($block);

        return response()->json($deleted);
    }
}
