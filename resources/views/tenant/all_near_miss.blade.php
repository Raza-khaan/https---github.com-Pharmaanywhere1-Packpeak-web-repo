@extends('tenant.layouts.mainlayout')
@section('title') <title>All Near Miss</title>
 <style>
  .bg-primary
{
background-color:lightgrey !Important;
padding:11px;
}
  .dt-button-collection
{
  margin-top: 5px  !important;
}
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
              
              <a href="{{url('/near_miss')}}" class="btn btn-primary"> Add Near Miss</a>
              <a href="{{url('/archive_all_near_miss')}}" class="btn btn-primary"> Archived Records</a>              
            </div>
          </div>    
         

            
            </nav>

          </div>


       

         <!-- Main content -->
             <!-- Main content -->
             <section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);"> 
          <div class="row">
             <div class="col-md-1"></div>
            <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-green">
                    <div class="inner">
                       <h4>Missed Tablet</h4>
                      <h5>Last 7 Days Count-<b>{{$mtL7}}</b></h5>
                      <h5>Last 30 Days Count-<b>{{$mtL30}}</b></h5>
                      <h5>Total Count-<b>{{$mtAll}}</b></h5>

                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div><!-- ./col -->

            <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-yellow">
                    <div class="inner">
                       <h4>Extra Tablet</h4>
                      <h5>Last 7 Days Count-<b>{{$etL7}}</b></h5>
                      <h5>Last 30 Days Count-<b>{{$etL30}}</b></h5>
                      <h5>Total Count-<b>{{$etAll}}</b></h5>

                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div><!-- ./col -->

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                       <h4>Wrong Tablet</h4>
                      <h5>Last 7 Days Count-<b>{{$wtL7}}</b></h5>
                      <h5>Last 30 Days Count-<b>{{$wtL30}}</b></h5>
                      <h5>Total Count-<b>{{$wtAll}}</b></h5>

                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div><!-- ./col -->

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                       <h4>Wrong Day</h4>
                      <h5>Last 7 Days Count-<b>{{$wdL7}}</b></h5>
                      <h5>Last 30 Days Count-<b>{{$wdL30}}</b></h5>
                      <h5>Total Count-<b>{{$wdAll}}</b></h5>

                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div><!-- ./col -->

                <div class="col-lg-2 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-primary" style="background-color:lightgrey !Important;">
                    <div class="inner">
                       <h4>Other</h4>
                      <h5>Last 7 Days Count-<b>{{$otherL7}}</b></h5>
                      <h5>Last 30 Days Count-<b>{{$otherL30}}</b></h5>
                      <h5>Total Count-<b>{{$otherAll}}</b></h5>

                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  </div>
                </div><!-- ./col -->

          </div>
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
                <h3 class="box-title">{{__('All Near Miss List')}}</h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">
                  
                  <table id="example1"     class="table">
                    <thead>
                      <tr>
        
                      @if(request()->get('role_type')=='admin')
                      <th></th>
                      @endif

                        <th>{{__('Date Time')}}</th>
                        <th>{{__('Time')}}</th>
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

                        <td>{{date("j/n/Y",strtotime($missedPatient->created_at))}}</td>
                        <td>{{date("h:i A",strtotime($missedPatient->created_at))}}</td>
                        <td>{{$missedPatient->missed_tablet?$missedPatient->missed_tablet:0}}</td>
                        <td>{{$missedPatient->extra_tablet?$missedPatient->extra_tablet:0}}</td>
                        <td>{{$missedPatient->wrong_tablet?$missedPatient->wrong_tablet:0}}</td>
                        <td>{{$missedPatient->wrong_day?$missedPatient->wrong_day:0}}</td>

                        <td>{{$missedPatient->other}}</td>
                        <td>{{$missedPatient->person_involved}}</td>
                        <td>{{$missedPatient->initials}}</td>

                        @if(request()->get('role_type')=='admin')
                        <td>

                        <a href="{{url('near_miss/softarchive/'.$missedPatient->id)}}" title="soft_delete"  ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                        <a href="{{url('near_miss/delete/'.$missedPatient->id)}}" title="{{__('delete')}}"  onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('near_miss/edit/'.$missedPatient->id)}}" title="{{__('edit')}}"><i class="fa fa-edit text-primary"></i></a>
                        </td>
                        @endif

                      </tr>
                      @empty
                      <tr><td colspan="9">{{__('NO Records')}}</td></tr>
                    @endforelse

                    </tbody>

                  </table>
                 


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

        $("#lblmainheading").html("All  Near Miss");
      });


    </script>
@endsection
