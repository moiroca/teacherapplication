<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subjects', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->unsignedInteger('teacher_id');

			$table->foreign('teacher_id')
					->references('id')
					->on('users');
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
			$table->dropForeign('subjects_teacher_id_foreign');
		});

		Schema::drop('subjects');
	}

}
