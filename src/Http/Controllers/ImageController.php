<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Larafolio\Http\Content\ContentImages;
use Intervention\Image\Facades\Image as Intervention;

class ImageController extends Controller
{
    /**
     * Service class for content images.
     *
     * @var \Larafolio\Http\Content\ContentImages
     */
    protected $contentImages;

    /**
     * Construct.
     *
     * @param \Larafolio\Http\Content\ContentImages $contentImages Service class for content images.
     */
    public function __construct(ContentImages $contentImages)
    {
        parent::__construct();

        $this->contentImages = $contentImages;
    }

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
            $imageData = $this->updateImageInstance($request, $image);
        } else {
            $imageData = $request->only(['name', 'caption', 'alt']);
        }

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

    /**
     * Update an image path.
     *
     * @param \Illuminate\Http\Request $request Request from user.
     * @param \Larafolio\Models\Image  $image   Image to update.
     */
    protected function updateImageInstance(Request $request, Image $image)
    {
        $imagePath = $this->contentImages->saveImage($request);

        Storage::delete($image->path);

        return ['path' => $imagePath];
    }
}
