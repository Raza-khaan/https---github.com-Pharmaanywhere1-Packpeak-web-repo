@extends('admin.layouts.mainlayout')
@section('title') <title>Patient Search</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
  
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
<style type="text/css">
	th{
		text-align:center;
	}
	
	a.dt-button.dropdown-item
	{
	text-align: center !important;
    margin: 4px !important;
    width: 46% !important;
    color: white !important;
    background-color: #007bff !important;
    border-color: #007bff !important;
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
              
			<div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      <span>
                      @if(!empty(session('admin')['name']))
                        {{session('admin')['name']}}  
                      @endif
                      </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a> -->
					  <a class="dropdown-item" href="{{url('admin/profile')}}">Profile</a>
                      <a class="dropdown-item" href="{{url('admin/changepassword')}}">Change Password</a>

                      <a class="dropdown-item" href="{{url('admin/logout')}}">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>

        <!-- Main content -->
		<section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
          <div class="row">
            
              <!-- general form elements -->
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
					</div><!-- /.box-header -->
              </div><!-- /.box -->


          </div>   <!-- /.row -->
        </section><!-- /.content -->
					
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

					
					
               



      </div><!-- /.content-wrapper -->



 

@endsection


@section('customjs')
    <script type="text/javascript">
 $(function () {

$('.table').DataTable( {
  responsive: true,
  lengthChange: true,
  language: {
       // search: '<i class="fa fa-search"></i>',
        // searchPlaceholder: "search",
        search: ""
       },

  lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
  columnDefs: [ {
    orderable: false,
    sorting: false,
    className: 'select-checkbox',
    targets:   0
   
    } ],
    select: {
        style:    'multi',
        selector: 'td:first-child'
    },
    dom: '<"top"if>Brt<"bottom"p>l',
    // dom: 'f<>Brtpl',
    buttons: [
       
        {
        extend: 'collection',
        text: 'Export',
        buttons: [
            {
            extend: 'excelHtml5',
            exportOptions: { orthogonal: 'export',
            columns: ':not(:last-child)'
              
             },
             className: 'copyButton',

            },
            {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            exportOptions: { orthogonal: 'export',
              columns: ':not(:last-child)'
            },
            className: 'copyButton'
            },
            {
            extend: 'print',
            exportOptions: { orthogonal: 'export',
              columns: ':not(:last-child)'
            },
            className: 'copyButton'
            },
            {
            extend: 'copy',
            exportOptions: { orthogonal: 'export',
              columns: ':not(:last-child)'
            },
            className: 'copyButton'
            }
            ,
            {
            extend: 'csv',
            exportOptions: { orthogonal: 'export',
              columns: ':not(:last-child)'
            },
            className: 'copyButton'
            }
        ],
        },
    ],
     //select: true,
});

});
    </script>
@endsection
