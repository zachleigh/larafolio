<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;
use Larafolio\Models\TextBlock;

class TextBlockController extends Controller
{
    /**
     * Remove a text block.
     *
     * @param  TextBlock $block Text block to remove.
     */
    public function destroy(TextBlock $block)
    {
        $this->user->removeTextBlock($block);
    }
}
