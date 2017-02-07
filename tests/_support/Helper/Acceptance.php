<?php

namespace Helper;

use Larafolio\Models\Page;
use Larafolio\Models\Project;
use Larafolio\Models\HasContent;
use Illuminate\Support\Facades\Artisan;

class Acceptance extends \Codeception\Module
{
    /**
     * Migrate and seed the database.
     */
    public function migrate()
    {
        Artisan::call('migrate:refresh');

        Artisan::call('db:seed', [
            '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
        ]);
    }

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
        $order = $order === null ? rand(1, 10) : $order;

        $project = Project::create([
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
     * @return Larafolio\Models\Project
     */
    public function getProject(\AcceptanceTester $I, $id = 1)
    {
        return Project::find($id);
    }

    /**
     * Get a page from the database.
     *
     * @param \AcceptanceTester $I
     * @param int               $id
     *
     * @return Larafolio\Models\Page
     */
    public function getPage(\AcceptanceTester $I, $id = 1)
    {
        return Page::find($id);
    }

    /**
     * Login the admin user.
     *
     * @param \AcceptanceTester $I
     */
    public function login(\AcceptanceTester $I, $size = 'max')
    {
        if ($size === 'max') {
            $I->resizeWindow(1300,800);;
        } elseif ($size === 'mobile') {
            $I->resizeWindow(400,700);
        }
        
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
        $I->seeCurrentUrlEquals("/manager/projects/{$project->slug()}");
        $I->waitForElement('.dz-hidden-input');
        $I->attachFile('.dz-hidden-input', $file);
        $I->wait(1);
    }

    /**
     * Add an image to the database.
     *
     * @param \AcceptanceTester $I
     * @param array             $project Project info array.
     */
    public function addImageToPage(\AcceptanceTester $I, Page $page, $file = 'new.jpg')
    {
        $I->amOnPagePage($I, $page);
        $I->seeCurrentUrlEquals("/manager/pages/{$page->slug()}");
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
        $I->amOnPage("/manager/projects/{$project->slug()}");
    }

    /**
     * Go to page page.
     *
     * @param \AcceptanceTester $I
     * @param array             $page Page info array.
     */
    public function amOnPagePage(\AcceptanceTester $I, Page $page)
    {
        $I->amOnPage("/manager/pages/{$page->slug()}");
    }

    /**
     * Go to project image page.
     *
     * @param \AcceptanceTester $I
     * @param array             $project Project info array.
     */
    public function amOnProjectImagePage(\AcceptanceTester $I, Project $project)
    {
        $I->amOnPage("/manager/projects/{$project->slug()}/images");
    }

    /**
     * Go to add project page.
     *
     * @param \AcceptanceTester $I
     */
    public function amOnAddPage(\AcceptanceTester $I)
    {
        $I->amOnPage('/manager/projects/add');
    }

    /**
     * Go to add page page.
     *
     * @param \AcceptanceTester $I
     */
    public function amOnAddPagePage(\AcceptanceTester $I)
    {
        $I->amOnPage('/manager/pages/add');
    }

    /**
     * Confirm that the current page is the add page.
     *
     * @param \AcceptanceTester $I
     */
    public function confirmOnAddPage(\AcceptanceTester $I)
    {
        $I->seeCurrentUrlEquals('/manager/projects/add');
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
     * Get first image for resource.
     *
     * @param array $resource HasContent info array.
     *
     * @return App\Image
     */
    public function getImageFromResourceArray(HasContent $resource)
    {
        return $resource->images[0];
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

    /**
     * Remove a link.
     *
     * @param \AcceptanceTester $I
     * @param string            $id Id of link to remove.
     */
    public function removeLink(\AcceptanceTester $I, $id)
    {
        $I->click($id);
        $I->click('Remove Link');
        $I->wait(1);
    }

    /**
     * Remove a line.
     *
     * @param \AcceptanceTester $I
     * @param string            $id Id of line to remove.
     */
    public function removeLine(\AcceptanceTester $I, $id)
    {
        $I->click($id);
        $I->click('Remove Line');
        $I->wait(1);
    }
}
