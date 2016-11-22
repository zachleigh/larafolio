<?php

namespace Larafolio\Models;

use Larafolio\Models\Image;
use Larafolio\Models\Project;
use Larafolio\Models\TextBlock;
use Illuminate\Support\Facades\Storage;

trait ManagesPortfolio
{
    /**
     * Add a project to the portfolio.
     *
     * @param array $data Array of data to save.
     *
     * @return App\Project
     */
    public function addProject(array $data)
    {
        $project = Project::create($data);

        foreach (collect($data)->get('blocks', []) as $block) {
            $this->addBlockToProject($project, $block);
        }

        return $project;
    }

    /**
     * Update a project in the portfolio.
     *
     * @param App\Project $project Project to update.
     * @param array       $data    Array of data to save.
     *
     * @return bool
     */
    public function updateProject(Project $project, array $data)
    {
        $project->update($data);

        $blockData = $this->getReducedBlockData($data);

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
     * Get block data from data array with order reduced to minimum.
     *
     * @param  array  $data Data array from request.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getReducedBlockData(array $data)
    {
        $blockData = collect($data)->get('blocks', []);

        return collect($blockData)->sortBy('order')
            ->map(function ($block, $key) {
                $block['order'] = $key;

                return $block;
            });
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param App\Project $project Project to remove.
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
     * @param Project $project   Project to add text block to.
     * @param array   $blockData Array of text block data.
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
     * @param  TextBlock $textBlock Text block to update.
     * @param  array     $blockData Array of text block data.
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
     * @param  TextBlock $textBlock The text block to delete.
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
     * @param App\Project $project   Project to add image to.
     * @param array       $imageData Array of image infomation.
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
     * @param App\Image $image     Image to update.
     * @param array     $imageData Array of inmage information.
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
     * @param App\Image $image Image to remove.
     *
     * @return bool
     */
    public function removeImage(Image $image)
    {
        Storage::delete($image->path());

        return $image->delete();
    }
}
