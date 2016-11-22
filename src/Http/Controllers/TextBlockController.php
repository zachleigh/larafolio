<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\TextBlock;

class TextBlockController extends Controller
{
    /**
     * Remove a text block.
     *
     * @param Larafolio\Models\TextBlock $block Text block to remove.
     */
    public function destroy(TextBlock $block)
    {
        $this->user->removeTextBlock($block);
    }
}
