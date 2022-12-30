@extends('tenant.layouts.mainlayout')
@section('title') <title>All Checking Report</title>
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
.dt-button-collection
{
  margin-top: 5px  !important;
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
              
              <a href="{{url('/checkings_report')}}" class="btn btn-primary"> Checking Report</a>              
            </div>
          </div>    
         

            
            </nav>

          </div>



        <!-- Content Header (Page header) -->
       
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
              
                <div class="box-body pre-wrp-in table-responsive">

                  <table id="example1"  data-model='Checkings'   class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th  >	{{__('Patient Name')}}</th>
                        <th>{{__('Date-Time')}}</th>
                        <th>{{__('No Of Weeks')}}</th>
                        <th>{{__('Pharmacist Signature')}}</th>
                        <th>{{__('Location')}}</th>
                        <th>{{__('Note For Patient')}}</th>
                        <th>{{__('DD')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($checkings as $checking)

                        @php
                        $m=explode(',',$checking->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                      @if(isset($checking->patients->first_name) && $checking->patients->first_name!="")
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $checking->id}}</td>
                      @endif
                        <td>{{$checking->patients->first_name.' '.$checking->patients->last_name}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($checking->created_at))}}</td>
                        <td>{{$checking->no_of_weeks}}</td>
                        <td><img src="{{$checking->pharmacist_signature}}" style="height:80px;"/></td>
                        <td>
                        @if(isset($locations) && count($locations))
                            @php $locationarray=array(); @endphp
                             @foreach($locations as $row)
                               @php array_push($locationarray,$row->name); @endphp
                             @endforeach
                            {{implode(',',$locationarray)}}
                          @endif
                        </td>
                        <td>{{$checking->note_from_patient}}</td>

                        <td>{{$checking->dd==1?'true':'false'}}</td>
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('checkings/softunarchive/'.$checking->id)}}" title="soft_delete"  ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                          <a href="{{url('checkings/delete/'.$checking->id)}}" title="delete"  onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                          <a href="{{url('checkings/edit/'.$checking->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a>
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
      $(document).ready(function(){
        $("#lblmainheading").html("Archive Checking Report");

      });

    </script>

@endsection
