@extends('admin.layouts.mainlayout')
@section('title') <title>Users/Admin List</title>

@endsection
<head>
<style>
  .btn-group, .btn-group-vertical {
    flex-direction: column !important;
}
.dt-buttons button {
    background: rgb(192, 229, 248) !important;
    border-color: rgb(255, 255, 255) !important;
    color: #1f89bb;
}
  </style>
</head>

@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <div class="pharma-add report-add">
              <a href="{{ url('admin/all_technician') }}" class="active">All Users</a>
              <a href="{{ url('admin/technician') }}">New User</a>
            </div>
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

          <div class="pharma-add pharma-add-mobile">
              <a href="{{ url('admin/all_technician') }}" class="active">All Users</a>
              <a href="{{ url('admin/technician') }}">New User</a>
          </div>  


         <!-- Main content -->
         <section class="content">
          <div style="width:100%">
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
                  <!-- <h3 class="box-title">All Users List <i class="fa fa-hospital-o"></i> </h3> <div class="pull-right alertmessage"></div>
                </div> /.box-header -->

                <div class="reports-breadcrum m-0">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a>Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Forms</li>
      <li class="breadcrumb-item active" aria-current="page">General Forms</li>
    </ol>
  </nav>

</div>
                <div class="box-body pre-wrp-in table-responsive" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
                    @if(count($all_technician))
               <table id="example1" class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Pharmacy</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Initials Name</th>
                        <th>Registration</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($all_technician as $row)

                      <tr id="row_{{$row['id']}}">
                        <td></td>
                        <td>{{ucfirst($row['pharmacy'])}}</td>
                        <td>{{$row['name']}} <small>({{ucfirst($row['roll_type'])}})</small></td>
                        <td>{{$row['email']}}</td>
                        <td>{{$row['initials_name']}}</td>
                        <td>{{$row['registration_no']}}</td>
                        <td>{{$row['phone']}}</td>
                        <!-- <td>{{$row['address']}}</td> -->

                        <td>

                        @if($row['status']=='1')
                        <a href="{{url('admin/status_technician/0/'.$row['website_id'].'/'.$row['id'])}}"
                         style="font-size:0.7rem;color:white"  
                        class="btn btn-xs btn-success" >Active </a>
                        @else
                        <a  href="{{url('admin/status_technician/1/'.$row['website_id'].'/'.$row['id'])}}"
                         style="font-size:0.7rem;color:white" class="btn btn-xs btn-danger">
                        Inactive
                        </a>
                        @endif
            


                        </td>
                        <td>

                        <a href="javascript:void(0);" title="Delete" onclick="delete_record('{{$row["website_id"]}}','{{$row["id"]}}');"  ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('admin/edit_technician/'.$row['website_id'].'/'.$row['id'])}}" title="edit"><i class="fa fa-edit text-primary"></i></a>
                      </td>

                        </td>
                      </tr>
                      @endforeach

                    </tbody>

                  </table>
                  @else
                   <div class="text-center text-danger"><span>There are no data.</span></div>
                  @endif




                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


<!-- Modal  From Extends validity -->
<div id="extends_plan_Modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Extends validity Plan</h4>
      </div>
      <div class="modal-body">
        <form action="{{url('admin/update_validity')}}"  method="post" >
            {{ csrf_field() }}
         <div class="row">
             <div class="col-sm-4">
                <div class="form-group">
                    <label>Parmacy</label>
                    <input type="hidden" name="website_id"  id="website_id">
                    <p class="pharmacy_name"></p>
                </div>

             </div>
             <div class="col-sm-4">
               <div class="form-group">
                    <label>Registration</label>
                    <p class="pharmacy_registration_number"></p>
                </div>
             </div>
             <div class="col-sm-4">
                <div class="form-group">
                   <label>Extends validity (days)</label>
                   <input type="text" name="plan_validity" id="plan_validity" required="required" onkeypress="return restrictAlphabets(event);" maxlength="10" class="form-control" placeholder="validity">
                </div>
             </div>
             <div class="col-sm-offset-8 col-sm-4">
                 <button type="submit" class="btn btn-primary btn-block" >Submit</button>
             </div>
         </div>
        </form>
      </div>

    </div>

  </div>
</div>



@endsection


@section('customjs')


    <script type="text/javascript">

      /*Extends Validity  of Subcription plan */
       function extends_validity(website_id,company_name,registration_no)
       {
          $('#website_id').val(website_id);
          $('.pharmacy_name').html(company_name);
          $('.pharmacy_registration_number').html(registration_no);
          $('#extends_plan_Modal').modal();
       }

       function restrictAlphabets(e){
        var x=e.which||e.keycode;
        if((x>=48 && x<=57) || x==8)
        return true;
        else
        return false;
      }

     /*delete by Ajax */
     function delete_record(website_id,rowId)
      {
          if(confirm('Do you want to delete this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/delete_technician')}}",
                  data: {'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Row deleted...</span>');
                      }
                      else{
                        $('.alertmessage').html('<span class="alert alert-success">Somthing event wrong!...</span>');
                        }
                  }
              });
          }
      }
      /*End of delete by ajavx */

     

    </script>
@endsection
