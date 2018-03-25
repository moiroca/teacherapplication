<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedInteger('school_year_id');
            $table->integer('semester');

            $table->foreign('school_year_id')
                    ->references('id')
                    ->on('school_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign('subjects_school_year_id_foreign');

            $table->dropColumn('school_year_id');
            $table->dropColumn('semester');
        });
    }
}
