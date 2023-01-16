<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('website_id')->nullable();
            $table->bigInteger('action_by')->nullable();
            $table->unsignedBigInteger('patient_id')->nullable();
            $table->enum('action',['1','2','3','4','5','6','7'])->default('5')->comment(' 1=>create,2=>update,3=>delete,4->login,5=>logout ,6=>on,7=>off'); // 
            $table->string('action_detail')->nullable();
            $table->text('comment')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('type')->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('authenticated_by', ['packnpeaks', 'google','linkedin'])->default('packnpeaks');
            $table->string('status')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events_logs');
    }
}
