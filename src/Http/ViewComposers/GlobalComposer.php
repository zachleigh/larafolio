<?php

namespace Larafolio\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use Larafolio\Models\Project;

class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        $projects = $user ? Project::all() : collect([]);

        $view->with('navProjects', $projects);
    }
}