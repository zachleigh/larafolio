<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceColumnsToLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->renameColumn('project_id', 'resource_id');
            $table->string('resource_type')->index()->default('Larafolio\\Models\\Project');

            $table->index('resource_id');

            $table->dropForeign('links_project_id_foreign');
            $table->dropIndex('links_project_id_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_resource_id_index');
            $table->dropIndex('links_resource_type_index');
            $table->dropColumn('resource_id');
            $table->dropColumn('resource_type');

            $table->integer('project_id')->nullable()->unsigned()->index();

            $table->foreign('project_id')
              ->references('id')
              ->on('projects')
              ->onDelete('cascade');
        });
    }
}
