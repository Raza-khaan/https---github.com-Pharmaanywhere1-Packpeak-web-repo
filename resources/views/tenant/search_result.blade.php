@extends('tenant.layouts.mainlayout')
@section('title') <title>Patient Search</title>
<style type="text/css">
	th{
		text-align:center;
	}
</style>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">

 <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Patient Search Result </h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="user-menu">
              
            </div>
          </div>
        <!-- Content Header (Page header)
        <section class="content-header">
          <h1>
           Search Resultss
            <small>Preview</small>
          </h1>
          <ol class="breadcrumb" style="padding-right:200px; margin-bottom: 10px;">
            <a href="{{url('create_patient_details_pdf/'.$patient->id)}}" target="_blank" class="bg-primary" style="padding:10px;">Pdf</a>
                   <a href="javascript:void(0)" onclick="window.print()" target="_blank" class="bg-primary" style="padding:10px;">Print</a>
          </ol>
        </section> -->

        <!-- Main content -->
        <section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
          
		  <div class="row">

		  <div class="col-md-12">
		  

		  <div class="box-header pre-wrp">
                	
                	<div class="col-md-offset-1 col-md-12 table-responsive">
                   
					<div class="row">
						<div class="col-md-10">
							<h2> Patient Information</h2>
						</div>
						<div class="col-md-2">
						<a style="float:right" href="{{url('admin/search')}}" class="btn btn-primary"> <b>
						<i class="fa fa-arrow-left"></i>	
						Back</b></a>
						</div>
					</div>
					<br/>

					<table  class="table">
						<thead>
						<tr>
							<th></th>
								<th class="text-center"> First Name </th>
								<th class="text-center"> Last Name </th>
								<th class="text-center"> DOB </th>
								<th class="text-center"> Facility </th>
								<th class="text-center"> Location </th>
								<th class="text-center"> Phone </th>
								<th class="text-center"> Carer Mobile </th>
							</tr>
					</thead>
						<tbody>
							@php 
							$m=explode(',',$patient->location);
							$locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
							@endphp
							
							<tr >
								<td></td>
								<td class="text-center"> {{ucfirst($patient->first_name)}} </td>
								<td class="text-center"> {{ucfirst($patient->last_name)}} </td>
								<td class="text-center">{{date("j/n/Y",strtotime($patient->dob))}}</td>
								<td class="text-center"> {{ucfirst($patient->facility->name)}} </td>
								<td class="text-center"> 
								@if(isset($locations) && count($locations))
								@php $locationarray=array(); @endphp
								@foreach($locations as $row)
									@php array_push($locationarray,$row->name); @endphp 
								@endforeach
								{{implode(',',$locationarray)}}
								@endif
								</td>
								<td class="text-center"> {{$patient->phone_number}} </td>
								<td class="text-center"> {{$patient->mobile_no}} </td>
							</tr>
						</tbody>
					</table>

		 </div>
		  </div>
		  </div>
		  </div>
</section>
<section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);margin-top:2%">
          <div class="row">
            
              <!-- general form elements -->
			  <div class="col-md-12">
              	
              	 
                <div class="box-header pre-wrp">
                	
                	<div class="col-md-offset-1 col-md-12 table-responsive">
					<h2> Picks Up</h2>
					<br/>
					<table  class="table">
						<thead>
							<th></th>
							<th class="text-center"> Date-Time </th>
							<th class="text-center"> Notes For Patients </th>
							<th class="text-center"> Number of Weeks </th>
							<th class="text-center"> Who is picking up? </th>
							<th class="text-center"> Carers Name </th>
							<th class="text-center"> Notes </th>
						</thead>
						<tbody>
						
							@if(count($allPickup))
							@foreach($allPickup as $pickup)
							<tr>
								<td></td>
								<td class="text-center"> {{date("j/n/Y h:i:s A",strtotime($pickup->created_at))}} </td>
								<td class="text-center"> {{$pickup->notes_from_patient}} </td>
								<td class="text-center"> {{$pickup->no_of_weeks}} </td>
								<td class="text-center"> {{ucfirst($pickup->pick_up_by)}} </td>
								<td class="text-center"> {{ucfirst($pickup->carer_name)}} </td>
								<td class="text-center"> {{$pickup->notes_from_patient}} </td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
</div>
</div>
</div>
</div>

</section>
					

<!-- Main content -->
<section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);margin-top:2%">
          <div class="row">
            
              <!-- general form elements -->
			  <div class="col-md-12">
              	
              	 
                <div class="box-header pre-wrp">
                	
                	<div class="col-md-offset-1 col-md-12 table-responsive">
					<h2>Checking</h2>
					<br/>
					<table  class="table">
					<thead>
					<tr>
					<th></th>			
					<th class="text-center"> Date-Time </th>
								<th class="text-center"> Number of weeks </th>
							</tr>
					</thead>
					<tbody>
							
							@if(count($allChecking))
							@foreach($allChecking as $checking)
							<tr>
								<td></td>
								<td class="text-center"> {{date("j/n/Y h:i:s A",strtotime($checking->created_at))}}</td>
								<td class="text-center"> {{$checking->no_of_weeks}} </td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
</div>
</div>
</div>
</div>

</section>
					
				

<!-- Main content -->
<section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);margin-top:2%">
          <div class="row">
            
              <!-- general form elements -->
			  <div class="col-md-12">
              	
              	 
                <div class="box-header pre-wrp">
                	
                	<div class="col-md-offset-1 col-md-12 table-responsive">
					<h2>Returns</h2>
					<br/>
					<table  class="table">
					<thead>
					<tr>
						<th></th>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Select Days or Weeks </th>
								<th class="text-center"> Number of Days or Weeks returned </th>
								<th class="text-center"> Strore </th>
								<th class="text-center"> Staff initials </th>
							</tr>
					</thead>	
					<tbody>

							@if(count($allPatientReturn))
							@foreach($allPatientReturn as $return)
							<tr>
								<td></td>
								<td class="text-center"> {{date("j/n/Y h:i:s A",strtotime($return->created_at))}} </td>
								<td class="text-center"> {{$return->day_weeks}} </td>
								<td class="text-center"> {{$return->returned_in_days_weeks}} </td>
								<td class="text-center"> {{isset($return->stores->name)?$return->stores->name:''}} {{$return->other_store?', Other :'.$return->other_store:''}} </td>
								<td class="text-center"> {{$return->staff_initials}} </td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
</div>
</div>
</div>
</div>

</section>
					
					
					
					
					
					
<!-- Main content -->
<section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);margin-top:2%;margin-bottom:2%">
          <div class="row">
            
              <!-- general form elements -->
			  <div class="col-md-12">
              	
              	 
                <div class="box-header pre-wrp">
                	
                	<div class="col-md-offset-1 col-md-12 table-responsive">
					<h2> Audits </h2>
					<br/>
					<table  class="table">
					<thead>
					<tr>
						<th></th>
								<th class="text-center"> Date-Time </th>
								<th class="text-center"> Number of weeks </th>
								<th class="text-center"> Store </th>
								<th class="text-center"> Staff initials </th>
							</tr>
					</thead>	
					<tbody>
						
							@if(count($allAudit))
							@foreach($allAudit as $audit)
							<tr>
								<td></td>
								<td class="text-center"> {{date("j/n/Y h:i:s A",strtotime($audit->created_at))}} </td>
								<td class="text-center"> {{$audit->no_of_weeks}} </td>
								<td class="text-center"> {{$audit->stores->name}} </td>
								<td class="text-center"> {{$audit->staff_initials}} </td>
							</tr>
							@endforeach
							@endif
						</tbody>
					</table>
</div>
</div>
</div>
</div>

</section>
        </section><!-- /.content -->



      </div><!-- /.content-wrapper -->



 

@endsection


@section('customjs')
<script type="text/javascript">
 //  For   Bootstrap  datatable 
 

 //  For   Bootstrap  datatable 
 $(function () {

$('.table').DataTable( {
  lengthChange: true,
  language: {
	   // search: '<i class="fa fa-search"></i>',
		searchPlaceholder: "search",
	   },
	   sorting: false,

  lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
  columnDefs: [ {
	orderable: false,
	sorting: false,
	className: 'select-checkbox',
	targets:   0
	} ],
	select: {
		style:    'os',
		selector: 'td:first-child'
	},
	// dom: 'i<"top"B><"left"f>',
	
	dom: '<"top"if>Brt<"bottom"p>l',
	// dom: 'f<>Brtpl',
	buttons: [
	   
		{
		extend: 'collection',
		text: 'Export',
		buttons: [
			'copy',
			'excel',
			'csv',
			'pdf',
			'print',
			'pageLength','colvis'
		]
		},
	],
	 //select: true,
});

});
    </script>
@endsection
