<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class CommonController extends Controller
{
    public function __construct(){
        $host=explode('.',request()->getHttpHost());
        //dd($host[0]);
        config(['database.connections.tenant.database' => $host[0]]);
        DB::purge('tenant');
        DB::reconnect('tenant');
        DB::disconnect('tenant'); 
    }

    // testPage for testing purpose
    public  function testPage(){
        return view('tenant.testPage');
    }
    
    //multidelete start
    public  function  deleteAll(Request $request,$db,$modelName)
    {
        $modelNam='\App\Models\Tenant\\'.$modelName;
        if($modelNam::count()){
            $deleteIds=$request->get('deleteIds');
            // $modelNam::whereIn('id',$deleteIds)->delete();
            $modelNam::destroy($deleteIds);
            return 200;
        }
        return 400;
    }
    //multi archive start
    public  function  archiveAll(Request $request,$db,$modelName)
    {
        $modelNam='\App\Models\Tenant\\'.$modelName;
        if($modelNam::count()){
            $deleteIds=$request->get('deleteIds');
            $modelNam::whereIn('id',$deleteIds)->update(['is_archive'=>1]);
            //$modelNam::whereIn('id',$deleteIds)->delete();
            return 200;
        }
        return 400;
    }
    //multi un-archive start
    public  function  unarchiveAll(Request $request,$db,$modelName)
    {
        $modelNam='\App\Models\Tenant\\'.$modelName;
        if($modelNam::count()){
            $deleteIds=$request->get('deleteIds');
            $modelNam::whereIn('id',$deleteIds)->update(['is_archive'=>0]);
            return 200;
        }
        return 400;
    }



}
