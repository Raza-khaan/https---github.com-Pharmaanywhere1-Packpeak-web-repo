@extends('tenant.layouts.mainlayout')
@section('title') <title>All Audits</title>
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
              <a href="{{url('/all_audits')}}" class="btn btn-primary">Audit Report</a>
            </div>
          </div>    
         

            
            </nav>

          </div>


</div>
 <div class="content-wrapper">
   
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
                
                  <table id="example1" data-model='Audit' class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th  >{{__('Patient Name')}}</th>
                        <th >{{__('Date-Time')}}</th>
                        <th >{{__('No Of Weeks')}}</th>
                        <th  >{{__('Store')}}</th>
                        <th> {{__('Store Other')}}</th>
                        <th>{{__('Staff initials')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @forelse($audits as $audit)
                    @if(isset($audit->patients->first_name) && $audit->patients->first_name!="")
                      <tr>

                      @if(request()->get('role_type')=='admin')
                        <td>{{ $audit->id}}</td>
                      @endif
                        <td>{{$audit->patients->first_name.' '.$audit->patients->last_name}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($audit->created_at))}}</td>
                        <td>{{$audit->no_of_weeks}}</td>
                        <td>{{$audit->stores->name}}</td>
                        <td>{{$audit->store_others_desc}}</td>
                        <td>{{$audit->staff_initials}}</td>
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('audits/softunarchive/'.$audit->id)}}" title="soft_delete"  ><i class="fa fa-trash text-success"></i>&nbsp;&nbsp;
                        <a href="{{url('audits/delete/'.$audit->id)}}" title="{{__('Delete')}}" onclick="delete_driver(1);" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('audits/edit/'.$audit->id)}}" title="{{__('Edit')}}"><i class="fa fa-edit text-success"></i></a>
                        </td>
                        @endif
                       
                      </tr>
                      @endif
                    @empty
                   
                    @endforelse
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
    
     
    $("#lblmainheading").html("Archived Audit  Report");
    
  });

</script>
   
@endsection
