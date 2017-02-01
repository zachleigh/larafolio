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
     * @return Project
     */
    public function addProject(array $data)
    {
        $data['order'] = Project::all()->pluck('order')->max() + 1;

        $project = Project::create($data);

        foreach (collect($data)->get('blocks', []) as $block) {
            $this->addBlockToProject($project, $block);
        }

        foreach (collect($data)->get('links', []) as $link) {
            $this->addLinkToProject($project, $link);
        }

        return $project;
    }

    /**
     * Update a project in the portfolio.
     *
     * @param Project $project Project to update.
     * @param array   $data    Array of data to save.
     *
     * @return Project
     */
    public function updateProject(Project $project, array $data)
    {
        $project->update($data);

        $this->updateProjectTextBlocks($project, $data);

        $this->updateProjectLinks($project, $data);

        return $project;
    }

    /**
     * Remove a project from the portfolio.
     *
     * @param Project $project Project to remove.
     *
     * @return bool|null
     */
    public function removeProject(Project $project)
    {
        return $project->delete();
    }

    /**
     * Restore a soft deleted project.
     *
     * @param Project $project Project to restore.
     *
     * @return bool|null
     */
    public function restoreProject(Project $project)
    {
        $this->updateProject($project, ['visible' => false]);

        return $project->restore();
    }

    /**
     * Hard delete a project from the portfolio.
     *
     * @param Project $project Project to purge.
     *
     * @return bool|null
     */
    public function purgeProject(Project $project)
    {
        foreach ($project->images as $image) {
            $this->removeImage($image);
        }

        $project->restore();

        return $project->forceDelete();
    }

    /**
     * Update the order of projects in the portfolio.
     *
     * @param array $data Array of data for projects.
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
     * Set order of data based on order value.
     *
     * @param array $data Data array containing 'order' index.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function setOrder(array $data)
    {
        return collect($data)->sortBy('order')
            ->map(function ($item, $key) {
                $item['order'] = $key;

                return $item;
            });
    }

    /**
     * Add a text block to a project.
     *
     * @param Project $project   Project to add text block to.
     * @param array   $blockData Array of text block data.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addBlockToProject(Project $project, array $blockData)
    {
        return $project->blocks()->create($blockData);
    }

    /**
     * Update a text block.
     *
     * @param TextBlock $textBlock Text block to update.
     * @param array     $blockData Array of text block data.
     *
     * @return TextBlock
     */
    public function updateTextBlock(TextBlock $textBlock, array $blockData)
    {
        $textBlock->update($blockData);

        return $textBlock;
    }

    /**
     * Update project text blocks by adding new ones and updating existing ones.
     *
     * @param Project $project Project that blocks belong to.
     * @param array   $data    Array of project information.
     */
    public function updateProjectTextBlocks(Project $project, array $data)
    {
        $blockData = collect($data)->get('blocks', []);

        $blockData = $this->setOrder($blockData);

        foreach ($blockData as $singleBlockData) {
            if (isset($singleBlockData['resource_id'])) {
                $block = TextBlock::find($singleBlockData['id']);

                $this->updateTextBlock($block, $singleBlockData);
            } else {
                $this->addBlockToProject($project, $singleBlockData);
            }
        }
    }

    /**
     * Remove a text block from a project.
     *
     * @param TextBlock $textBlock The text block to delete.
     *
     * @return bool|null
     */
    public function removeTextBlock(TextBlock $textBlock)
    {
        return $textBlock->delete();
    }

    /**
     * Add image to a project.
     *
     * @param Project $project   Project to add image to.
     * @param array   $imageData Array of image infomation.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addImageToProject(Project $project, array $imageData)
    {
        return $project->images()->create($imageData);
    }

    /**
     * Update image name and caption.
     *
     * @param Image $image     Image to update.
     * @param array $imageData Array of inmage information.
     *
     * @return Image
     */
    public function updateImageInfo(Image $image, array $imageData)
    {
        $image->update($imageData);

        return $image;
    }

    /**
     * Remove image from storage and delete database info.
     *
     * @param Image $image Image to remove.
     *
     * @return bool|null
     */
    public function removeImage(Image $image)
    {
        Storage::delete($image->path());

        return $image->delete();
    }

    /**
     * Add a link to a project.
     *
     * @param Project $project  Project to add link to.
     * @param array   $linkData Array of link info.
     */
    public function addLinkToProject(Project $project, array $linkData)
    {
        return $project->links()->create($linkData);
    }

    /**
     * Update a link.
     *
     * @param Link  $link     Link to update.
     * @param array $linkData Array of link data.
     *
     * @return Link
     */
    public function updateLink(Link $link, array $linkData)
    {
        $link->update($linkData);

        return $link;
    }

    /**
     * Update project links by adding new ones and updating existing ones.
     *
     * @param Project $project Project that links belong to.
     * @param array   $data    Array of project information.
     */
    public function updateProjectLinks(Project $project, array $data)
    {
        $linkData = collect($data)->get('links', []);

        $linkData = $this->setOrder($linkData);

        foreach ($linkData as $singleLinkData) {
            if (isset($singleLinkData['resource_id'])) {
                $link = Link::find($singleLinkData['id']);

                $this->updateLink($link, $singleLinkData);
            } else {
                $this->addLinkToProject($project, $singleLinkData);
            }
        }
    }

    /**
     * Remove link from a project.
     *
     * @param Link $link Link to remove.
     *
     * @return bool|null
     */
    public function removeLink(Link $link)
    {
        return $link->delete();
    }
}
