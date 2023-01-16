<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('no_of_admins')->unsigned()->nullable();
            $table->bigInteger('no_of_technicians')->unsigned()->nullable();
            $table->string('app_logout_time')->default('10');
            $table->string('default_cycle')->default('4');
            $table->boolean('form1')->default('0')->comment('Add PickUp');
            $table->boolean('form2')->default('0')->comment('PickUps Report');
            $table->boolean('form3')->default('0')->comment('PickUps Calender');
            $table->boolean('form4')->default('0')->comment('6 Monthly Compliance Reports');
            $table->boolean('form5')->default('0')->comment('All Compliance Index Reports');
            $table->boolean('form6')->default('0')->comment('Add Patients');
            $table->boolean('form7')->default('0')->comment('New Patients Reports');
            $table->boolean('form8')->default('0')->comment('Patients Picked Up Last Month');
            $table->boolean('form9')->default('0')->comment('Add Checking');
            $table->boolean('form10')->default('0')->comment('Checking Reports');
            $table->boolean('form11')->default('0')->comment('Add Near Misses');
            $table->boolean('form12')->default('0')->comment('All Near Misses');
            $table->boolean('form13')->default('0')->comment('Last Month Miss Reports');
            $table->boolean('form14')->default('0')->comment('Near Miss Monthly Reports v2');
            $table->boolean('form15')->default('0')->comment('Add Return');
            $table->boolean('form16')->default('0')->comment('All Return');
            $table->boolean('form17')->default('0')->comment('Add Audit');
            $table->boolean('form18')->default('0')->comment('All Audit');
            $table->boolean('form19')->default('0')->comment('Add Note For Patients');
            $table->boolean('form20')->default('0')->comment('Note For Patients Reports');
            $table->boolean('form21')->default('0')->comment('Sms Tracking Reports');
            $table->boolean('is_default')->default('1')->comment('default Access Level');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->bigInteger('website_id')->unsigned()->nullable();
            $table->bigInteger('reminderdefaultdays')->default('2');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_levels');
    }
}
