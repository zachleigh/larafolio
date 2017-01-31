<?php

namespace Larafolio\database\seeds;

use Larafolio\Models\Project;
use Illuminate\Database\Seeder;
use Larafolio\Models\TextBlock;

class TextBlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            factory(TextBlock::class, ['resource_id' => $project->id()])->create();
        }
    }
}
