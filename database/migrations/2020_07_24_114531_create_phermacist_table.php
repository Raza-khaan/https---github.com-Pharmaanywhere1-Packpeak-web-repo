<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhermacistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phermacist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->binary('sign')->nullable();
            $table->date('dob')->nullable();
            $table->string('host_name')->nullable();
            $table->string('username')->nullable();
            $table->string('pin')->nullable();
            $table->string('password')->nullable();
            $table->string('subscription')->nullable();
            $table->date('expired_date')->nullable();
            $table->integer('verification_status')->length(2)->default(0);
            $table->integer('payment_verification_status')->length(2)->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('phermacist');
    }
}
