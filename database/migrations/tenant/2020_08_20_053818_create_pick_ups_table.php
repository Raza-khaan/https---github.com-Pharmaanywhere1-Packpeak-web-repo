<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pick_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->date('dob')->nullable();
            $table->string('last_pick_up_date')->nullable();
            $table->bigInteger('weeks_last_picked_up')->unsigned()->nullable();
            $table->bigInteger('no_of_weeks')->unsigned()->nullable();
            $table->string('location')->nullable();
            $table->string('pick_up_by')->nullable();
            $table->string('carer_name')->nullable();
            $table->text('notes_from_patient')->nullable();
            $table->text('notes_for_patient')->nullable();
            $table->binary('pharmacist_sign')->nullable();
            $table->binary('patient_sign')->nullable();
            $table->date('pickup_date')->nullable();
            $table->bigInteger('pickup_slot')->default('0');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
            $table->string('pharmacy_image')->nullable();
            $table->string('patient_image')->nullable();
            $table->Integer('hold')->default('0');
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
    public function down()
    {
        Schema::dropIfExists('pick_ups');
    }
}
