@extends('admin.layouts.mainlayout')
@section('title') <title>Pickups Reports</title>
@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>All Pickups Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>

            <div class="pharma-add report-add">
             <a href="{{url('admin/pickups')}}"class="active family">Add Pickup</a>
            </div>

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

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">
          <nav class="dash-breadcrumb" aria-label="breadcrumb" style="width:100%">
          <div class="row">
            <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">General Forms</li>
              </ol>
            </div>

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
         <section class="content" style="background-color: #ffffff;margin-top:10px;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
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


              <div class="box">
                <div class="box-header">
                  
                  <div class="pull-right alertmessage"></div>
                   
                  
                </div><!-- /.box-header -->

               <div class="row">
               <div class="col-md-3 ">
            <a href="{{ url('admin/pickups_reports_excel')}}"  style="padding: 7px !important;" class="btn btn-success"> Export Excel</a>
                  <!-- <a href="#"  class="btn btn-success"  onclick="export_pdf()"> Export PDF</a>  -->
                   <a href="{{ url('admin/pickups_reports_pdf')}}"  style="padding: 7px !important;"  class="btn btn-success"> Export PDF</a> 
            
            </div>
            <div class="col-md-9 text-right" >
            <a href="{{url('admin/pickups')}}" class="btn btn-primary"> Add Pickups</a>
                  
                  <a href="{{url('admin/pickups_reports_last_month')}}" class="btn btn-primary">Last Month Pickup</a>
                  
                  <a href="{{url('admin/archived_pickups_reports')}}" class="btn btn-primary"> Archived Records</a>
                  <!-- <a href="{{ url('admin/pickups_reports_excel')}}" class="btn btn-primary"> Export Excel</a> -->
                  <!-- <a href="#"  class="btn btn-success"  onclick="export_pdf()"> Export PDF</a>  -->
                   <!-- <a href="{{ url('admin/pickups_reports_pdf')}}"  class="btn btn-primary"> Export PDF</a>  
                  <a href="{{url('admin/email_pickups_report')}}" class="btn btn-primary" data-toggle="modal" data-target="#emailModal"> Email Report</a> -->
              
            
              </div>
          </div> 

               </div>
 

                <div class="box-body pre-wrp-in table-responsive" style="margin-top:15px">
                @if(isset($all_pickups))
                    <table id="example1" class="table" >
                      <thead>
                        <tr>
                          <th></th>
                          <th>Pharmacy</th>
                          <th>Date Time</th>
                          <th>Patient</th>
                          <th>DOB</th>
                          <th>No Of Week</th>
                          <!-- <th>Not For Patients</th> -->
                          <th>Who is picking up?</th>
                          <th>Patients Signature</th>
                          <th>Pharmacy Signature</th>
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
                          $location_ids=explode(',',$value->location);
                          $locations=App\Models\Admin\Location::select('name')->whereIn('id', $location_ids)->get();
                        @endphp
                        <tr id="row_{{$value->id}}" >
                          <td></td>
                          <td>{{ucfirst($value->pharmacy)}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          <td>{{$value->no_of_weeks}}</td>
                          <!-- <td></td> -->
                          <td>{{ucfirst($value->pick_up_by)}}</td>
                          <td><img src="{{$value->patient_sign}}" style="height:45px;  width:100px; "/></td>
                          <td><img src="{{$value->pharmacist_sign}}" style="height:45px;  width:100px; "/></td>
                          <td>{{ucfirst($value->carer_name)}}</td>
                          <td>{{substr($value->notes_from_patient,0,30)}}</td>
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
                          <!-- <a href="javascript:void(0)" onclick="view_info('Pickup Overview','{{$value->website_id}}','{{$value->id}}','pickup_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                          &nbsp;&nbsp; -->
                          <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                          <a href="javascript:void(0);" title="soft_delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                          
                          <a href="{{url('admin/edit_pickup/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a></td>
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
          <!-- Modal -->
                    <div id="emailModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          
                          <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <br>
                            <form action="/admin/email_pickup_report" method="POST">
                              @csrf
                              <div > 
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                              </div>
                              <div > 
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                              </div>
                              <div > 
                                <label>E-Mail</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
                              </div>
                              <div align="center"> 
                                <br>
                                <input type="submit" class="btn btn-info" value="Send Mail">
                              </div>
                            </form>
                            
                          </div>
                           
                        </div>
                        <!-- modal end -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


@endsection


@section('customjs')

<script type="text/javascript">
      

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
      function soft_delete_record(website_id,rowId)
      {
        console.log('Hello');
          if(confirm('Do you want  to soft archive this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_pickup')}}",
                  data: {archivetypeid:1,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Record Archived...</span>');
                      }
                      else{ 
                        $('.alertmessage').html('<span class="alert alert-danger">Somthing event wrong!...</span>'); 
                        }
                  }
              });
          }
      }
    
function export_pdf()
{
  alert('as');
  $('#example1').tableExport({
  type: 'pdf',
  pdfmake: { enabled: true }
});
}

   

    </script>
@endsection
