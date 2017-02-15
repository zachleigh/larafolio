<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Intervention;

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

        if ($request->hasFile('file')) {
            $this->updatePath($request, $image);
        } else {
            $imageData = $request->only(['name', 'caption', 'alt']);

            $this->user->updateImageInfo($image, $imageData);
        }

        if ($request->ajax()) {
            return response()->json($image);
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

    /**
     * Update an image path.
     *
     * @param \Illuminate\Http\Request $request Request from user.
     * @param \Larafolio\Models\Image  $image   Image to update.
     */
    protected function updatePath(Request $request, Image $image)
    {
        $imagePath = $request->file('file')->store('public/images');

        $imageFile = Intervention::make(Storage::get($imagePath))->encode('jpg', 50);

        Storage::put($imagePath, $imageFile);

        Storage::delete($image->path);

        $this->user->updateImageInfo($image, ['path' => $imagePath]);
    }
}
