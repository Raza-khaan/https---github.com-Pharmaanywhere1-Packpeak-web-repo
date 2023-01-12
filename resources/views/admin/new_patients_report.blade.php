@extends('admin.layouts.mainlayout')
@section('title') <title>New Patients Report</title>
@endsection
@section('content')
<style type="text/css">

</style>
 <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->
        


        <div class="content-wrapper">
        <div class="dash-wrap">
        <div class="dashborad-header">

     

            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Patient Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>

            <div class="pharma-add report-add">
            <a href="{{url('admin/patients')}}"class="active family">Add Patient</a>
           
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

          
        <div class="reports-breadcrum m-0">

<nav class="dash-breadcrumb" aria-label="breadcrumb" style="width:100%">
<div class="row">
  <div class="col-md-4">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Forms</li>
      <li class="breadcrumb-item active" aria-current="page">General Forms</li>
    </ol>
  </div>
  <div class="col-md-5" >
  <div id="alertdelete" style="display:none;height:44px" class="alert alert-danger" role="alert">
    Record Deleted
  </div>

  
</div>

  <div class="col-md-3">
  <a style="float:right" href="{{url('admin/archived_new_patients_report')}}" class="btn btn-primary"> Archived Records</a>
                      
                        <!-- <a href="{{url('admin/email_new_patients_report')}}" class="btn btn-primary" data-toggle="modal" data-target="#emailModal"> Email Report</a> -->
  
  </div>
</div>    


  
  </nav>

</div>
       

          <div class="pharma-register">
              <h2>Search Results</h2>
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
          <div >
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
            </div><!-- /.box-header -->
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    
 
                    <!-- Modal -->
                    <div id="emailModal" class="modal fade" role="dialog">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          
                          <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <br>
                            <form action="/admin/email_new_patients_report" method="POST">
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
                      </div>
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
              <div class="pull-right alertmessage"></div>
                <div class="table-responsive">
            <div class="col-md-12">
                  @if(isset($new_patients))
                    <table id="example1" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          
                          <th>Pharmacy</th>
                          <th>Date</th>
                          <th>Name</th>
                          <th>DOB</th>
                          <th>Facility</th>
                          <th>Location</th>
                          <!-- <th>Address</th> -->
                          <th>Phone#</th>
                          <!-- <th>Get a text</th> -->
                          <th>Mobile#</th>
                          <th style="width: 60px;" class='notexport' >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($new_patients as $value)
                        @php
                        $m=explode(',',$value->locations);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp

                        @if($value->is_archive ==0)
                        <tr id="row_{{$value->id}}" >
                           <td></td>
                        
                           <td>{{ucfirst($value->pharmacy)}}</td>
                           <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                           <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                           <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                           <td>{{isset($value->facility)?strtoupper($value->facility->name):''}}</td>
                           <td>
                            @if(isset($locations) && count($locations))
                               @php $locationarray=array(); @endphp
                               @foreach($locations as $row)
                                 @php array_push($locationarray,$row->name); @endphp
                               @endforeach
                               {{implode(',',$locationarray)}}
                             @endif
                           </td>
                           <!-- <td>{{ucfirst($value->address)}}</td> -->
                           <td>{{$value->phone_number}}</td>
                           <!-- <td>@if($value->text_when_picked_up_deliver==NUll) False @else True @endif</td> -->
                           <td>{{$value->mobile_no}}</td>
                           <td class='notexport'>
                           <!-- <a href="javascript:void(0)" onclick="view_info('Patients Info','{{$value->website_id}}','{{$value->id}}','patient_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                           &nbsp;&nbsp; -->
                           <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>
                             &nbsp;&nbsp;
                             <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-info"></i>
                             &nbsp;&nbsp;
                           <a href="{{url('admin/edit_patient/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a></td>
                          </td>
                         </tr>
                        @endif
                        
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
    

      function delete_record(website_id,rowId)
      {
          if(confirm('Do you want  to  delete this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/delete_patient')}}",
                  data: {'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        // $('.alertmessage').html('<span class="text-success">Row deleted...</span>');
                      $("#alertdelete").fadeIn();
                      $('#alertdelete').html('<span class="text-danger">Record Deleted...</span>');
                      $("#alertdelete").delay(5000).fadeOut(800);
                      }
                      else{
                        // $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>');

                        $("#alertdelete").fadeIn();
                      $('#alertdelete').html('<span class="text-danger">Somthing event wrong!...</span>');
                      $("#alertdelete").delay(5000).fadeOut(800);
                        }
                  }
              });
          }
      }

      function soft_delete_record(website_id,rowId)
      {
          if(confirm('Do you want  to soft delete this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_patient')}}",
                  data: {archivetypeid:1,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        // $('.alertmessage').html('<span class="text-success">Row deleted...</span>');
                        $("#alertdelete").fadeIn();
                      $('#alertdelete').html('<span class="text-danger">Record Archived...</span>');
                      $("#alertdelete").delay(5000).fadeOut(800);
                      }
                      else{
                        $("#alertdelete").fadeIn();
                      $('#alertdelete').html('<span class="text-danger">Something went wrong...</span>');
                      $("#alertdelete").delay(5000).fadeOut(800);
                        }
                  }
              });
          }
      }


    </script>
@endsection
