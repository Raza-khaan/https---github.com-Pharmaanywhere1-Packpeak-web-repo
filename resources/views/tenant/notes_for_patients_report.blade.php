@extends('tenant.layouts.mainlayout')
@section('title') <title>All Notes For Patients  Reports </title>

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
              <a href="{{url('/notes_for_patients')}}" class="btn btn-primary"> Add Notes</a>
              <a href="{{url('/archive_notes_for_patients_report')}}" class="btn btn-primary"> Archived Records</a>              
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

                  <table id="example1"   class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th  >{{__('Patient Name')}}</th>
                        <th>{{__('Patient DOB')}}</th>
                        <th  >{{__('Notes For Patient')}}</th>
                        <th  >{{__('Note Date')}}</th>
                        <th>{{__('Send As Text')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody>
                    @forelse($notes_for_patients as $notes_for_patient)
                    @if(isset($notes_for_patient->patients->first_name) && $notes_for_patient->patients->first_name!="")
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{$notes_for_patient->patients->id}}</td>
                      @endif
                        <td>{{$notes_for_patient->patients->first_name.' '.$notes_for_patient->patients->last_name}}</td>
                        <td>{{date("j/n/Y",strtotime($notes_for_patient->dob))}}</td>
                        <td>{{$notes_for_patient->notes_for_patients}}</td>
                        <td>{{date("j/n/Y, h:i A",strtotime($notes_for_patient->created_at))}}</td>
                        <td>{{$notes_for_patient->notes_as_text==1?'True':'False'}}</td>

                        @if(request()->get('role_type')=='admin')
                        <td>
                       
                        <a href="{{url('notes_for_patients/softarchive/'.$notes_for_patient->id)}}" title="soft_delete"  ><i class="fa fa-trash text-info"></i>&nbsp;&nbsp;
                        <a href="{{url('notes_for_patients/delete/'.$notes_for_patient->id)}}" title="{{__('delete')}}"  onclick="return confirm('Are you sure you want to delete this report?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('notes_for_patients/edit/'.$notes_for_patient->id)}}" title="{{__('edit')}}"><i class="fa fa-edit text-success"></i></a>
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
  $("#lblmainheading").html("Note for patient  Report");

});

    </script>
@endsection
