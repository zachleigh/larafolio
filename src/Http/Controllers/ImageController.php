<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Update image name and caption.
     *
     * @param \Illuminate\Http\Request $request Request object.
     * @param int                      $imageID ID of image to be updated.
     *
     * @return \Illuminate\Http\Response|bool
     */
    public function update(Request $request, $imageID)
    {
        $image = Image::findOrFail($imageID);

        $imageData = $request->only(['name', 'caption', 'alt']);

        $this->user->updateImageInfo($image, $imageData);

        if ($request->ajax()) {
            return response()->json(true);
        }

        return back();
    }

    /**
     * Remove image from portfolio.
     *
     * @param \Illuminate\Http\Request $request Request object.
     * @param int                      $imageID ID of image to be removed.
     *
     * @return \Illuminate\Http\Response|bool
     */
    public function destroy(Request $request, $imageID)
    {
        $image = Image::findOrFail($imageID);

        $this->user->removeImage($image);

        if ($request->ajax()) {
            return response()->json(true);
        }

        return back();
    }
}
