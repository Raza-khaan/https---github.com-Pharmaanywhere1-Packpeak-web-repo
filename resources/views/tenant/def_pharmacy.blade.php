@extends('tenant.layouts.mainlayout')
@section('title') <title>Add Pharmacy</title>
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


 </style>
@endsection





@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
        Pharmacy Registration
            <small>New</small>
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
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header">
                    <form id="addPharmacy" action="{{ url('admin/add_pharmacy') }}" method="post" autocomplete="off">  <!--dashboard-->
                        {{ csrf_field() }}
                        
                        
                        <div class="row">
                            <div class="col-xs-6">  
                               <div class="form-group">
                                <label for="first_name">First Name</label>
                                    <input type="text"  name="first_name" class="form-control" placeholder="First Name" value="Augurs"/>
                                </div>
                                <div class="form-group">
                                <label for="last_name">Last Name</label>
                                    <input type="text"  name="last_name" class="form-control" placeholder="Last Name" value="Augurs"/>
                                </div>
                                <div class="form-group">
                                <label for="email">Email (<span class="text-danger">this email  will  be  use for pharmacy login also</span>)</label>
                                    <input type="text"  name="email" class="form-control" placeholder="Business Email" value="augurs@gmail.in"/>
                                </div>
                                <div class="form-group">
                                <label for="password">Password</label>
                                    <input type="password"  name="password" class="form-control" placeholder="Password" value="12345678"/>
                                </div>
                                <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                    <input type="text"  name="password_confirmation" class="form-control" placeholder="Confirm Password" value="12345678"/>
                                </div>
                                <div class="checkbox icheck">
                                    <label> <input type="checkbox" checked='checked' name="term"> I confirm that I have read the <a href="#" class="">privacy policy</a> and agree to the processing of my personal data by PeakPack for the stated purposes.Furthermore, by sending the form I agree to the general <a href="#" class="">terms and conditions</a>  . </label>
                                </div>
                                <!-- <a href="{{url('/')}}" class="text-center">I already have a membership</a> -->
                            </div><!-- /.col -->
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <div class="input-group">
                                      <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>&nbsp;
                                      </div>
                                      <div class="input-group-addon">
                                        +04
                                      </div>
                                      <input type="text"  name="phone" class="form-control" maxlength="10"  placeholder="Phone"  value="9999999999"/>
                                    </div>
                                </div>
                                @if(isset($all_subscription) && count($all_subscription))
                                <label>Subcription Plan</label>
                                <div class="form-group">
                                    @foreach($all_subscription as $key=>$row)
                                    <label>
                                      <input type="radio" name="subscription" class="flat-red"  value="{{$row->id}}" @if($key=='0') {{'checked'}} @endif /> {{ucfirst($row->title)}}
                                    </label>
                                    @endforeach
                                </div>
                                @endif
                                <div class="form-group">
                                <label for="company_name">Company Name</label>
                                    <input type="text"  name="company_name" maxlength="50" id="company_name" class="form-control" placeholder="Company Name"  value="{{env('TENANCY_DEFAULT_HOSTNAME')}}"/>
                                </div>
                                <div class="form-group" id="host-name-div" style="">
                                    <label>We will setup packnpeaks for you at:</label><br>
                                    https://www.<input type="text" style="border:none; " maxlength="50" name="host_name" maxlength="50" id="host_name"  autocomplete="host-name" value="{{env('TENANCY_DEFAULT_HOSTNAME')}}">.packpeak.com.au
                                    
                                </div>
                                <div class="form-group">
                                <label for="address">Address   Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map marker</a> </label>
                                   <textarea name="address" style="resize:none;" id="address" onFocus="geolocate()" class="form-control" cols="30" rows="3" placeholder="Enter address" >Augurs</textarea>
                                </div>
                               <button type="submit" class="btn btn-primary btn-flat">Register</button>
                            </div><!-- /.col -->
                        </div>
                    </form>     
                </div><!-- /.box-header -->
              </div><!-- /.box -->


          </div>   <!-- /.row -->
        </section><!-- /.content -->



         

      </div><!-- /.content-wrapper -->



 
      <!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Select Address</h4>
            </div>
            <form action="{{url('booking')}}"  method="post" >
            {{ csrf_field() }}
              <div class="modal-body" style="padding:0px; " >
                <input type="hidden"  name="event_date"  id="event_date" />
                <div id="myMap" style="height:435px;  width:100%;     position: static; "></div>
                <input id="map_address" type="text" style="width:600px; display:none; "/><br/>
                <input type="hidden" id="latitude" placeholder="Latitude"/>
                <input type="hidden" id="longitude" placeholder="Longitude"/>
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
            </form>
          </div>
        </div>
      </div>

@endsection


@section('customjs')


<script type="text/javascript">

    // auto-submit form
    $(document).ready(function(){

      $('#addPharmacy').submit();
      
    });
    


    </script>
@endsection
