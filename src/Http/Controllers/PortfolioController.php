<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Larafolio\Http\Content\ContentCrud;

class PortfolioController extends Controller
{
    /**
     * Service class for content crud.
     *
     * @var \Larafolio\Http\Content\ContentCrud
     */
    protected $contentCrud;

    /**
     * Construct.
     *
     * @param \Larafolio\Http\Content\ContentCrud $contentCrud Service class for crud.
     */
    public function __construct(ContentCrud $contentCrud)
    {
        parent::__construct();

        $this->contentCrud = $contentCrud;
    }

    /**
     * Show the manager dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = $this->contentCrud->getDashboardProjects();
        
        $projectImages = $this->contentCrud->getDashboardProjectImages($projects);

        $projectBlocks = $this->contentCrud->getDashboardProjectBlocks($projects);

        $pages = $this->contentCrud->getDashboardPages();

        return view('larafolio::dashboard.index', [
            'projects'        => $projects,
            'projectImages'   => $projectImages,
            'projectBlocks'   => $projectBlocks,
            'pages'           => $pages,
            'icons'           => $this->contentCrud->dashboardIcons(),
        ]);
    }

    /**
     * Update project order in portfolio.
     *
     * @param \Illuminate\Http\Request $request Request data containing all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if ($request->has('projects')) {
            $this->user->updateProjectOrder($request->get('projects'));
        } elseif ($request->has('pages')) {
            $this->user->updatePageOrder($request->get('pages'));
        }
    }
}
