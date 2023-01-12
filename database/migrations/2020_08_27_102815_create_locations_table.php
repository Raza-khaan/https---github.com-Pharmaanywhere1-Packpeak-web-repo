<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Location;
class CreateLocationsTable extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->bigInteger('status')->unsigned()->default('1');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });

        Location::insert([ 
            ['name'=>'shelf','created_by'=>'-1'],
            ['name'=>'fridge','created_by'=>'-1'],
            ['name'=>'safe','created_by'=>'-1']
         ]);
    }


    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
