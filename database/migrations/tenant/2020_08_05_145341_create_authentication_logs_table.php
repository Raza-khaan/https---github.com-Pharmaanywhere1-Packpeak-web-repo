<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthenticationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authentication_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('authenticatable_type')->nullable();
            $table->bigInteger('authenticatable_id')->unsigned()->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('uid')->nullable();
            $table->string('type')->nullable();
            $table->text('user_agent')->nullable();
            // $table->string('authenticated_by')->nullable();
            $table->enum('authenticated_by', ['packnpeaks', 'google','linkedin'])->default('packnpeaks');
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            // $table->timestamps();
            $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('authentication_logs');
    }
}
