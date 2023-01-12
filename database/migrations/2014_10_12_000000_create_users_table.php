<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('initials_name')->nullable();
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
            $table->string('username')->nullable();
            $table->string('pin')->nullable(); 
            $table->string('website_id')->nullable(); 
            $table->string('subscription')->nullable();
            $table->date('expired_date')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('favourite')->nullable();
            $table->integer('status')->length(2)->default(1);
            $table->integer('isverified')->length(2)->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        // User::create(array('name'=>'Augurs','website_id'=>'1','first_name'=>'Augurs','first_name'=>'Augurs','subscription'=>'1','email'=>'Augurs@gmail.com','company_name'=>'augurs','registration_no'=>'PHA0018311','initials_name'=>'A.T.','host_name'=>'superadmin','password'=>Hash::make('12345678'),'address'=>'gomti nagar lucknow')); 
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
