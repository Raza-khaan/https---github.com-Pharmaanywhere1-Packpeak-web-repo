<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Store;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('deleted_by')->unsigned()->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });

       Store::insert([ 
            ['name'=>'N/A','created_by'=>'-1'],
            ['name'=>'pc','created_by'=>'-1'],
            ['name'=>'cp','created_by'=>'-1'],
            ['name'=>'bc','created_by'=>'-1'],
            ['name'=>'other','created_by'=>'-1']
         ]); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
