<?php

namespace Larafolio\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Update project order in portfolio.
     *
     * @param Request $request Request data containing all projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $updated = $this->user->updateProjectOrder($request->get('projects'));

        return response()->json($updated);
    }
}
