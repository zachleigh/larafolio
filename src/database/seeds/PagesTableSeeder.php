<?php

namespace Larafolio\database\seeds;

use Larafolio\Models\Link;
use Larafolio\Models\Page;
use Illuminate\Database\Seeder;
use Larafolio\Models\TextBlock;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 5) as $i) {
            $name = 'Page'.$i;

            factory(Page::class)
                ->create(['name' => $name])
                ->each(function (Page $project) {
                    $project->blocks()->save(factory(TextBlock::class)->make());

                    $project->links()->save(factory(Link::class)->make());
                });
        }
    }
}