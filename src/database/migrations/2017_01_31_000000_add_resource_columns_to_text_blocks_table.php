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
            $table->integer('resource_id')->nullable()->unsigned()->index();
            $table->string('resource_type')->index()->default('Larafolio\\Models\\Project');

            // $table->dropForeign('text_blocks_project_id_foreign');
            // $table->dropIndex('text_blocks_project_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('text_blocks', function (Blueprint $table) {
            $table->dropIndex('text_blocks_resource_id_index');
            $table->dropIndex('text_blocks_resource_type_index');
            $table->dropColumn('resource_id');
            $table->dropColumn('resource_type');

            // $table->foreign('project_id')
            //   ->references('id')
            //   ->on('projects')
            //   ->onDelete('cascade');
        });
    }
}
