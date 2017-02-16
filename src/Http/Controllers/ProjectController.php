<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Larafolio\Http\Content\ContentCrud;
use Larafolio\Http\Requests\ProjectRequest;

class ProjectController extends Controller
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
     * Return all projects.
     *
     * @param \Illuminate\Http\Request $request Request data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(Project::all());
        }

        $projects = $this->contentCrud->getDashboardProjects();
        
        $projectImages = $this->contentCrud->getDashboardProjectImages($projects);

        $projectBlocks = $this->contentCrud->getDashboardProjectBlocks($projects);

        return view('larafolio::projects.index', [
            'projects'        => $projects,
            'projectImages'   => $projectImages,
            'projectBlocks'   => $projectBlocks,
            'icons'           => $this->contentCrud->dashboardIcons(),
        ]);
    }

    /**
     * Show an individual project in the manager.
     *
     * @param string $slug Slug of the project to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $project = Project::withBlocks($slug)->firstOrFail();

        return $this->contentCrud->show($project, 'project');
    }

    /**
     * Return the project create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->contentCrud->create('project');
    }

    /**
     * Add a new project to the portfolio.
     *
     * @param \Larafolio\Http\Requests\ProjectRequest $request Form request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        return $this->contentCrud->store($request, $this->user, 'project');
    }

    /**
     * Return the project edit form view.
     *
     * @param string $slug Slug for the project to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $project = Project::full($slug)->firstOrFail();

        return $this->contentCrud->edit($project);
    }

    /**
     * Update a project.
     *
     * @param \Larafolio\Http\Requests\ProjectRequest $request Request data.
     * @param string                                  $slug    Slug of project to update.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, $slug)
    {
        $project = Project::withTrashed()->where('slug', $slug)->firstOrFail();

        return $this->contentCrud->update($request, $project, $this->user);
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param string                   $slug    Slug of project to remove.
     *
     * @return \Illuminate\Http\Response|bool
     */
    public function destroy(Request $request, $slug)
    {
        $project = Project::withTrashed()->where('slug', $slug)->firstOrFail();

        return $this->contentCrud->destroy($request, $project, $this->user);
    }
}
