<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizzesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('quizzes', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('subject_id');
			$table->string('title');

			$table->foreign('subject_id')
					->references('id')
					->on('subjects');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('quizzes', function (Blueprint $table) {
			$table->dropForeign('quizzes_subject_id_foreign');
		});

		Schema::drop('quizzes');
	}

}
