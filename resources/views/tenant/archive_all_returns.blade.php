@extends('tenant.layouts.mainlayout')

@section('title') <title>All Returns</title>
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
              
              <a href="{{url('/all_returns')}}" class="btn btn-primary"> Return Report</a>
              
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
                  <h3 class="box-title">{{__('All Returns List')}}</h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body pre-wrp-in table-responsive">

                  <table id="example1"  class="table">
                    <thead>
                    @if(request()->get('role_type')=='admin')
                      <th></th>
                      @endif
                      <th>{{__('Patient Name')}}</th>
                      <th>{{__('Patient DOB')}}</th>
                      <th>{{__('Return date-time')}}</th>
                      <th>{{__('Store')}}</th>
                      <th>{{__('Other store')}}</th>
                      <th>{{__('Select days/weeks')}}</th>
                      <th>{{__('Number of Days or Weeks return')}}</th>
                      <th>{{__('Staff Initial')}}</th>
                      @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                      @endif
                    </thead>
                    <tbody>
                      @foreach($returns as $return)
                      @if(isset($return->patients->first_name) && $return->patients->first_name!="")
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $return->id}}</td>
                      @endif
                        <td>{{ucfirst($return->patients->first_name).' '.ucfirst($return->patients->last_name)}}</a></td>
                        <td>{{date("j/n/Y",strtotime($return->dob))}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($return->created_at))}}</td>
                        <td>{{$return->stores->name}}</td>
                        <td>{{$return->other_store}}</td>
                        <td>{{$return->day_weeks}}</td>
                        <td>{{$return->returned_in_days_weeks}}</td>
                        <td>{{$return->staff_initials}}</td>

                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('return/softunarchive/'.$return->id)}}" title="soft_delete"  ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                        <a href="{{__('return/delete/'.$return->id)}}" title="{{__('Delete')}}"  onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{__('return/edit/'.$return->id)}}" title="{{__('Edit')}}"><i class="fa fa-edit text-success"></i></a>
                        </td>
                        @endif

                      </tr>
                      @endif

                      @endforeach

                    </tbody>

                  </table>

                </div>
                <!-- /.box-body -->
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

        $("#lblmainheading").html("Archived Return Report");
      });

    </script>
@endsection
