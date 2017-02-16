<?php

namespace Larafolio\Http\Content;

use Larafolio\Models\Page;
use Illuminate\Http\Request;
use Larafolio\Models\Project;
use Larafolio\Models\HasContent;
use Illuminate\Support\Collection;

class ContentCrud
{
    /**
     * Get all projects, sorted by oreder.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDashboardProjects()
    {
        return Project::all()->sortBy('order')->values();
    }

    /**
     * Get collection of project image info: [project name => small url].
     *
     * @param  \Illuminate\Support\Collection $projects Collection of projects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDashboardProjectImages(Collection $projects)
    {
        return $projects->mapWithKeys(function (Project $project) {
            return [$project->name => $project->getProjectImageUrl()];
        })->objectIfEmpty();
    }

    /**
     * Get collection of project block info: [project name => block text].
     *
     * @param  \Illuminate\Support\Collection $projects Collection of projects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDashboardProjectBlocks(Collection $projects)
    {
        return $projects->mapWithKeys(function (Project $project) {
            return [$project->name => $project->getProjectBlockText()];
        })->objectIfEmpty();
    }

    /**
     * Get all pages, sorted by oreder.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDashboardPages()
    {
        return Page::all()->sortBy('order')->values();
    }

    /**
     * Show an individual resource in the manager.
     *
     * @param \Larafolio\Models\HasContent $model Resource to show.
     * @param string                       $type  Type of resource (page, project etc.).
     *
     * @return \Illuminate\Http\Response
     */
    public function show(HasContent $model, $type)
    {
        $images = $model->imagesWithProps();

        return view($this->makeRoute('show', $type), [
            $type    => $model,
            'images' => $images,
        ]);
    }

    /**
     * Return the create resource page.
     *
     * @param string $type Type of resource (page, project etc.).
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        return view($this->makeRoute('add', $type));
    }

    /**
     * Add a new resource to the portfolio.
     *
     * @param \Illuminate\Http\Request $request Form request.
     * @param User                     $user    User object.
     * @param string                   $type    Type of resource (page, project etc.).
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $user, $type)
    {
        $addMethod = $this->makeMethod('add', $type);

        $model = $user->{$addMethod}($request->all());

        if ($request->ajax()) {
            return response()->json([$type => $model]);
        }

        return redirect(route("show-{$type}", [$type => $model]));
    }

    /**
     * Return the resource edit form view.
     *
     * @param \Larafolio\Models\HasContent $model Resource to show edit form for.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(HasContent $model)
    {
        $type = $this->getTypeFromModel($model);

        $nextBlock = $model->blocks->pluck('order')->max() + 1;

        $nextLink = $model->links->pluck('order')->max() + 1;

        $nextLine = $model->lines->pluck('order')->max() + 1;

        return view($this->makeRoute('edit', $type), [
            $type       => $model,
            'nextBlock' => $nextBlock,
            'nextLink'  => $nextLink,
            'nextLine'  => $nextLine,
        ]);
    }

    /**
     * Update a resource.
     *
     * @param \Illuminate\Http\Request     $request Request data.
     * @param \Larafolio\Models\HasContent $model   Resource to update.
     * @param User                         $user    User object.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HasContent $model, $user)
    {
        $type = $this->getTypeFromModel($model);

        if ($model->trashed()) {
            $restoreMethod = $this->makeMethod('restore', $type);

            $user->{$restoreMethod}($model);
        } else {
            $updateMethod = $this->makeMethod('update', $type);

            $user->{$updateMethod}($model, $request->all());
        }

        if ($request->ajax()) {
            return response()->json([$type => $model]);
        }

        return redirect(route("show-{$type}", [$type => $model]));
    }

    /**
     * Remove a resource from the portfolio.
     *
     * @param \Illuminate\Http\Request     $request Request data.
     * @param \Larafolio\Models\HasContent $model   Resource to update.
     * @param User                         $user    User object.
     *
     * @return \Illuminate\Http\Response|bool
     */
    public function destroy(Request $request, HasContent $model, $user)
    {
        $type = $this->getTypeFromModel($model);

        if ($model->trashed()) {
            $purgeMethod = $this->makeMethod('purge', $type);

            $user->{$purgeMethod}($model);
        } else {
            $removeMethod = $this->makeMethod('remove', $type);

            $user->{$removeMethod}($model);
        }

        if ($request->ajax()) {
            return response()->json(true);
        }

        return redirect(route('dashboard'));
    }

    /**
     * Make HasContent method.
     *
     * @param string $verb Action to perform.
     * @param string $type Name of resource.
     *
     * @return string
     */
    protected function makeMethod($verb, $type)
    {
        return $verb.ucfirst($type);
    }

    /**
     * Make route name.
     *
     * @param string $verb Route action to perform.
     * @param string $type Name of resource.
     *
     * @return string
     */
    protected function makeRoute($verb, $type)
    {
        return "larafolio::{$type}s.{$verb}";
    }

    /**
     * Get the model type (short class name) from the model.
     *
     * @param \Larafolio\Models\HasContent $model Model to get name of.
     *
     * @return string
     */
    protected function getTypeFromModel(HasContent $model)
    {
        $reflection = new \ReflectionClass($model);

        return lcfirst($reflection->getShortName());
    }

    /**
     * Icons needed for dashboard.
     *
     * @return array
     */
    public function dashboardIcons()
    {
        return [
            'down'    => $this->getIcon('vendor/larafolio/zondicons/arrow-thin-down.svg'),
            'up'      => $this->getIcon('vendor/larafolio/zondicons/arrow-thin-up.svg'),
            'hidden'  => $this->getIcon('vendor/larafolio/zondicons/view-hide.svg'),
            'visible' => $this->getIcon('vendor/larafolio/zondicons/view-show.svg'),
        ];
    }

    /**
     * Get icon file contents.
     *
     * @param  string $path Path to icon file, relative to public path.
     *
     * @return string
     */
    protected function getIcon($path)
    {
        return file_get_contents(public_path($path));
    }
}
