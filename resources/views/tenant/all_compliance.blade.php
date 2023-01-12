@extends('tenant.layouts.mainlayout')
@section('title') <title>All Compliance Reports</title>
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

                  <table id="example1"  data-model='Pickups'   class="table">
                    <thead>

                      <tr>
                        <th></th>
                        <th style="width: 150px;" >{{__('Patients Name')}}</th>
                        <th>{{__('DOB')}}</th>
                        <th>{{__('Location')}}</th>

                        <th>{{__('MI%')}}</th>
                        @if(request()->get('role_type')=='admin')
                        <th > {{__('Action')}}</th>
                        @endif

                      </tr>

                    </thead>
                    <tbody>
                    @foreach($patients as $patient)

                      @foreach($patient->pickups as $pickup)
                      @php
                        $m=explode(',',$pickup->location);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                    @if(isset($patient->first_name) && $patient->first_name!="")
                      <tr>
                      @if(request()->get('role_type')=='admin')
                        <td>{{ $pickup->id}}</td>
                      @endif
                        <td>{{ $patient->first_name.' '.$patient->last_name }}</td>
                        <td>{{ date("j/n/Y, h:i A",strtotime($patient->dob)) }}</td>
                        <td>
                          @if(isset($locations) && count($locations))
                            @php $locationarray=array(); @endphp
                             @foreach($locations as $row)
                               @php array_push($locationarray,$row->name); @endphp
                             @endforeach
                            {{implode(',',$locationarray)}}
                          @endif
                        </td>
                        <td>100</td>
                        @if(request()->get('role_type')=='admin')
                        <td>
                        <a href="{{url('pickups/delete/'.$pickup->id)}}" title="delete"  onclick="return confirm('Are you sure you want to delete this compliance?');"><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        <a href="{{url('pickups/edit/'.$pickup->id)}}" title="edit"><i class="fa fa-edit text-primary"></i></a>
                        </td>
                        @endif

                      </tr>
                      @endif
                      @endforeach
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
      $("#lblmainheading").html("All Compliance Reports");
    });

  </script>

@endsection
