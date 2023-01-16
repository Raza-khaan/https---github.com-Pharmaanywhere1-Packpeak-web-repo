@extends('admin.layouts.mainlayout')
@section('title') <title>Pharmacy Reports</title>
@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>All Pharmacy Report</h2>
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

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">Pharmacy Report</li>
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
         <section class="content" style="background-color: #ffffff;
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

                <!-- <div class="row  text-right"  style="position: relative; BOTTOM: 20PX;">
                  <div class="col-md-12">
                    <a href="{{url('admin/pickups')}}" class="btn btn-info"> Add Pickups</a>
                  
                    <a href="{{url('admin/pickups_reports_last_month')}}" class="btn btn-info">Last Month Pickup</a>
                    
                    <a href="{{url('admin/archived_pickups_reports')}}" class="btn btn-info"> Archived Records</a>
                    
                    <a href="{{url('admin/email_pickups_report')}}" class="btn btn-info" data-toggle="modal" data-target="#emailModal"> Email Report</a>
                
                  </div>
                  
                </div>
  -->

                <div class="box-body pre-wrp-in table-responsive">
                @if(isset($all_pickups))
                    <table id="example1" class="table">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Pharmacy</th>
                          <th>User Name</th>
                          <th>Email</th>
                          <th>Status</th>
                          <th style="width: 60px;" >Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($all_pickups as $value)
                        <tr id="row_{{$value->id}}" >
                          <td></td>
                          <td>{{ucfirst($value->company_name)}}</td>
                          <td>{{ucfirst($value->username)}}</td>
                          <td>{{ucfirst($value->	email)}}</td>
                         

                          <td>

                          @if($value['status']=='1')
                        <a href="{{url('admin/status_pharmacy/0/'.$value['website_id'].'/'.$value['id'])}}"
                         style="font-size:0.7rem;color:white"  
                        class="btn btn-xs btn-success" >Active </a>
                        @else
                        <a  href="{{url('admin/status_pharmacy/1/'.$value['website_id'].'/'.$value['id'])}}"
                         style="font-size:0.7rem;color:white" class="btn btn-xs btn-danger">
                        Inactive
                        </a>
                        @endif

                          </td>
                          <td>
                          
                          <!-- <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                          <a href="javascript:void(0);" title="soft_delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                           -->
                          <a href="{{url('admin/edit_pharmacy/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a></td>
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
          if(confirm('Do you want  to soft delete this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_pickup')}}",
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
