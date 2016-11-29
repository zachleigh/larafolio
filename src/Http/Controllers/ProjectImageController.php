<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Intervention\Image\Facades\Image as Intervention;
use Illuminate\Support\Facades\Storage;

class ProjectImageController extends Controller
{
    /**
     * Show images for project.
     *
     * @param \Illuminate\Http\Request $request Request from user.
     * @param Larafolio\Models\Project $project Project to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        if ($request->ajax()) {
            $images = $project->imagesWithProps();

            return response()->json($images);
        }

        return view('larafolio::images.manage', [
            'project' => $project,
        ]);
    }

    /**
     * Add a new project to the portfolio.
     *
     * @param \Illuminate\Http\Request $request Form request.
     * @param Larafolio\Models\Project $project The project to add the image too.
     */
    public function store(Request $request, Project $project)
    {
        $imagePath = $request->file('file')->store('public/images');

        $image = Intervention::make(Storage::get($imagePath))->encode('jpg', 50);

        Storage::put($imagePath, $image);

        $this->user->addImageToProject($project, ['path' => $imagePath]);
    }
}
