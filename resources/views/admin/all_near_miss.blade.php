@extends('admin.layouts.mainlayout')
@section('title') <title>All Near Miss</title>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->
        


        <div class="content-wrapper">
        <div class="dash-wrap">
        <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>All Near Miss</h2>
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
          
            <a href="{{url('admin/near_miss')}}" class="btn btn-primary"> Add Near Miss</a>
            <a href="{{url('admin/archived_all_near_miss')}}" class="btn btn-primary"> Archived Records</a>
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



        <!-- Content Header (Page header) -->
        
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
            </div>
          <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    
                    
                  <div class="pull-right alertmessage"></div>
                <div class="search-logs" style="margin-top:15px">
                <div class="table-responsive">
            <div class="col-md-12">

               @if(isset($all_missed_patients))
                  <table id="example1" class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Pharmacy</th>
                        <th>Date Time</th>
                        <!-- <th>Person Involved</th> -->
                        <!-- <th>Missed Tablet</th>
                        <th>Extra Tablet</th>
                        <th>Wrong Tablet</th>
                        <th>Wrong Day</th> -->
                        <th>Error Type</th>
                        <th style="width: 60px;" >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($all_missed_patients as $value)
                      <tr id="row_{{$value->id}}">
                        <td></td>
                        <!-- <td><input type="checkbox" class="checkbox" data-id="{{$value->id}}" website-id="{{$value->website_id}}"></td> -->
                        <td>{{ucfirst($value->pharmacy)}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                        <!-- <td>{{$value->person_involved}}</td> -->
                        <td>
                            @if($value->missed_tablet!=NULL) 
                              <input type="checkbox" name="missed_tablet" checked disabled> Missed Tablet 
                            @endif
                            @if($value->extra_tablet!=NULL) 
                              <input type="checkbox" name="extra_tablet" checked disabled> Extra Tablet 
                            @endif
                            @if($value->wrong_tablet!=NULL) 
                              <input type="checkbox" name="wrong_tablet" checked disabled> Wrong Tablet 
                            @endif
                            @if($value->wrong_day!=NULL) 
                              <input type="checkbox" name="wrong_day" checked disabled> Wrong Day 
                            @endif
                            @if($value->other!=NULL) 
                              <input type="checkbox" name="wrong_day" checked disabled> {{$value->other}}
                            @endif
                            
                        </td>
                        <td>
                        <!-- <a href="javascript:void(0)" onclick="view_info('Near Miss Overview','{{$value->website_id}}','{{$value->id}}','near_miss_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                        &nbsp;&nbsp; -->
                        <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                        
                        <a href="{{url('admin/edit_near_miss/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a></td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @else
                  <h5 class="box-title text-danger">There is no data.</h3>
                  @endif
                  
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <!-- <div class="row">
                      <div class="col-md-offset-10 col-md-2">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#SummaryModal"><b>Missed Summary</b></a>
                      </div>
                  </div> -->
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
                            <form action="/admin/email_nearmiss_report" method="POST">
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


      <!-- Modal for All Summary -->
  <div class="modal fade" id="SummaryModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content" style="height:230px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title " style="font-size:18px; font-weight:bold;" > <i class="fa fa-file-o"></i> Summary</h5>
        </div>
        <div class="modal-body">
           <table class="table">
             <tr>
               <th>MissedTablet</th>
               <td>{{$allMissedTablet}}</td>
             </tr>
             <tr>
               <th>ExtraTablet</th>
               <td>{{$allExtraTablet}}</td>
             </tr>
             <tr>
               <th>WrongTablet</th>
               <td>{{$allWrongTablet}}</td>
             </tr>
             <tr>
               <th>WrongDay</th>
               <td>{{$allWrongDay}}</td>
             </tr>

           </table>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>


@endsection


@section('customjs')


    <!-- <script type="text/javascript">
      //  For   Bootstrap  datatable 
      $(function () {
          // CheckBox 
          $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
          });

        // Data atable With Export Button  
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
                    'pageLength','colvis'
                ]
                },
            ],
             //select: true,
        });
        
      }); -->

      <script type="text/javascript">
      
      });

      function delete_record(website_id,rowId)
      {
          if(confirm('Do you want  to  delete this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/delete_near_miss')}}",
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
                  url: "{{url('admin/soft_delete_near_miss')}}",
                  data: {archivetypeid:1,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row Archived...</span>');
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
