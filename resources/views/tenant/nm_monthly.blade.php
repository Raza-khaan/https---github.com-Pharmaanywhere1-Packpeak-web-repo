@extends('tenant.layouts.mainlayout')
@section('title') <title>All NM Monthly</title>
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
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           All NM Monthly
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
                  <!-- <h3 class="box-title">All NM Monthly List</h3> --> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">
                  @if(count($missedPatients))
                  <table id="{{ request()->get('role_type')=='admin'?'multidelete':'tbDatatable' }}"  data-model='MissedPatient'   class="table table-bordered table-striped">
                    <thead>
                      <tr>
                       
                        <th>{{__('Missed Tablet')}}</th>
                        <th>{{__('Extra Tablet')}}</th>
                        <th>{{__('Wrong Tablet')}}</th>
                        <th>{{__('Wrong Day')}}</th>
                        <th>{{__('Other')}}</th>
                        <th>{{__('Person Involved')}}</th>
                        <th>{{__('Initials')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @forelse($missedPatients as $missedPatient)
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $missedPatient->id}}</td>
                      @endif
                        <td>{{$missedPatient->missed_tablet?$missedPatient->missed_tablet:0}}</td>
                        <td>{{$missedPatient->extra_tablet?$missedPatient->extra_tablet:0}}</td>
                        <td>{{$missedPatient->wrong_tablet?$missedPatient->wrong_tablet:0}}</td>
                        <td>{{$missedPatient->wrong_day?$missedPatient->wrong_day:0}}</td>

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
                      @empty
                      <tr><td colspan="9">{{__('NO Records')}}</td></tr>
                    @endforelse

                    </tbody>
                   
                  </table>
                  @else
                     <div class="form-group">
                      <p><b>You have No records </b></p>
                        <a href="{{url('near_miss')}}" class="btn btn-primary">Add Near Miss</a>
                     </div>
                  @endif


                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <div class="row">
                  <div class="col-md-offset-10 col-md-2">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#SummaryModal"><b>Missed Summary</b></a>
                  </div>
              </div>
              

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


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
               <th>Missed Tablet</th>
               <td>{{$allMissedTablet}}</td>
             </tr>
             <tr>
               <th>Extra Tablet</th>
               <td>{{$allExtraTablet}}</td>
             </tr>
             <tr>
               <th>Wrong Tablet</th>
               <td>{{$allWrongTablet}}</td>
             </tr>
             <tr>
               <th>Wrong Day</th>
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
      //  For   Bootstrap  datatable 
      $(document).ready(function(){
       
      });

    </script>

@endsection
