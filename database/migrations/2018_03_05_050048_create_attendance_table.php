<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attendance', function (Blueprint $table) {
			$table->increments('id');
			$table->dateTime('date');
			$table->unsignedInteger('subject_id');

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
		Schema::table('attendance', function (Blueprint $table) {
			$table->dropForeign('attendance_subject_id_foreign');
		});
		
		Schema::drop('attendance');
	}

}
