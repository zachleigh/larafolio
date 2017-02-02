<?php

namespace Larafolio\Models\UserTraits;

use Larafolio\Models\Project;

trait ManagesProjects
{
    /**
     * Add a blocks and links to model.
     *
     * @param HasContent $model Model to add extras to.
     * @param array      $data  Array of posted user data.
     *
     * @return HasContent
     */
    protected abstract function addModelExtras(HasContent $model, array $data);

    /**
     * Update a HasContent model and its children.
     *
     * @param  HasContent $model Model to update.
     * @param  array      $data  Array of posted user data.
     *
     * @return HasContent
     */
    protected abstract function updateModel(HasContent $model, array $data);

    /**
     * Permanently delete a model.
     *
     * @param  HasContent $model Model to delete.
     *
     * @return boolean
     */
    protected abstract function purgeModel(HasContent $model);

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

        return $this->addModelExtras($project, $data);
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
        return $this->updateModel($project, $data);
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
        return $this->purgeModel($project);
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