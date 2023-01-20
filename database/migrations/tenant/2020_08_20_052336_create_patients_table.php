<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('patients', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->date('dob')->nullable();
			$table->string('phone_number')->nullable();
			$table->string('location')->nullable();
			$table->unsignedBigInteger('facilities_id')->nullable();
			$table->string('facility_other_desc')->nullable();
			$table->string('text_when_picked_up_deliver')->nullable();
			$table->string('mobile_no')->nullable();
			$table->text('address')->nullable();
			$table->string('notification')->nullable();
			$table->boolean('sms_allowed')->nullable()->default(0);
			$table->bigInteger('created_by')->nullable();
			$table->bigInteger('deleted_by')->nullable();
			$table->bigInteger('status')->unsigned()->default('1');
			$table->bigInteger('exempted')->unsigned()->default('0');
			$table->timestamp('created_at')->useCurrent();
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
			$table->softDeletes();
			$table->bigInteger('is_archive')->default('0');
			$table->bigInteger('website_id')->unsigned()->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('patients');
	}
}
