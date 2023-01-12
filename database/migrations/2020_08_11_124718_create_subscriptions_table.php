<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Subscription; 

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->bigInteger('no_of_admins')->unsigned()->nullable();
            $table->bigInteger('no_of_technicians')->unsigned()->nullable();
            $table->string('app_logout_time')->default('10')->nullable();
            $table->string('default_cycle')->default('4')->nullable();
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
            $table->bigInteger('plan_validity')->unsigned()->nullable()->comment('Plan validity');
            $table->bigInteger('allowed_sms')->nullable();
            $table->bigInteger('status')->unsigned()->default(1);
            $table->timestamps();
        });
       
        //   Subscription::insert([ 
        //     ['title'=>'Large','no_of_admins'=>'1','no_of_technicians'=>'3','form1'=>'on','form2'=>'on','form3'=>'on','form4'=>'off','form5'=>'off','form6'=>'off','form7'=>'off','form8'=>'off','form9'=>'off','form10'=>'off','form11'=>'off','form12'=>'off',
        //     'form13'=>'off','form14'=>'off','form15'=>'off','form16'=>'off','form17'=>'off','form18'=>'off','form19'=>'off','form20'=>'off','form21'],
        //     ['title'=>'Medium','no_of_admins'=>'1','no_of_technicians'=>'3','form1'=>'off','form2'=>'off','form3'=>'off','form4'=>'off','form5'=>'off','form6'=>'off','form7'=>'off','form8'=>'off','form9'=>'off','form10'=>'off','form11'=>'off','form12'=>'off',
        //     'form13'=>'off','form14'=>'off','form15'=>'off','form16'=>'off','form17'=>'off','form18'=>'off','form19'=>'off','form20'=>'off','form21'],
        //     ['title'=>'Small','no_of_admins'=>'1','no_of_technicians'=>'3','form1'=>'off','form2'=>'off','form3'=>'off','form4'=>'off','form5'=>'off','form6'=>'off','form7'=>'off','form8'=>'off','form9'=>'off','form10'=>'off','form11'=>'off','form12'=>'off',
        //     'form13'=>'off','form14'=>'off','form15'=>'off','form16'=>'off','form17'=>'off','form18'=>'off','form19'=>'off','form20'=>'off','form21']
        //  ]); 
         Subscription::insert([ 
            ['title'=>'Large','no_of_admins'=>'1','no_of_technicians'=>'3','plan_validity'=>365],
            ['title'=>'Medium','no_of_admins'=>'1','no_of_technicians'=>'2','plan_validity'=>365],
            ['title'=>'Small','no_of_admins'=>'1','no_of_technicians'=>'1','plan_validity'=>365]
         ]); 

    }

    /**
 * Reverse the migrations.
 *
 * @return void
 */

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
    


}
