<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Larafolio\Http\Content\ContentImages;

class ProjectImageController extends Controller
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
     * Show images for project.
     *
     * @param \Illuminate\Http\Request $request Request from user.
     * @param string                   $slug    Slug of project to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug)
    {
        $project = Project::full($slug)->firstOrFail();

        return $this->contentImages->getImages($request, $project, 'project');
    }

    /**
     * Add a new project image to the portfolio.
     *
     * @param \Illuminate\Http\Request $request Form request.
     * @param string                   $slug    Slug of project to add image to.
     */
    public function store(Request $request, $slug)
    {
        $project = Project::full($slug)->firstOrFail();

        $this->contentImages->store($request, $project, $this->user);
    }

    public function update(Request $request, $id)
    {
        $image = Image::firstOrFail($id);

        $this->contentImages->update($request, $image, $this->user);
    }
}
