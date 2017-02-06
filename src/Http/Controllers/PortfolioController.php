<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Models\Project;

class PortfolioController extends Controller
{
    /**
     * Show the manager dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all()->sortBy('order')->values();

        $projectImages = $projects->mapWithKeys(function (Project $project) {
            return [$project->name() => $project->getProjectImageUrl()];
        })->objectIfEmpty();

        $projectBlocks = $projects->mapWithKeys(function (Project $project) {
            return [$project->name() => $project->getProjectBlockText()];
        })->objectIfEmpty();

        $pages = Page::all()->sortBy('order')->values();

        return view('larafolio::projects.index', [
            'projects'        => $projects,
            'projectImages'   => $projectImages,
            'projectBlocks'   => $projectBlocks,
            'pages'           => $pages,
            'icons'           => $this->dashboardIcons(),
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

    /**
     * Icons needed for dashboard.
     *
     * @return array
     */
    protected function dashboardIcons()
    {
        return [
            'down'    => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-down.svg')),
            'up'      => file_get_contents(public_path('vendor/larafolio/zondicons/arrow-thin-up.svg')),
            'hidden'  => file_get_contents(public_path('vendor/larafolio/zondicons/view-hide.svg')),
            'visible' => file_get_contents(public_path('vendor/larafolio/zondicons/view-show.svg')),
        ];
    }
}
