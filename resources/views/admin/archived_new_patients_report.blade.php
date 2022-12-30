@extends('admin.layouts.mainlayout')
@section('title') <title>New Patients Report</title>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 
 <div class="content-wrapper">
        <div class="dash-wrap">
        <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Archived New Patients Report</h2>
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
  <div id="alertdelete" style="display:none" class="alert alert-danger" role="alert">
    Record Deleted
  </div>
</div>
  <div class="col-md-3">
  <a style="float:right" href="{{url('admin/new_patients_report')}}" class="btn btn-primary" style="margin-bottom: 2%;"> All Records</a>
                      
                        <!-- <a href="{{url('admin/email_new_patients_report')}}" class="btn btn-primary" data-toggle="modal" data-target="#emailModal"> Email Report</a> -->
  
  </div>
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
                  
                  
                  
                </div><!-- /.box-header -->
                <div class="search-logs">
                <div class="table-responsive">
                  <div class="col-md-12">
                  @if(isset($new_patients))
                    <table id="example1" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Pharmacy</th>
                          <th>Date Time</th>
                          <th>Patient Name</th>
                          <th>DOB</th>
                          <th>Facility</th>
                          <th>Location</th>
                          <th>Address</th>
                          <th>Mobile Number</th>
                          <!-- <th>Get a text when picked up/Delivered </th> -->
                          <th>Mobile</th>
                          <th style="width: 60px;" >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($new_patients as $value)
                        @php
                        $m=explode(',',$value->locations);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp

                        @if($value->is_archive == 1)
                        <tr id="row_{{$value->id}}" >
                           <td></td>
                          <td>{{ucfirst($value->pharmacy)}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          
                          <td></td> 


                          <td>
                           @if(isset($locations) && count($locations))
                              @php $locationarray=array(); @endphp
                              @foreach($locations as $row)
                                @php array_push($locationarray,$row->name); @endphp
                              @endforeach
                              {{implode(',',$locationarray)}}
                            @endif
                          </td>
                          
                          <td>{{ucfirst($value->address)}}</td>
                          <td>{{$value->phone_number}}</td>
                          <!-- <td>@if($value->text_when_picked_up_deliver==NUll) False @else True @endif</td> -->
                          <td>{{$value->mobile_no}}</td>
                          <td>
                          <!-- <a href="javascript:void(0)" onclick="view_info('Patients Info','{{$value->website_id}}','{{$value->id}}','patient_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                          &nbsp;&nbsp; -->
                          <!-- <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>
                            &nbsp;&nbsp; -->
                            <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-success"></i>
                            &nbsp;&nbsp;
                          <!-- <a href="{{url('admin/edit_patient/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a></td> -->
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
                        $('.alertmessage').html('<span class="text-success">Row deleted...</span>');
                      }
                      else{
                        $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>');
                        }
                  }
              });
          }
      }

      function soft_delete_record(website_id,rowId)
      
      {


          if(confirm('Do you want  to unarchive  this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_patient')}}",
                  data: {archivetypeid:0,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        
                        // $('.alertmessage').html('<span class="text-success">Row deleted...</span>');
                        $("#alertdelete").fadeIn();
                      $('#alertdelete').html('<span class="text-danger">Record Unarchived...</span>');
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


    </script>
@endsection
