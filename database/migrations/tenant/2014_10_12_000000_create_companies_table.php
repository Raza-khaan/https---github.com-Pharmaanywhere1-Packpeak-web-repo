<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('name');
            $table->string('initials_name')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->datetime('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->string('company_name')->nullable();
            $table->binary('sign')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('registration_no')->nullable();
            $table->string('address')->nullable();
            $table->string('host_name')->nullable(); 
            $table->string('website_id')->nullable(); 
            $table->string('pin')->nullable(); 
            $table->string('subscription')->nullable(); 
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('roll_type')->default('technician'); 
            //  ['technician', 'admin']
            $table->date('expired_date')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
