<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Larafolio\Http\Requests\AddProjectRequest;

class ProjectController extends Controller
{
    /**
     * Allowed project request fields.
     *
     * @var array
     */
    protected $fields = ['name', 'link', 'blocks', 'visible', 'type', 'order'];

    /**
     * Show the manager dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all()->sortBy('order')->values();

        $images = $projects->mapWithKeys(function ($project) {
            return [$project->name() => $project->getProjectImage()];
        });

        $blocks = $projects->mapWithKeys(function ($project) {
            return [$project->name() => $project->getProjectBlock()];
        });

        return view('larafolio::projects.index', [
            'projects' => $projects,
            'images'   => $images,
            'blocks'   => $blocks,
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
        $project = Project::withBlocks($slug);

        $images = $project->imagesWithProps();

        return view('larafolio::projects.show', [
            'project' => $project,
            'images'  => $images,
        ]);
    }

    /**
     * Return the project create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('larafolio::projects.add');
    }

    /**
     * Add a new project to the portfolio.
     *
     * @param Larafolio\Http\Requests\AddProjectRequest $request Form request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddProjectRequest $request)
    {
        $project = $this->user->addProject($request->all());

        if ($request->ajax()) {
            return response()->json(['project' => $project]);
        }

        return redirect(route('show-project', ['project' => $project]));
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
        $project = Project::withBlocks($slug);

        $nextInOrder = $project->blocks->pluck('order')->max() + 1;

        return view('larafolio::projects.edit', [
            'project'     => $project,
            'nextInOrder' => $nextInOrder,
        ]);
    }

    /**
     * Update a project.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param Larafolio\Models\Project $project Project to update.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $this->user->updateProject($project, $request->all());

        if ($request->ajax()) {
            return response()->json(['project' => $project]);
        }

        return redirect(route('show-project', ['project' => $project]));
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param Larafolio\Models\Project $project Project to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project)
    {
        $this->user->removeProject($project);

        if ($request->ajax()) {
            return;
        }

        return redirect(route('dashboard'));
    }
}
