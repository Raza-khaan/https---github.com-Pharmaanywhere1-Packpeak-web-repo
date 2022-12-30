@extends('admin.layouts.mainlayout')
@section('title') <title>All Checking Report</title>

<style>
  div#example1_wrapper {
    margin-top: 15px;
}
  </style>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
      
 <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Archived Checking Report</h2>
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
  <div class="col-md-8">
  <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Forms</li>
      <li class="breadcrumb-item active" aria-current="page">General Forms</li>
    </ol>
  </div>

  <div class="col-md-4">
  <a style="float:right" href="{{url('admin/checkings_report')}}" class="btn btn-primary" style="margin-bottom: 2%;"> All Records</a>            
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

                @if(isset($all_checkings))
                <table id="example1" class="table" style="margin-top:15px">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Pharmacy</th>
                        <th>Date Time</th>
                        <th>Patient Name</th>
                        <th>Patient Signature</th>
                        <th>Location</th>
                        <th>Number of weeks</th>
                        <th>Note For Patients</th>
                        <!-- <th>Facility</th> -->
                        <th>DD</th>
                        <th  >Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($all_checkings as $value)
                        @php 
                        $m=explode(',',$value->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                      <tr id="row_{{$value->id}}">
                        <td></td>
                        <!-- <td><input type="checkbox" class="checkbox" data-id="{{$value->id}}" website-id="{{$value->website_id}}"></td> -->
                        <td>{{ucfirst($value->pharmacy)}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                        <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                        <td>
                        <img  src="{{$value->pharmacist_signature}}" style="height:80px; "/>
                        </td>
                        <td>
                        @if(isset($locations) && count($locations))
                            @php $locationarray=array(); @endphp
                             @foreach($locations as $row)
                               @php array_push($locationarray,$row->name); @endphp 
                             @endforeach
                            {{implode(',',$locationarray)}}
                          @endif 
                        </td>
                        <td>{{$value->no_of_weeks}}</td>
                        <td>{{$value->note_from_patient}}</td>
                        <!-- <td>{{$value->store}}</td> -->
                        <td>{{$value->dd?'true':'false'}}</td>
                        <td>
                        <!-- <a href="javascript:void(0)" onclick="view_info('Checking Overview','{{$value->website_id}}','{{$value->id}}','checking_info')" title="View info" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> Info</a>
                          &nbsp;&nbsp; -->
                        <!-- <a href="javascript:void(0);" title="delete" onclick="delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp; -->
                        <a href="javascript:void(0);" title="soft delete" onclick="soft_delete_record('{{$value->website_id}}','{{$value->id}}');" ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                        
                        <!-- <a href="{{url('admin/edit_checking/'.$value->website_id.'/'.$value->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a></td> -->
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
                  url: "{{url('admin/delete_checking')}}",
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
                  url: "{{url('admin/soft_delete_checking')}}",
                  data: {archivetypeid:0,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row Unarchived...</span>');
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
