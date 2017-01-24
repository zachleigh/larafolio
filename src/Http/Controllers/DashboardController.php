<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Project;

class DashboardController extends Controller
{
    /**
     * Show the manager dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all()->sortBy('order')->values();

        $images = $projects->mapWithKeys(function ($project) {
            return [$project->name() => $project->getProjectImageUrl()];
        });

        $blocks = $projects->mapWithKeys(function ($project) {
            return [$project->name() => $project->getProjectBlockText()];
        });

        return view('larafolio::projects.index', [
            'projects' => $projects,
            'images'   => $images,
            'blocks'   => $blocks,
        ]);
    }
}
