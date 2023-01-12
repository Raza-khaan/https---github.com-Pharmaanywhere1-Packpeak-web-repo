@extends('admin.layouts.mainlayout')
@section('title') <title>All Returns</title>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <div class="dash-wrap">
        <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>All Returns</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="pharma-add report-add">
            <a href="{{url('admin/all_returns')}}"class="active family">Add Patient</a>
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
            <div class="col-md-7">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">General Forms</li>
              </ol>
            </div>

            <div class="col-md-5 text-right">
          
            <a href="{{url('admin/returns')}}" class="btn btn-primary"> Add Returns</a>
                        <a href="{{url('admin/archived_all_returns')}}" class="btn btn-primary"> Archived Records</a>
                        <!-- <a href="#" class="btn btn-info" data-toggle="modal" data-target="#emailModal"> Email Report</a> -->
                  
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
            <div class="box-header">
                  <!-- <h3 class="box-title">All Logs</h3> --> 
                </div><!-- /.box-header -->
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
            
              <div class="pull-right alertmessage"></div>
                 
              <div class="search-logs" style="margin-top:15px">
                <div class="table-responsive">
                @if(isset($all_returns))
                  <table id="example1" class="table">
                    <thead>
                      <tr>
                        <th scope="col"></th>
                        <th scope="col">Pharmacy</th>
                        <th scope="col">Date Time</th>
                        <th scope="col">Patient Name</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Select Days or Weeks</th>
                        <th scope="col">Days/Weeks Return</th>
                        <th scope="col">Store</th>
                        <th scope="col">Other Store</th>
                        <th scope="col">Staff initials</th>
                        <th scope="col" style="width: 60px;" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($all_returns as $value)
                      <tr id="row_{{$value->id}}">
                         <td></td>
                        <!-- <td><input type="checkbox" class="checkbox" data-id="{{$value->id}}" website-id="{{$value->website_id}}"></td> -->
                        <td>{{ucfirst($value->pharmacy)}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                        <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                        <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                        <td>{{ucfirst($value->day_weeks)}}</td>
                        <td>{{$value->returned_in_days_weeks}}</td>
                        <td>{{$value->store}}@if(isset($value->other_store))@endif</td>
                        <td>{{$value->other_store}}</td>
                        <td>{{$value->staff_initials}}</td>
                        <td>
                          <!-- <a href="javascript:void(0)" onclick="view_info('Returns Overview','{{$value->website_id}}','{{$value->id}}','return_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                          &nbsp;&nbsp; -->
                        <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');"  ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');"  ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                        
                        <a href="{{url('admin/edit_return/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a></td>
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
                            <form action="/admin/email_return_report" method="POST">
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
                  url: "{{url('admin/delete_return')}}",
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
          if(confirm('Do you want  to  soft delete this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_return')}}",
                  data: {archivetypeid:1,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row archived...</span>');
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
