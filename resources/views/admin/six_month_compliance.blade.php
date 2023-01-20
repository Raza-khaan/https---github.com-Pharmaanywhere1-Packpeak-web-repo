@extends('admin.layouts.mainlayout')
@section('title') <title> Six Month Compliance Report</title>

@endsection





@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Six Month Complaince Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="assets/images/user.png" alt=""> <span>Amir Eid</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" href="#">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a>
                      <a class="dropdown-item" href="#">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">General Elemenst</li>
              </ol>
            </nav>

          </div>
       
          <style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                       
/* right: -1062%;
    bot    tom: 90; */
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>

         <!-- Main content -->
         <section class="content">
          <div class="row">
          @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    <div class="preview-record">
                      <h3>All Reports For Complaince</h3>&nbsp&nbsp&nbsp&nbsp<a href="{{url('admin/pickups_reports')}}" class="btn btn-info"> Add Pickup</a><br>
                     
                      <!-- <p>Showing 8 Records out of 100</p> -->
                    </div>
                    <div class="row col-md-12" style="margin-bottom: 2%; margin-top: -2%; left: 86%;">
                    <a href="{{url('admin/archived_new_patients_report')}}" class="btn btn-info"> Archived Records</a>
</div>
                    <!-- <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                      </div>
                    </div> -->
                </div>
            
                <div class="search-logs">
                <div class="table-responsive">
            <div class="col-md-12">
                
                @if(isset($all_pickups))
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Pharmacy</th>
                          <th>Date Time</th>
                          <th>Patient</th>
                          <th>DOB</th>
                          <th>No Of Week</th>
                          <th>Not For Patients</th>
                          <th>Who is picking up?</th>
                          <th>Patients Signature</th>
                          <th>Carer`s Name</th>
                          <th>Note From Patient</th>
                          <th>Location</th>
                          <th>Facility</th>
                          <th style="width: 60px;" >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($all_pickups as $value)
                        @php 
                        $m=explode(',',$value->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                        <tr id="row_{{$value->id}}" >
                          <td></td>
                          <td>{{ucfirst($value->pharmacy)}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          <td>{{$value->no_of_weeks}}</td>
                          <td></td>
                          <td>{{$value->pick_up_by}}</td>
                          <td><img src="{{$value->patient_sign}}" style="height:45px;  width:100px; "/></td>
                          <td>{{$value->carer_name}}</td>
                          <td>{{$value->notes_from_patient}}</td>
                          <td>
                            @if(isset($locations) && count($locations))
                              @php $locationarray=array(); @endphp
                               @foreach($locations as $row)
                                 @php array_push($locationarray,$row->name); @endphp 
                               @endforeach
                              {{implode(',',$locationarray)}}
                            @endif
                          </td>
                          <td>{{strtoupper($value->facility)}}</td>
                          <td>
                          <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                           <a href="{{url('admin/edit_pickup/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a></td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @else
                    <h5 class="box-title text-danger">There is no data.</h3>
                  @endif
                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


@endsection


@section('customjs')

<script type="text/javascript">
      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').DataTable( {
          lengthChange: true,
          language: {
               // search: '<i class="fa fa-search"></i>',
                searchPlaceholder: "search",
               },

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
                    'pageLength','colvis',
                    'pageLength','colvis'
                ]
                },
            ],
             //select: true,
        });
        
      });



       function delete_record(website_id,rowId)
      {
          if(confirm('Do you want  to  delete this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/delete_pickup')}}",
                  data: {'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row deleted...</span>');
                      }
                      else{ 
                        $('.alertmessage').html('<span class="alert alert-danger">Somthing event wrong!...</span>'); 
                        }
                  }
              });
          }
      }

    </script>
@endsection
