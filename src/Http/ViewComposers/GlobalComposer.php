<?php

namespace Larafolio\Http\ViewComposers;

use Auth;
use Illuminate\View\View;
use Larafolio\Models\Page;
use Larafolio\Models\Project;

class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        $user = Auth::user();

        $projects = $user ? Project::all() : collect([]);

        $pages = $user ? Page::all() : collect([]);

        $view->with('navProjects', $projects)
             ->with('navPages', $pages);
    }
}
