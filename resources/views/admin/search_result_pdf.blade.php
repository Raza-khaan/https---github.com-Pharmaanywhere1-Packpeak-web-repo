<!DOCTYPE html>
<html>
<head>
	<title>Patient Deatils</title>
     <!-- <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/> -->
    <!-- <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" /> -->  
    <!-- <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>  -->
    <style type="text/css">
    	table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: center;
		  padding: 8px;
		  font-family: arial, sans-serif;
		}

		tr:nth-child(even) {
		  /*background-color: #dddddd;*/
		  font-family: arial, sans-serif;
		}
    </style>
</head>
<body>
	  <div class="container-fluid">
	  	 <div class="row">
	  	 	<div class="col-md-offset-1 col-md-10">
                    <h2> Patient Information </h2>
					<table style="width:100%;" class="table mytable table-bordered table-striped table-hover table-condensed">
						<tbody>
							<tr>
								<th class="text-center"> First Name </th>
								<th class="text-center"> Last Name </th>
								<th class="text-center"> DOB </th>
								<th class="text-center"> Facility </th>
								<th class="text-center"> Location </th>
								<th class="text-center"> Phone </th>
								<th class="text-center"> Carer Mobile </th>
							</tr>
							<tr >
								@php 
								$m=explode(',',$patient->location);
								$locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
								@endphp
								<th class="text-center"> {{ucfirst($patient->first_name)}} </th>
								<th class="text-center"> {{ucfirst($patient->last_name)}} </th>
								<th class="text-center">{{date("j/n/Y",strtotime($patient->dob))}}</th>
								<th class="text-center"> {{ucfirst($patient->facility->name)}} </th>
								<th class="text-center"> 
								@if(isset($locations) && count($locations))
								@php $locationarray=array(); @endphp
								@foreach($locations as $row)
									@php array_push($locationarray,$row->name); @endphp 
								@endforeach
								{{implode(',',$locationarray)}}
								@endif
								</th>
								<th class="text-center"> {{$patient->phone_number}} </th>
								<th class="text-center"> {{$patient->mobile_no}} </th>
							</tr>
						</tbody>
					</table>
					<h2> Picks Up</h2>
					<table class="table mytable table-bordered table-striped table-hover table-condensed">
						<tbody>
							<tr>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Notes For Patients </th>
								<th class="text-center"> Number of Weeks </th>
								<th class="text-center"> Who is picking up? </th>
								<th class="text-center"> Carers Name </th>
								<th class="text-center"> Notes </th>
							</tr>
							@if(count($allPickup))
							@foreach($allPickup as $pickup)
							<tr>
								<th class="text-center"> {{date("j/n/Y h:i:s A",strtotime($pickup->created_at))}} </th>
								<th class="text-center"> {{$pickup->notes_from_patient}} </th>
								<th class="text-center"> {{$pickup->no_of_weeks}} </th>
								<th class="text-center"> {{ucfirst($pickup->pick_up_by)}} </th>
								<th class="text-center"> {{ucfirst($pickup->carer_name)}} </th>
								<th class="text-center"> {{$pickup->notes_from_patient}} </th>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
					<h2>Checking</h2>
					<table class="table mytable table-bordered table-striped table-hover table-condensed">
						<tbody>
							<tr>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Number of weeks </th>
							</tr>
							@if(count($allChecking))
							@foreach($allChecking as $checking)
							<tr>
								<th class="text-center"> {{date("j/n/Y h:i:s A",strtotime($checking->created_at))}}</th>
								<th class="text-center"> {{$checking->no_of_weeks}} </th>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
					<h2>Returns</h2>
					<table class="table mytable table-bordered table-striped table-hover table-condensed">
						<tbody>
							<tr>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Select Days or Weeks </th>
								<th class="text-center"> Number of Days or Weeks returned </th>
								<th class="text-center"> Strore </th>
								<th class="text-center"> Staff initials </th>
							</tr>
							@if(count($allPatientReturn))
							@foreach($allPatientReturn as $return)
							<tr>
								<th class="text-center"> {{date("j/n/Y h:i:s A",strtotime($return->created_at))}} </th>
								<th class="text-center"> {{$return->day_weeks}} </th>
								<th class="text-center"> {{$return->returned_in_days_weeks}} </th>
								<th class="text-center"> {{$return->stores->name}} {{$return->other_store?', Other :'.$return->other_store:''}} </th>
								<th class="text-center"> {{$return->staff_initials}} </th>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
					<h2> Audits </h2>
					<table class="table mytable table-bordered table-striped table-hover table-condensed">
						<tbody>
							<tr>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Number of weeks </th>
								<th class="text-center"> Store </th>
								<th class="text-center"> Staff initials </th>
							</tr>
							@if(count($allAudit))
							@foreach($allAudit as $audit)
							<tr>
								<th class="text-center"> {{date("j/n/Y h:i:s A",strtotime($audit->created_at))}} </th>
								<th class="text-center"> {{$audit->no_of_weeks}} </th>
								<th class="text-center"> {{$audit->stores->name}} </th>
								<th class="text-center"> {{$audit->staff_initials}} </th>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
					</div>
	  	 </div>
	  </div>
</body>
</html>