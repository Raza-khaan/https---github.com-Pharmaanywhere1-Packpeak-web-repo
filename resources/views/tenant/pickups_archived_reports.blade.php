@extends('tenant.layouts.mainlayout')
@section('title') <title>Pickups Archived Reports</title>

@endsection

@section('content')
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
              <a href="{{url('/pickups_reports')}}" class="btn btn-primary"> Pickups Report</a>
                            
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
            <div class="col-xs-12">


              <div class="box">
                
                <div class="box-body pre-wrp-in table-responsive">

                <table id="example1"    class="table">
                    <thead>

                      <tr>

            <th></th>
                        <th>{{__('Name')}}</th>
                        <!-- <th>{{__('DOB')}}</th> -->
                        <th>{{__('No Of Weeks')}}</th>
                        <th >{{__('Date')}}</th>
                        <th >{{__('Time')}}</th>

                        <th>{{__('Who is picking up ?')}}</th>
                        <th>{{__('Patient Signature')}}</th>
                        <th>{{__('Pharmacy Signature')}}</th>
                        <th>{{__('Carer`s Name')}}</th>
                        <!-- <th>{{__('Notes From Patient')}}</th> -->
                        <th>{{__('Location')}}</th>
                        <!-- <th>{{__('Facility')}}</th> -->
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>

                    </thead>


                    <tbody>


                      @foreach($pickups as $pickup)
                        @php
                        $m=explode(',',$pickup->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                    @if(isset($pickup->patients->first_name) && $pickup->patients->first_name!="")
                      <tr >
                        
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $pickup->id}}</td>
                      @endif
                        <td>{{ $pickup->patients->first_name.' '.$pickup->patients->last_name }}</td>
                        
                        <td>{{ $pickup->no_of_weeks}}</td>

                        <td>{{ date("j/n/Y",strtotime($pickup->created_at)) }}</td>
                        <td>{{ date("h:i A",strtotime($pickup->created_at)) }}</td>
                        <td>{{ $pickup->pick_up_by }}</td>
                        <td><img style="height:50px" src="{{ $pickup->patient_sign }}" alt="Patient sign" /></td>



                        <td><img style="height:50px" src="{{ $pickup->pharmacist_sign }}" alt="Pharmacist sign" /></td>
                        <td>{{ $pickup->carer_name }}</td>
                        
                        <td>
                          @if(isset($locations) && count($locations))
                            @php $locationarray=array(); @endphp
                             @foreach($locations as $row)
                               @php array_push($locationarray,$row->name); @endphp
                             @endforeach
                            {{implode(',',$locationarray)}}
                          @endif
                        </td>
                        

                        @if(request()->get('role_type')=='admin')
                        <td>
                          
                        <a href="{{url('pickups/softunarchive/'.$pickup->id)}}" title="soft_delete"  ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                        <!-- <a href="javascript:void(0);" title="soft_delete" onclick="soft_delete_record('{{session()->get('phrmacy')->website_id}}','{{$pickup->id}}');" ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp; -->
                        <a href="{{url('pickups/delete/'.$pickup->id)}}" title="delete" onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('pickups/edit/'.$pickup->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a>
                        </td>
                        @endif

                      </tr>
                      @endif
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
        $("#lblmainheading").html("Archived Pickup Report");
      });


      function soft_delete_record(website_id,rowId)
      {
        console.log('Hello');
          if(confirm('Do you want  to soft archive this?'))
          {     
              $.ajax({
                  type: "POST",
                  url: "{{url('/PickUp/soft_delete_pickup')}}",
                  data: {archivetypeid:1,'row_id':rowId,website_id:website_id,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      return;
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="alert alert-success">Record Archived...</span>');
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
