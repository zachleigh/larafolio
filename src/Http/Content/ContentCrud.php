<?php

namespace Larafolio\Http\Content;

use Illuminate\Http\Request;
use Larafolio\Models\HasContent;
use Illuminate\Support\Collection;
use Larafolio\Http\Requests\AddResourceRequest;

class ContentCrud
{
    /**
     * Return all.
     *
     * @param \Illuminate\Support\Collection $collection Colelction of all resources.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Collection $collection)
    {
        return response()->json($collection);
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
     * @param \Larafolio\Http\Requests\AddResourceRequest $request Form request.
     * @param User                                        $user    User object.
     * @param string                                      $type    Type of resource (page, project etc.).
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AddResourceRequest $request, $user, $type)
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
}
