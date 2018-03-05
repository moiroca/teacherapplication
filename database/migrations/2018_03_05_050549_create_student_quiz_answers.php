<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentQuizAnswers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_quiz_answers', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('quiz_item_id');
			$table->unsignedInteger('option_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('student_quiz_answers');
	}

}
