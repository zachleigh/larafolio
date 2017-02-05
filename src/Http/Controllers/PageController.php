<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Http\Requests\AddResourceRequest;

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
     * Return the page create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('larafolio::pages.add');
    }

    /**
     * Add a new page to the portfolio.
     *
     * @param Larafolio\Http\Requests\AddResourceRequest $request Form request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddResourceRequest $request)
    {
        $page = $this->user->addPage($request->all());

        if ($request->ajax()) {
            return response()->json(['page' => $page]);
        }

        return redirect(route('show-page', ['page' => $page]));
    }

    /**
     * Return the page edit form view.
     *
     * @param string $slug Slug for the page to edit.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $page = Page::full($slug)->first();

        $nextBlock = $page->blocks->pluck('order')->max() + 1;

        $nextLink = $page->links->pluck('order')->max() + 1;

        return view('larafolio::pages.edit', [
            'page'   => $page,
            'nextBlock' => $nextBlock,
            'nextLink'  => $nextLink,
        ]);
    }

    /**
     * Update a page.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param string                   $slug    Slug of page to update.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $page = Page::withTrashed()->where('slug', $slug)->first();

        if ($page->trashed()) {
            $this->user->restorePage($page);
        } else {
            $this->user->updatePage($page, $request->all());
        }

        if ($request->ajax()) {
            return response()->json(['page' => $page]);
        }

        return redirect(route('show-page', ['page' => $page]));
    }

    /**
     * Remove a page from the portfolio.
     *
     * @param \Illuminate\Http\Request $request Request data.
     * @param string                   $slug    Slug of page to remove.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slug)
    {
        $page = Page::withTrashed()->where('slug', $slug)->first();

        if ($page->trashed()) {
            $this->user->purgePage($page);
        } else {
            $this->user->removePage($page);
        }

        if ($request->ajax()) {
            return response()->json(true);
        }

        return redirect(route('dashboard'));
    }
}
