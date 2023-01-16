<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Carbon;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('form_no')->nullable();
            $table->string('form_name')->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

DB::table('forms')->insert([ 
['form_no'=>'form1','form_name'=>'Add PickUp','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form2','form_name'=>'PickUps Report','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form3','form_name'=>'PickUps Calender','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form4','form_name'=>'6 Monthly Compliance Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form5','form_name'=>'All Compliance Index Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form6','form_name'=>'Add Patients','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form7','form_name'=>'New Patients Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form8','form_name'=>'Patients Picked Up Last Month','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form9','form_name'=>'Add Checking','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form10','form_name'=>'Checking Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form11','form_name'=>'Add Near Misses','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form12','form_name'=>'All Near Misses','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form13','form_name'=>'Last Month Miss Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form14','form_name'=>'Near Miss Monthly Reports v2','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form15','form_name'=>'Add Return','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form16','form_name'=>'All Return','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form17','form_name'=>'Add Audit','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form18','form_name'=>'All Audit','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form19','form_name'=>'Add Note For Patients','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form20','form_name'=>'Note For Patients Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()],
['form_no'=>'form21','form_name'=>'Sms Tracking Reports','created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]
         ]);
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
