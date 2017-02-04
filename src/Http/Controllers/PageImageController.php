<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Http\Content\ContentImages;

class PageImageController extends Controller
{
    /**
     * Service class for content images.
     *
     * @var Larafolio\Http\Content\ContentImages
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
     * @param Larafolio\Models\Page    $page    Page to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Page $page)
    {
        return $this->contentImages->getImages($request, $page, 'page');
    }

    /**
     * Add a new page image.
     *
     * @param \Illuminate\Http\Request $request Form request.
     * @param Larafolio\Models\Page    $page    The page to add the image too.
     */
    public function store(Request $request, Page $page)
    {
        $this->contentImages->store($request, $page, $this->user);
    }
}
