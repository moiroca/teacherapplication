<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnouncementsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('announcements', function (Blueprint $table) {
			$table->increments('id');
			$table->longText('content');
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
		Schema::table('announcements', function (Blueprint $table) {
			$table->dropForeign('announcements_subject_id_foreign');
		});

		Schema::drop('announcements');
	}

}
