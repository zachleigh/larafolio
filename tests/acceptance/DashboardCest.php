<?php

use Larafolio\Models\Project;

class DashboardCest
{
    public function _before(\AcceptanceTester $I)
    {
        $I->migrate();
    }

    public function projects_can_be_moved_up(AcceptanceTester $I)
    {
        $I->wantTo('Move a project up.');
        $I->login($I);
        $project = Project::all()->sortBy('order')->last();
        foreach (range(0, 4) as $time) {
            $I->click('#up'.$project->id());
        }
        $I->amOnPage('/manager');
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 0,
        ]);
    }

    public function projects_can_be_moved_down(AcceptanceTester $I)
    {
        $I->wantTo('Move a project down.');
        $I->login($I);
        $project = Project::all()->sortBy('order')->first();
        foreach (range(0, 4) as $time) {
            $I->click('#down'.$project->id());
            $I->wait(1);
        }
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 4,
        ]);
    }

    public function user_can_toggle_visibility_from_dashboard(AcceptanceTester $I)
    {
        $project = $I->getProject($I);

        $I->wantTo('Toggle project visibility from the dashboard.');
        $I->login($I);
        $I->click('#makeVisible'.$project->id());
        $I->wait(1);
        $I->see('Project Visible');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => true,
        ]);
        $I->click('#makeHidden'.$project->id());
        $I->wait(1);
        $I->see('Project Hidden');
        $I->seeInDatabase('projects', [
            'id'      => $project->id(),
            'visible' => false,
        ]);
    }
}
