<?php

namespace Larafolio\database\seeds;

use Larafolio\Models\Link;
use Larafolio\Models\Project;
use Illuminate\Database\Seeder;
use Larafolio\Models\TextBlock;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 5) as $i) {
            $name = 'Project'.$i;

            factory(Project::class)
                ->create(['name' => $name])
                ->each(function (Project $project) {
                    $project->blocks()->save(factory(TextBlock::class)->make());

                    $project->links()->save(factory(Link::class)->make());
                });
        }
    }
}
