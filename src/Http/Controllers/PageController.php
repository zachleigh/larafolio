<?php

namespace Larafolio\Http\Controllers;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Http\Content\ContentCrud;
use Larafolio\Http\Requests\AddResourceRequest;

class PageController extends Controller
{
    /**
     * Service class for content crud.
     *
     * @var Larafolio\Http\Content\ContentCrud
     */
    protected $contentCrud;

    /**
     * Construct.
     *
     * @param Larafolio\Http\Content\ContentCrud $contentCrud Service class for crud.
     */
    public function __construct(ContentCrud $contentCrud)
    {
        parent::__construct();

        $this->contentCrud = $contentCrud;
    }

    /**
     * Return all projects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->contentCrud->index(Page::all());
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
        $page = Page::withBlocks($slug)->firstOrFail();

        return $this->contentCrud->show($page, 'page');
    }

    /**
     * Return the page create page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->contentCrud->create('page');
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
        return $this->contentCrud->store($request, $this->user, 'page');
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
        $page = Page::full($slug)->firstOrFail();

        return $this->contentCrud->edit($page);
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
        $page = Page::withTrashed()->where('slug', $slug)->firstOrFail();

        return $this->contentCrud->update($request, $page, $this->user);
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
        $page = Page::withTrashed()->where('slug', $slug)->firstOrFail();

        return $this->contentCrud->destroy($request, $page, $this->user);
    }
}
