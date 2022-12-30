@extends('tenant.layouts.mainlayout')
@section('title') <title> Patients Notification</title>
@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          {{__(' Patients Notification')}}
            <small>{{__('Preview')}}</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> {{__('Home')}}</a></li>
            <li class="active">{{__(' Patient Notification')}}</li>
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
                  <h3 class="box-title">{{__(' Patients Notification')}}</h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">
                
                  <table id="{{ request()->get('role_type')=='admin'?'multidelete':'tbDatatable' }}"  data-model='Patient'   class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th  >{{__('First Name')}}</th>
                        <th >{{__('Last Name')}}</th>
                        <th>{{__('Date of birth')}}</th>
                        <th  >{{__('Facility')}}</th>
                        <th>{{__('Location')}}</th>
                        <th>{{__('Address')}}</th>
                        <th>{{__('Phone')}}</th>
                        <th>{{__('Get Text when pickup Deliver ?')}}</th>
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
                        <td>{{ $patient_report->facility->name}}</td>
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
                        <td>{{ $patient_report->text_when_picked_up_deliver==1?'Yes':'No'}}</td>
                        
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('patients/notification/'.$patient_report->id)}}" title="Notification" >
                          @if($patient_report->notification=='1')
                           <i class="fa fa-bell text-success"></i> On
                           
                          @else
                           <i class="fa fa-bell text-warning"></i> Off
                          @endif
                          &nbsp;&nbsp;
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

    
  </script>
@endsection
