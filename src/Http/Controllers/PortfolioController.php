<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Update project order in portfolio.
     *
     * @param Request $request Request data containing all projects.
     */
    public function update(Request $request)
    {
        $this->user->updateProjectOrder($request->get('projects'));
    }
}
