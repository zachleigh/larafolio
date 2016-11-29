<?php

use Larafolio\Models\Project;
use Illuminate\Support\Facades\Artisan;

class DashboardCest
{
    public function _before(\AcceptanceTester $I)
    {
        Artisan::call('migrate:refresh');

        Artisan::call('db:seed', [
            '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
        ]);
    }

    public function _after(\AcceptanceTester $I)
    {
        Artisan::call('migrate:refresh');

        Artisan::call('db:seed', [
            '--class' => 'Larafolio\database\seeds\DatabaseSeeder',
        ]);
    }

    public function projects_can_be_moved_up(AcceptanceTester $I)
    {
        $project = Project::all()->sortBy('order')->last();

        $I->wantTo('Move a project up.');
        $I->login($I);
        foreach (range(0, 4) as $time) {
            $I->click('#up'.$project->id());
        }
        $I->wait(1);
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 0,
        ]);
    }

    public function projects_can_be_moved_down(AcceptanceTester $I)
    {
        $project = Project::all()->sortBy('order')->first();

        $I->wantTo('Move a project down.');
        $I->login($I);
        foreach (range(0, 4) as $time) {
            $I->click('#down'.$project->id());
        }
        $I->seeInDatabase('projects', [
            'id'    => $project->id(),
            'order' => 4,
        ]);
    }
}
