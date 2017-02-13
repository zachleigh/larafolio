<?php

use Larafolio\Models\Page;
use Larafolio\Models\Project;

class DashboardCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    // **PROJECTS** //
    public function projects_can_be_moved_up(AcceptanceTester $I)
    {
        $I->wantTo('Move a project up.');
        $I->login($I);
        $project = Project::all()->sortBy('order')->last();
        foreach (range(0, 4) as $time) {
            $I->click('#projectUp'.$project->id);
        }
        $I->amOnPage('/manager');
        $I->seeInDatabase('projects', [
            'id'    => $project->id,
            'order' => 0,
        ]);
    }

    public function projects_can_be_moved_down(AcceptanceTester $I)
    {
        $I->wantTo('Move a project down.');
        $I->login($I);
        $project = Project::all()->sortBy('order')->first();
        foreach (range(0, 4) as $time) {
            $I->click('#projectDown'.$project->id);
            $I->wait(1);
        }
        $I->seeInDatabase('projects', [
            'id'    => $project->id,
            'order' => 4,
        ]);
    }

    public function user_can_toggle_project_visibility_from_dashboard(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Toggle project visibility from the dashboard.');
        $I->login($I);
        $I->click('#projectMakeVisible'.$project->id);
        $I->wait(1);
        $I->see('Resource Visible');
        $I->seeInDatabase('projects', [
            'id'      => $project->id,
            'visible' => true,
        ]);
        $I->click('#projectMakeHidden'.$project->id);
        $I->wait(1);
        $I->see('Resource Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id,
            'visible' => false,
        ]);
    }

    // **PAGES** //
    public function pages_can_be_moved_up(AcceptanceTester $I)
    {
        $I->wantTo('Move a page up.');
        $I->login($I);
        $page = Page::all()->sortBy('order')->last();
        foreach (range(0, 4) as $time) {
            $I->click('#pageUp'.$page->id);
        }
        $I->amOnPage('/manager');
        $I->seeInDatabase('pages', [
            'id'    => $page->id,
            'order' => 0,
        ]);
    }

    public function pages_can_be_moved_down(AcceptanceTester $I)
    {
        $I->wantTo('Move a page down.');
        $I->login($I);
        $page = Page::all()->sortBy('order')->first();
        foreach (range(0, 4) as $time) {
            $I->click('#pageDown'.$page->id);
            $I->wait(1);
        }
        $I->seeInDatabase('pages', [
            'id'    => $page->id,
            'order' => 4,
        ]);
    }

    public function user_can_toggle_page_visibility_from_dashboard(AcceptanceTester $I)
    {
        $page = $I->getPage($I);

        $I->wantTo('Toggle page visibility from the dashboard.');
        $I->login($I);
        $I->click('#pageMakeVisible'.$page->id);
        $I->wait(1);
        $I->see('Resource Visible');
        $I->seeInDatabase('pages', [
            'id'      => $page->id,
            'visible' => true,
        ]);
        $I->click('#pageMakeHidden'.$page->id);
        $I->wait(1);
        $I->see('Resource Hidden');
        $I->seeInDatabase('pages', [
            'id'      => $page->id,
            'visible' => false,
        ]);
    }
}
