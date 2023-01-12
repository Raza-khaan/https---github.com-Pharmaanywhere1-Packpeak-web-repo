<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissedPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missed_patients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('missed_tablet')->nullable();
            $table->boolean('extra_tablet')->nullable();
            $table->boolean('wrong_tablet')->nullable();
            $table->boolean('wrong_day')->nullable();
            $table->string('other')->nullable();
            $table->string('person_involved')->nullable();
            $table->text('initials')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
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
        Schema::dropIfExists('missed_patients');
    }
}
