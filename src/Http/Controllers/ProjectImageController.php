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
     * @param Larafolio\Http\Content\ContentImages $contentImages Service class for content images.
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
     * @param Larafolio\Models\Project $project Project to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        return $this->contentImages->getImages($request, $project, 'project');
    }

    /**
     * Add a new project image to the portfolio.
     *
     * @param \Illuminate\Http\Request $request Form request.
     * @param Larafolio\Models\Project $project The project to add the image too.
     */
    public function store(Request $request, Project $project)
    {
        $this->contentImages->store($request, $project, $this->user);
    }
}
