<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceColumnsToTextBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('text_blocks', function (Blueprint $table) {
            $table->renameColumn('project_id', 'resource_id');
            $table->string('resource_type')->index()->default('Larafolio\\Models\\Project');

            $table->dropForeign('text_blocks_project_id_foreign');
            $table->dropIndex('text_blocks_project_id_index');
            $table->index('resource_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
