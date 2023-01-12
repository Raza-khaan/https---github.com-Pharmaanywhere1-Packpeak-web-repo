<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Reports extends Controller
{
    public function reportsGeneration(String $report, String $id)
    {
        $all_pharmacy = User::all();
        $newarray = array();
        foreach ($all_pharmacy as $row) {
            $this->get_connection($row->website_id);
            $get_audit = Patient_Model::all();
            foreach ($get_audit as $col) {
                $Patientlocations = PatientLocation::where('patient_id', $col->id)->orderBy('created_at', 'desc')->first();
                $col->pharmacy = $row->company_name;
                if (!empty($Patientlocations)) {
                    $col->locations = $Patientlocations->locations;
                }

                $newarray[] = $col;
            }
            DB::disconnect('tenant');
        }
        $data['new_patients'] = $newarray; 
        

        return view('admin.reports',compact('report','data'))
    }
}
