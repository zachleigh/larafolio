<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Return all projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(Page::all());
    }

    /**
     * Show an individual page in the manager.
     *
     * @param string $slug Slug of the page to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $page = Page::withBlocks($slug)->first();

        if (!$page) {
            abort(404, "No page with slug {$slug} found.");
        }

        $images = $page->imagesWithProps();

        return view('larafolio::pages.show', [
            'page' => $page,
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
        // return view('larafolio::projects.add');
    }

    /**
     * Add a new project to the portfolio.
     *
     * @param AddProjectRequest $request Form request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddProjectRequest $request)
    {
        // $project = $this->user->addProject($request->all());

        // if ($request->ajax()) {
        //     return response()->json(['project' => $project]);
        // }

        // return redirect(route('show-project', ['project' => $project]));
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
        // $project = Project::full($slug)->first();

        // $nextBlock = $project->blocks->pluck('order')->max() + 1;

        // $nextLink = $project->links->pluck('order')->max() + 1;

        // return view('larafolio::projects.edit', [
        //     'project'   => $project,
        //     'nextBlock' => $nextBlock,
        //     'nextLink'  => $nextLink,
        // ]);
    }

    /**
     * Update a project.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param string                   $slug    Slug of project to update.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        // $project = Project::withTrashed()->where('slug', $slug)->first();

        // if ($project->trashed()) {
        //     $this->user->restoreProject($project);
        // } else {
        //     $this->user->updateProject($project, $request->all());
        // }

        // if ($request->ajax()) {
        //     return response()->json(['project' => $project]);
        // }

        // return redirect(route('show-project', ['project' => $project]));
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param string                   $slug    Slug of project to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slug)
    {
        // $project = Project::withTrashed()->where('slug', $slug)->first();

        // if ($project->trashed()) {
        //     $this->user->purgeProject($project);
        // } else {
        //     $this->user->removeProject($project);
        // }

        // if ($request->ajax()) {
        //     return response()->json(true);
        // }

        // return redirect(route('dashboard'));
    }
}
