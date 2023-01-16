@extends('admin.layouts.mainlayout')
@section('title') <title>All Logs</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
  
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
  
  <!-- <script>
      $(document).ready(function() {
      var table = $('#example').DataTable( {
          lengthChange: false,
          //"dom": 'lrtip',
          buttons: [ 
          {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'pdf',
                    'colvis'
                ]
            }]
      } );
  
      table.buttons().container()
          .appendTo( '#example_wrapper .col-md-6:eq(0)' );
  
  } );
  </script> -->
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>All Logs</h2>
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
                <li class="breadcrumb-item active" aria-current="page">All Logs</li>
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
         <section class="report-forms">
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
                  <!-- <h3 class="box-title">All Logs</h3> --> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                 
              <div class="search-logs">
                <div class="table-responsive">
                    @if(isset($allLogs))
                    <table id="example1" class="table" >
                      <thead>
                        <tr>
                          <th></th>
                          
                          <th scope="col">Pharmacy</th>
                          <th scope="col">User</th>
                          <th scope="col">Action</th>
                          <th scope="col">Date Time</th>
                          <th scope="col">IP Address</th>
                          <!-- <th scope="col">Action</th> -->
                          
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($allLogs as $value)
                       
                        
                        <tr id="" >
                          <td></td>
                           
                           <td>{{isset($value->pharmacy_name) ?ucfirst($value->pharmacy_name):''}}</td>
                           <td>{{isset($value->userdata->name)?$value->userdata->name:'Super Admin'}}</td>
                           <td>
                           @if($value->action=='1')
                            <p class="lgout">Create</p>
                            <p class="login">{{$value->action_detail}}</p>
                           @elseif($value->action=='2')
                           <p class="text-info">Update</p>
                           <p class="text-info">{{$value->action_detail}}</p>
                           @elseif($value->action=='3')
                            <p class="lgout">Delete</p>
                            <p class="lgout">{{$value->action_detail}}</button>
                           @elseif($value->action=='4')
                           <p class="login">Login</p>
                           @elseif($value->action=='5')
                            <button type="submit" class="btn btn-xs btn-info">Logout</button>
                           @elseif($value->action=='6')
                            <button type="submit" class="btn btn-xs btn-success">On</button>
                            <button type="submit" class="btn btn-xs btn-primary">{{$value->action_detail}} Form</button>
                           @elseif($value->action=='7')
                            <button type="submit" class="btn btn-xs btn-danger">Off</button>
                            <button type="submit" class="btn btn-xs btn-primary">{{$value->action_detail}} Form</button>
                           @endif
                          
                           <!-- {{isset($value->Patientdata->first_name)?' For '.ucfirst($value->Patientdata->first_name.' '.$value->Patientdata->last_name):''}}  -->
                           </td>
                          <td>{{isset($value->created_at)?date("j/n/Y, h:i A",strtotime($value->created_at)):''}}</td>
                          <td>{{$value->ip_address?$value->ip_address:''}}</td>

                          <!-- <td><a style="color:#007bff;cursor:pointer"><i class="fa fa-edit"></i></a>
                        <a  style="color:red;cursor:pointer">  <i class="fa fa-trash"></i> </a>
                        </td> -->
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


    </script>
@endsection
