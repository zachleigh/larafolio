<?php

namespace Larafolio\Http\Content;

use Illuminate\Http\Request;
use Larafolio\Models\HasContent;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Intervention;

class ContentImages
{
    /**
     * Get all images from resource.
     *
     * @param \Illuminate\Http\Request     $request  Request from user.
     * @param \Larafolio\Models\HasContent $resource Resource to show images for.
     * @param string                       $type     Resource type.
     *
     * @return \Illuminate\Http\Response
     */
    public function getImages(Request $request, HasContent $resource, $type)
    {
        $images = $resource->imagesWithProps();

        if ($request->ajax()) {
            return response()->json($images);
        }

        return view('larafolio::images.manage', [
            'type' => $type,
            'resource' => $resource,
            'images' => $images,
        ]);
    }

    /**
     * Store an image and attach to resource.
     *
     * @param \Illuminate\Http\Request     $request  Request from user.
     * @param \Larafolio\Models\HasContent $resource Resource to show images for.
     * @param User                         $user     User model.
     */
    public function store(Request $request, HasContent $resource, $user)
    {
        $imagePath = $request->file('file')->store('public/images');

        $image = Intervention::make(Storage::get($imagePath))->encode('jpg', 50);

        Storage::put($imagePath, $image);

        $user->addImageToModel($resource, ['path' => $imagePath]);
    }
}
