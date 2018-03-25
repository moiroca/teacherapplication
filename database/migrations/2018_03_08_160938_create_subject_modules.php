<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('subject_id');
            $table->unsignedInteger('module_id');

            $table->foreign('subject_id')
                ->references('id')
                ->on('subjects');

            $table->foreign('module_id')
                ->references('id')
                ->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_modules', function (Blueprint $table) {
            $table->dropForeign('subject_modules_subject_id_foreign');
            $table->dropForeign('subject_modules_module_id_foreign');
        });

        Schema::drop('subject_modules');
    }
}
