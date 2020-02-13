<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLoginLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_login_logs', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('fkey_user_login_logs_user_id');
			$table->timestamp('login_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('ip_address', 45)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_login_logs');
	}

}
