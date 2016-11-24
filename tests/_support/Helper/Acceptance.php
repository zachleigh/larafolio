<?php

namespace Helper;

use Larafolio\Models\Project;

class Acceptance extends \Codeception\Module
{
    /**
     * Add a basic project record.
     *
     * @param \AcceptanceTester $I
     * @param string            $name
     * @param string            $description
     * @param string            $info
     * @param string            $link
     * @param string            $slug
     *
     * @return App\Project
     */
    public function addProject(
        \AcceptanceTester $I,
        $name = 'name',
        $description = 'description',
        $info = 'info',
        $link = 'link',
        $slug = 'name',
        $order = null
    ) {
        $order = $order ?? rand(1, 10);

        $id = $I->haveRecord('projects', [
            'name'        => $name,
            'description' => $description,
            'info'        => $info,
            'link'        => $link,
            'slug'        => $name,
            'order'       => $order,
        ]);

        return Project::find($project['id']);
    }

    /**
     * Get a project from the database.
     *
     * @param \AcceptanceTester $I
     * @param int               $id
     *
     * @return App\Project
     */
    public function getProject(\AcceptanceTester $I, $id = 1)
    {
        return Project::find($id);
    }

    /**
     * Login the admin user.
     *
     * @param \AcceptanceTester $I
     */
    public function login(\AcceptanceTester $I)
    {
        $I->maximizeWindow();
        $I->amOnPage('/login');
    }

    /**
     * Add an image to the database.
     *
     * @param \AcceptanceTester $I
     * @param array             $project Project info array.
     */
    public function addImage(\AcceptanceTester $I, Project $project, $file = 'new.jpg')
    {
        $I->amOnProjectPage($I, $project);
        $I->seeCurrentUrlEquals("/manager/{$project->slug()}");
        $I->waitForElement('.dz-hidden-input');
        $I->attachFile('.dz-hidden-input', $file);
        $I->wait(1);
    }

    /**
     * Go to project page.
     *
     * @param \AcceptanceTester $I
     * @param array             $project Project info array.
     */
    public function amOnProjectPage(\AcceptanceTester $I, Project $project)
    {
        $I->amOnPage("/manager/{$project->slug()}");
    }

    /**
     * Go to project image page.
     *
     * @param \AcceptanceTester $I
     * @param array             $project Project info array.
     */
    public function amOnProjectImagePage(\AcceptanceTester $I, Project $project)
    {
        $I->amOnPage("/manager/{$project->slug()}/images");
    }

    /**
     * Go to add project page.
     *
     * @param \AcceptanceTester $I
     */
    public function amOnAddPage(\AcceptanceTester $I)
    {
        $I->amOnPage('/manager/add');
    }

    /**
     * Confirm that the current page is the add page.
     *
     * @param \AcceptanceTester $I
     */
    public function confirmOnAddPage(\AcceptanceTester $I)
    {
        $I->seeCurrentUrlEquals('/manager/add');
    }

    /**
     * Get first image for project.
     *
     * @param array $project Project info array.
     *
     * @return App\Image
     */
    public function getImageFromProjectArray(Project $project)
    {
        return $project->images[0];
    }

    /**
     * Get all blocks for project.
     *
     * @param array $project Project info array.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProjectBlocks(Project $project)
    {
        return $project->blocks;
    }

    /**
     * Fill in form fields with data from array.
     *
     * @param \AcceptanceTester $I
     * @param array             $data Array of form data.
     */
    public function fillForm(\AcceptanceTester $I, array $data)
    {
        foreach ($data as $key => $value) {
            $I->fillField(['name' => $key], $value);
        }
    }

    /**
     * Remove a text block.
     *
     * @param \AcceptanceTester $I
     * @param string            $id Id of block to remove.
     */
    public function removeBlock(\AcceptanceTester $I, $id)
    {
        $I->click($id);
        $I->click('Remove Block');
        $I->wait(1);
    }
}
