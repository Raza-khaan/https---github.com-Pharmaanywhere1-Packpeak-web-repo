@extends('tenant.layouts.mainlayout')
@section('title') <title>New Patients Report</title>
<style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                    
    bottom: 90;
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>
 
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
.dt-button-collection
{
  margin-top: 5px  !important;
}

</style>
@endsection

@section('content')



<div class="dash-wrap">
    <div class="dashborad-header">
    <div class="pharma-add report-add">
            <a href="{{url('/')}}/patients" class="active family">Add New Patient</a>
            <a href="{{url('/')}}/new_patients_report" class="family">New Patient Report</a>
         
           
        </div>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button style="background-color:transparent" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                    
                      <a class="dropdown-item" href="{{url('logout')}}">Logout</a>
                    </div>
                  </div>
                  
                </div>
            </div>
    </div>
</div>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  
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
              <a href="{{url('/patients')}}" class="btn btn-primary"> Add Patient</a>
              <a href="{{url('/archived_patients_report')}}" class="btn btn-primary"> Archived Records</a>              
            </div>
          </div>    

            
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
                <div class="box-body pre-wrp-in table-responsive" >

                  <!-- <table id="{{ request()->get('role_type')=='admin'?'multidelete':'tbDatatable' }}" 
                   data-model='Patient'   class="table table-bordered table-striped">
                     -->
                 
                     <table id="example1"    class="table">

                   <thead>
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <th></th>
                        @endif
                        <th  >{{__('First Name')}}</th>
                        <th >{{__('Last Name')}}</th>
                        <th>{{__('Date of birth')}}</th>
                        <th  >{{__('Facility')}}</th>
                        <th>{{__('Location')}}</th>
                        <th>{{__('Address')}}</th>
                        <th>{{__('Mobile Number')}}</th>

                        <th>{{__('Get Text when pickup Deliver ?')}}</th>
                        <th>{{__('Mobile')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($patient_reports as $patient_report)
                        @php
                        $m=explode(',',$patient_report->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                      <tr>
                        @if(request()->get('role_type')=='admin')
                          <td>{{ $patient_report->id}}</td>
                        @endif

                        <td>{{ $patient_report->first_name}}</a></td>
                        <td>{{ $patient_report->last_name}}</a></td>
                        <td>{{ date("j/n/Y",strtotime($patient_report->dob))}}</td>
                        <td>{{isset($patient_report->facility)?strtoupper($patient_report->facility->name):''}}</td>
                        <td>
                              @if(isset($locations) && count($locations))
                                @php $locationarray=array(); @endphp
                                 @foreach($locations as $row)
                                   @php array_push($locationarray,$row->name); @endphp
                                 @endforeach
                                {{implode(',',$locationarray)}}
                              @endif
                        </td>
                        <td>{{ $patient_report->address}}</td>
                        <td>{{ $patient_report->phone_number}}</td>
                        <td>{{ $patient_report->text_when_picked_up_deliver==1?'True':'False'}}</td>
                        <td>{{ $patient_report->mobile_no}}</td>
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('patients/softarchive/'.$patient_report->id)}}" title="soft_delete"  ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                        <a href="{{url('patients/delete/'.$patient_report->id)}}" title="delete"  onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('patients/edit/'.$patient_report->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a>
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
  $("#main-wrap").css("display", "none");
  $("#lblmainheading").html("New Patient");
});
  </script>
@endsection
