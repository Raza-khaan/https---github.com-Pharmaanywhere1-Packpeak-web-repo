<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\FormTable; 
class CreateFormTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_tables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("form_name")->nullable();
            $table->string("form_description")->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
        FormTable::insert([
            ['form_name'=>"form1","form_description"=>"Add PickUp"],
            ['form_name'=>"form2","form_description"=>"PickUps Report"],
            ['form_name'=>"form3","form_description"=>"PickUps Calender"],
            ['form_name'=>"form4","form_description"=>"6 Monthly Compliance Reports"],
            ['form_name'=>"form5","form_description"=>"All Compliance Index Reports"],
            ['form_name'=>"form6","form_description"=>"Add Patients"],
            ['form_name'=>"form7","form_description"=>"New Patients Reports"],
            ['form_name'=>"form8","form_description"=>"Patients Picked Up Last Month"],
            ['form_name'=>"form9","form_description"=>"Add Checking"],
            ['form_name'=>"form10","form_description"=>"Checking Reports"],
            ['form_name'=>"form11","form_description"=>"Add Near Misses"],
            ['form_name'=>"form12","form_description"=>"All Near Misses"],
            ['form_name'=>"form13","form_description"=>"Last Month Miss Reports"],
            ['form_name'=>"form14","form_description"=>"Near Miss Monthly Reports v2"],
            ['form_name'=>"form15","form_description"=>"Add Return	"],
            ['form_name'=>"form16","form_description"=>"All Return"],
            ['form_name'=>"form17","form_description"=>"Add Audit"],
            ['form_name'=>"form18","form_description"=>"All Audit"],
            ['form_name'=>"form19","form_description"=>"Add Note For Patients"],
            ['form_name'=>"form20","form_description"=>"Note For Patients Reports"],
            ['form_name'=>"form21","form_description"=>"Sms Tracking Reports"]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_tables');
    }
}
