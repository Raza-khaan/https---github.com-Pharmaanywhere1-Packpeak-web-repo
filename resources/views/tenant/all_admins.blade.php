@extends('tenant.layouts.mainlayout')
@section('title') <title>All Admins</title>
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
        <li class="breadcrumb-item active" aria-current="page">All Admin</li>
    </ol>
</nav>


</div>

         <!-- Main content -->
         <section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
          
        
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
            <div class="col-xs-12">


              <div class="box">
                <div class="box-header">
                  
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  @if(isset($all_admins) && count($all_admins))
                  <table id="example1"   class="table table-bordered table-striped">
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
                        <th>{{__('Action')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($all_admins as $row)
                      @if(Session::has('phrmacy') && Session::get('phrmacy')->id!=$row['id'])
                      <tr>
                        <td>{{ucfirst($row['first_name']).' '.ucfirst($row['last_name'])}}</td>
                        <td>{{$row['email']}}</td>
                        <td>{{$row['initials_name']}}</td>
                        <!-- <td><i class="fa fa-hospital-o"></i>&nbsp;{{$row['company_name']}}</td> -->
                        <td>{{$row['registration_no']}}</td>
                        <td>{{$row['phone']}}</td>
                        <!-- <td>{{$row['address']}}</td> -->
                        <!-- <td>{{$row['host_name']}}</td> -->
                        <!-- <td>{{$row['website_id']}}</td> -->
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('technician/delete/'.$row['id'])}}" title="{{__('delete')}}" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('technician/edit/'.$row['id'])}}" title="{{__('edit')}}"><i class="fa fa-edit text-success"></i></a>
                        </td>
                        @endif

                      </tr>
                      @endif
                      @endforeach

                    </tbody>
                   
                  </table>
                  @else
                   <div class="text-center text-danger"><span> {{__('There are no data.')}}</span></div>
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
      $(document).ready(function(){
        $('#example1').dataTable();
        $("#lblmainheading").html("All Admin List");
      });

    </script>
@endsection
