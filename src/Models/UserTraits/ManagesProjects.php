<?php

namespace Larafolio\Models\UserTraits;

use Larafolio\Models\Project;

trait ManagesProjects
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
            $this->addBlockToModel($project, $block);
        }

        foreach (collect($data)->get('links', []) as $link) {
            $this->addLinkToModel($project, $link);
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

        $this->updateAllTextBlocks($project, $data);

        $this->updateAllLinks($project, $data);

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
}
