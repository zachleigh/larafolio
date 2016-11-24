<?php

namespace Larafolio\Models;

use Illuminate\Support\Facades\Storage;

trait ManagesPortfolio
{
    /**
     * Add a project to the portfolio.
     *
     * @param array $data Array of data to save.
     *
     * @return Larafolio\Models\Project
     */
    public function addProject(array $data)
    {
        $data['order'] = Project::all()->pluck('order')->max() + 1;

        $project = Project::create($data);

        foreach (collect($data)->get('blocks', []) as $block) {
            $this->addBlockToProject($project, $block);
        }

        return $project;
    }

    /**
     * Update a project in the portfolio.
     *
     * @param Larafolio\Models\Project $project Project to update.
     * @param array                    $data    Array of data to save.
     *
     * @return bool
     */
    public function updateProject(Project $project, array $data)
    {
        $project->update($data);

        $blockData = collect($data)->get('blocks', []);

        $blockData = $this->setOrder($blockData);

        foreach ($blockData as $singleBlockData) {
            if (isset($singleBlockData['project_id'])) {
                $block = TextBlock::find($singleBlockData['id']);

                $this->updateTextBlock($block, $singleBlockData);
            } else {
                $this->addBlockToProject($project, $singleBlockData);
            }
        }

        return $project;
    }

    /**
     * Update the order of projects in the portfolio.
     *
     * @param  array  $data Array of data for projects.
     */
    public function updateProjectOrder(array $data)
    {
        $projectData = $this->setOrder($data);

        foreach ($projectData as $singleProjectData) {
            $project = Project::find($singleProjectData['id']);

            $project->update($singleProjectData);
        }
    }

    /**
     * Set order of data based on order.
     *
     * @param array $data Data array containing 'order' index.
     *
     * @return  Collection
     */
    public function setOrder(array $data)
    {
        return collect($data)->sortBy('order')
            ->map(function ($item, $key) {
                $item['order'] = $key;

                return $item;
            });
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param Larafolio\Models\Project $project Project to remove.
     *
     * @return bool
     */
    public function removeProject(Project $project)
    {
        foreach ($project->images as $image) {
            $this->removeImage($image);
        }

        return $project->delete();
    }

    /**
     * Add a text block to a project.
     *
     * @param Larafolio\Models\Project $project   Project to add text block to.
     * @param array                    $blockData Array of text block data.
     *
     * @return App\TextBlock
     */
    public function addBlockToProject(Project $project, array $blockData)
    {
        return $project->blocks()->create($blockData);
    }

    /**
     * Update a text block.
     *
     * @param Larafolio\Models\TextBlock $textBlock Text block to update.
     * @param array                      $blockData Array of text block data.
     *
     * @return App\TextBlock
     */
    public function updateTextBlock(TextBlock $textBlock, array $blockData)
    {
        $textBlock->update($blockData);

        return $textBlock;
    }

    /**
     * Remove a text block from a project.
     *
     * @param Larafolio\Models\TextBlock $textBlock The text block to delete.
     *
     * @return bool
     */
    public function removeTextBlock(TextBlock $textBlock)
    {
        return $textBlock->delete();
    }

    /**
     * Add image to a project.
     *
     * @param Larafolio\Models\Project $project   Project to add image to.
     * @param array                    $imageData Array of image infomation.
     *
     * @return App\Image
     */
    public function addImageToProject(Project $project, array $imageData)
    {
        return $project->images()->create($imageData);
    }

    /**
     * Update image name and caption.
     *
     * @param Larafolio\Models\Image $image     Image to update.
     * @param array                  $imageData Array of inmage information.
     *
     * @return App\Image
     */
    public function updateImageInfo(Image $image, array $imageData)
    {
        $image->update($imageData);

        return $image;
    }

    /**
     * Remove image from storage and delete database info.
     *
     * @param Larafolio\Models\Image $image Image to remove.
     *
     * @return bool
     */
    public function removeImage(Image $image)
    {
        Storage::delete($image->path());

        return $image->delete();
    }
}
