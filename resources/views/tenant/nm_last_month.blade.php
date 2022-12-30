@extends('tenant.layouts.mainlayout')
@section('title') <title>All Near Miss</title>
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
           {{__('All Near Miss Last Month')}}
            <small>{{__('Preview')}}</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i>{{__('Home')}}</a></li>
            <li><a href="dashboard">{{__('Near Miss')}}</a></li>
            <li class="active">{{__('Last Month Near Miss')}}</li>
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
                  <h3 class="box-title">{{__('Last Month Near Miss List')}}</h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">
                
                  <table id="{{ request()->get('role_type')=='admin'?'multidelete':'tbDatatable' }}"  data-model='NotesForPatient'   class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style="width: 150px;" >{{__('Missed Tablet')}}</th>
                        <th>{{__('Extra Tablet')}}</th>
                        <th>{{__('Wrong Tablet')}}</th>
                        <th style="width: 350px;" >{{__('Wrong Day')}}</th>
                        <th>{{__('Other')}}</th>
                        <th>{{__('Person Involved')}}</th>
                        <th>{{__('Initials')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($missedPatients as $missedPatient)
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $missedPatient->id}}</td>
                      @endif
                        <td>{{$missedPatient->missed_tablet}}</td>
                        <td>{{$missedPatient->extra_tablet}}</td>
                        <td>{{$missedPatient->wrong_tablet}}</td>
                        <td>{{$missedPatient->wrong_day}}</td>

                        <td>{{$missedPatient->other}}</td>
                        <td>{{$missedPatient->person_involved}}</td>
                        <td>{{$missedPatient->initials}}</td>
                        
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('near_miss/delete/'.$missedPatient->id)}}" title="{{__('delete')}}" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('near_miss/edit/'.$missedPatient->id)}}" title="{{__('edit')}}"><i class="fa fa-edit text-success"></i></a>
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
      //  For   Bootstrap  datatable 
      $(document).ready(function(){
        
        
      });

    </script>

@endsection
