@extends('admin.layouts.mainlayout')
@section('title') <title>All Near Miss</title>


  <style>
#example1_wrapper
{
  margin-top:15px;
}
</style>

@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Archived Near Miss Report</h2>
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
  <div class="col-md-9">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Forms</li>
      <li class="breadcrumb-item active" aria-current="page">General Forms</li>
    </ol>
  </div>

  <div class="col-md-3">
  <a style="float:right" href="{{url('admin/all_near_miss')}}" class="btn btn-primary" style="margin-bottom: 2%;"> All Records</a>
                      
                        <!-- <a href="{{url('admin/email_new_patients_report')}}" class="btn btn-primary" data-toggle="modal" data-target="#emailModal"> Email Report</a> -->
  
  </div>
</div>    


  
  </nav>

</div>
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
                
                <div class="box-body pre-wrp-in table-responsive">
                <div class="pull-right alertmessage"></div>

                @if(isset($all_missed_patients))
                  <table id="example1" class="table" style="margin-top:15px">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Pharmacy</th>
                        <th>Date Time</th>
                        <th>Person Involved</th>
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
                        <td>{{$value->person_involved}}</td>
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
                        <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                        </td>
                        
                        <!-- <td>
                        <a href="javascript:void(0)" onclick="view_info('Near Miss Overview','{{$value->website_id}}','{{$value->id}}','near_miss_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                        &nbsp;&nbsp;
                        <a href="javascript:void(0);" title="delete" onclick="delete_archived_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                         
                        <a href="{{url('admin/edit_near_miss/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a></td> -->
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


    <script type="text/javascript">
      


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
          if(confirm('Do you want  to  unarchive this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/soft_delete_near_miss')}}",
                  data: {'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row unarchived...</span>');
                      }
                      else{ 
                        $('.alertmessage').html('<span class="alert alert-danger">Somthing event wrong!...</span>'); 
                        }
                  }
              });
          }
      }
      function delete_archived_record(website_id,rowId)
      {
          if(confirm('Do you want  to  permanently delete this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/delete_archived_near_miss')}}",
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
