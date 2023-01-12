@extends('tenant.layouts.mainlayout')
@section('title') <title>Users/Admin List</title>
 <style>
  .dropzone {
    min-height: 150px;
    border: 2px dotted rgba(0, 0, 0, 0.3);
    background: white;
    padding: 20px 59px;
}


 .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

.dt-buttons button
{
background: rgb(192, 229, 248) !important;
border-color: rgb(255, 255, 255) !important;
color: blue;
font-weight: italic;
color: #1f89bb;

/* right: -1062%;
bot    tom: 90; */
}

.btn-group, .btn-group-vertical
{
flex-direction: column !important;
}

 </style>
@endsection


@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">



<div class="reports-breadcrum" style="margin-bottom:0px">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"  ><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">All Suspended User List</li>
    </ol>
</nav>


</div>

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
                  
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">
                  
                  <table id="example1"   class="table">
                    <thead>
                      <tr>

                        <th style="width: 150px;">  {{__('Name')}}</th>
                        <th> {{__('Email')}}</th>
                        <th> {{__('Initials Name')}}</th>
                        <!-- <th>Pharmacy</th> -->
                        <th> {{__('Registration')}}</th>
                        <th> {{__('Phone')}}</th>
                        <!-- <th style="width: 350px;" >Address</th> -->
                        <!-- <th>Host Name</th> -->
                        <!-- <th>Website id</th> -->
                        <th>{{__('Status')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th>{{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($all_technicians as $row)
                      <tr>
                        <td>{{ucfirst($row['first_name']).' '.ucfirst($row['last_name'])}} <small>({{ucfirst($row['roll_type'])}})</small></td>
                        <td>{{$row['email']}}</td>
                        <td>{{$row['initials_name']}}</td>
                        <!-- <td><i class="fa fa-hospital-o"></i>&nbsp;{{$row['company_name']}}</td> -->
                        <td>{{$row['registration_no']}}</td>
                        <td>{{$row['phone']}}</td>
                        <!-- <td>{{$row['host_name']}}</td> -->
                        <!-- <td>{{$row['website_id']}}</td> -->
                        <td>
                        @if(request()->get('role_type')=='admin')
                        <a href="{{url('technician/status/'.$row['id'])}}" class="btn btn-xs btn-@if($row['status']=='1'){{'success'}}@else{{'danger'}}@endif">
                        @if($row['status']=='1'){{'Active'}}@else{{'Inactive'}}@endif
                        </a>
                        @else
                        <a class="btn btn-xs btn-@if($row['status']=='1'){{'success'}}@else{{'danger'}}@endif">
                        @if($row['status']=='1'){{'Active'}}@else{{'Inactive'}}@endif
                        </a>
                        @endif


                        </td>
                        @if(request()->get('role_type')=='admin')
                        <td>
                          <a href="{{url('technician/restore/'.$row['id'])}}" title="{{__('restore')}}" ><i class="fa fa-recycle   text-success"></i>&nbsp;&nbsp;
                        <a href="{{url('technician/delete/'.$row['id'])}}" title="{{__('delete')}}" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;

                        </td>
                        @endif

                      </tr>
                      @endforeach

                    </tbody>

                  </table>
                




                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


@endsection


@section('customjs')


    <script type="text/javascript">
      
   

      $(document).ready(function()
{
  $("#lblmainheading").html("All Suspended User List");
});

    </script>
@endsection
