@extends('tenant.layouts.mainlayout')
@section('title') <title>All Pharmacies</title>
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
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           All Pharmacies
            <small>Preview</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="dashboard">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>

       
       
             



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
            <div class="col-xs-12">


              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">All Pharmacies List <i class="fa fa-hospital-o"></i> </h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                  @if(count($all_pharmacies))
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="width: 150px;" >Name</th>
                        <th>Email</th>
                        <th>Initials Name</th>
                        <th>Pharmacy</th>
                        <th>Registration</th>
                        <th>Phone</th>
                        <!-- <th style="width: 350px;" >Address</th> -->
                        <th>Host Name</th>
                        <th>Website id</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($all_pharmacies as $row)
                      <tr id="row_4">
                        <td>{{$row['name']}}</td>
                        <td>{{$row['email']}}</td>
                        <td>{{$row['initials_name']}}</td>
                        <td><i class="fa fa-hospital-o"></i>&nbsp;{{$row['company_name']}}</td>
                        <td>{{$row['registration_no']}}</td>
                        <td>{{$row['phone']}}</td>
                        <!-- <td>{{$row['address']}}</td> -->
                        <td>{{$row['host_name']}}</td>
                        <td>{{$row['website_id']}}</td>
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


@endsection


@section('customjs')


    <script type="text/javascript">
      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').dataTable({
          "ordering": false,
          //"bPaginate": true,
          "bLengthChange": true,
          "pageLength": 2,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
        
      });

    </script>
@endsection
