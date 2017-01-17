<?php

namespace Larafolio\Http\Controllers;

use View;
use Illuminate\Http\Request;
use Larafolio\Models\Project;

class SettingsController extends Controller
{
    /**
     * Show the requested settings page if it exists.
     *
     * @param  string $page Settings page name.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($page)
    {
        if (!View::exists("larafolio::settings.{$page}")) {
            abort(404); 
        }

        if (method_exists($this, $method = 'show'.ucfirst($page))) {
            return $this->{$method}();
        }

        return view("larafolio::settings.{$page}");
    }

    /**
     * Show the projects settings page.
     *
     * @return \Illuminate\Http\Response
     */
    private function showProjects()
    {
        $deletedProjects = Project::onlyTrashed()
            ->orderBy('deleted_at', 'DESC')
            ->get()
            ->map(function ($project) {
                return $project->generateProps();
            });

        return view('larafolio::settings.projects', [
            'deletedProjects' => $deletedProjects
        ]);
    }
}
