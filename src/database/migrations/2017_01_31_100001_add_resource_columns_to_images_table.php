<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResourceColumnsToImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->renameColumn('project_id', 'resource_id');
            $table->string('resource_type')->index()->default('Larafolio\\Models\\Project');

            $table->index('resource_id');

            $table->dropForeign('images_project_id_foreign');
            $table->dropIndex('images_project_id_index');
            $table->dropIndex('images_path_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex('images_resource_id_index');
            $table->dropIndex('images_resource_type_index');
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
