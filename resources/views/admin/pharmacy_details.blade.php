<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Pharmacy details </title>
     <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
     <!-- Favicon  -->
     <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
  <!-- Bootstrap 3.3.2 -->
  <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />    
        <!-- FontAwesome 4.3.0 -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- <link href="{{ URL::asset('admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" /> -->
        <!-- Ionicons 2.0.0 -->
        <!-- Theme style -->
        <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
              folder instead of downloading all of them to reduce the load. -->
        <link href="{{ URL::asset('admin/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- iCheck -->
        <link href="{{ URL::asset('admin/plugins/iCheck/flat/blue.css') }}" rel="stylesheet" type="text/css" />

        <link href="{{ URL::asset('admin/plugins/jQueryUI/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
          
        <link href="{{ URL::asset('admin/dist/css/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css"/>
        
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{ URL::asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- DATA TABLES --> 
        <link href="{{ URL::asset('admin/plugins/datatables/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
          <!-- Date Picker -->
        <link href="{{ URL::asset('admin/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="{{ URL::asset('admin/plugins/daterangepicker/jquery.comiseo.daterangepicker.css') }}" rel="stylesheet" type="text/css" />
        
        

          <!-- fullCalendar 2.2.5 -->  
          <link href="{{ URL::asset('admin/plugins/fullcalendar/fullcalendar.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('admin/plugins/fullcalendar/fullcalendar.print.css')}}" rel="stylesheet" type="text/css" media='print' />
        <link href="{{ URL::asset('admin/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css" />

        <!--   DropZone    Css  -->
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('admin/plugins/dropzone/css/dropzone.css')}}" />

        <!--  to  Add  Advanced Form feature -->
        <!-- iCheck for checkboxes and radio inputs -->
        <link href="{{ URL::asset('admin/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" />
        <!-- Bootstrap Color Picker -->
        <link href="{{ URL::asset('admin/plugins/colorpicker/bootstrap-colorpicker.min.css')}}" rel="stylesheet"/>
        <!-- Bootstrap time Picker -->
        <link href="{{ URL::asset('admin/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet"/>
        <!-- Theme style -->
        <!-- <link href="{{ URL::asset('admin/plugins/iCheck/all.css')}}" rel="stylesheet" type="text/css" /> -->
        <link href="{{ URL::asset('admin/dist/css/pre-style.css')}}" rel="stylesheet" type="text/css" />
        
 <style>
 /*.onoffswitch {
    position: relative; width: 68px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 15px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 21px; padding: 0; line-height: 21px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 10px;
    background-color: #34A7C1; color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 10px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 1.5px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 44px;
    width:22px;
    border: 2px solid #999999; border-radius: 15px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
} */


 </style>
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('admin.includes.header')
      @include('admin.includes.sidebar')

      <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Pharmacy details
            <small>Preview</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="dashboard">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>

       
       
             



         <!-- Main content -->
         <section class="content pre-wrp-in">
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
                <form role="form" action="{{url('add_drivers')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="first_name">First Name</label>
                              <p>{{$pharmacy_details[0]->first_name}}</p>
                              <!-- <input type="text" class="form-control" maxlength="20" value="{{$pharmacy_details[0]->first_name}}" required id="first_name" name="first_name" placeholder="First Name"> -->
                            </div>
                            <div class="form-group">
                              <label for="last_name">Last Name</label>
                              <p>{{$pharmacy_details[0]->last_name}}</p>
                              <!-- <input type="text" class="form-control" maxlength="20" value="{{$pharmacy_details[0]->last_name}}" required id="last_name" name="last_name" placeholder="Last Name"> -->
                            </div>

                            <div class="form-group">
                              <label for="phone">Phone</label>
                              <p>{{$pharmacy_details[0]->phone}}</p>
                              <!-- <input type="text" class="form-control" value="{{$pharmacy_details[0]->phone}}"  maxlength="10" onkeypress="return restrictAlphabets(event);" id="phone"  required name="phone" placeholder="phone"> -->
                            </div>

                            

                        </div>

                        <div class="col-md-6">
                             
                              <div class="form-group">
                               <label for="email">Email</label>
                               <p>{{$pharmacy_details[0]->email}}</p>
                               <!-- <input type="email" class="form-control" value="{{$pharmacy_details[0]->email}}" required id="email" name="email" placeholder="Email"> -->
                              </div>
                            <!-- textarea -->
                            <div class="form-group">
                                <label for="address">Address</label> 
                                <!-- Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map </a> -->
                                <p>{{$pharmacy_details[0]->address}}</p>
                                <!-- <input type="text" class="form-control" onFocus="geolocate()" value="{{$pharmacy_details[0]->address}}"  list="custom_address"  id="address"  required name="address" placeholder="Address"/> -->

                            </div>

                              <div class="form-group">
                                <label for="registration_no">Registration No</label>
                                <p>{{$pharmacy_details[0]->registration_no}}</p>
                                <!-- <input type="text" class="form-control" value="{{$pharmacy_details[0]->registration_no}}" required id="registration_no" name="registration_no" placeholder="Registration No"> -->
                              </div>
                             
                              

                        </div>
                        <!-- <div class="box-footer">
                          <button type="submit" class="btn btn-primary">Add A Driver</button>
                        </div> -->
                       <!--  <div class="col-md-offset-6 col-md-6">
                          <button type="submit" class="btn btn-primary">Update</button>
                        </div> -->
                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->
              
            <div class="col-xs-12">
               <div class="box">
                <div class="box-header">
                

                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                    <label for="no_of_admins">Admin</label>
                    <input type="text" name="no_of_admins" id="no_of_admins"  maxlength="10" onkeypress="return restrictAlphabets(event);" value="{{$subscription[0]->no_of_admins}}" onkeyup="update_user(this,'no_of_admins')"  placeholder="No Of Admin" class="form-control">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                     <label for="no_of_technicians">Technicians</label>
                     <input type="text" name="no_of_technicians" id="no_of_technicians" maxlength="10" onkeypress="return restrictAlphabets(event);"  value="{{$subscription[0]->no_of_technicians}}" onkeyup="update_user(this,'no_of_technicians')"  placeholder="No Of Technicians" class="form-control">
                    </div>
                  </div>
                   <div class="col-sm-3">
                     <div class="form-group">
                      <label for="app_logout_time">App Logout Time (Minute)</label>
                      <input type="text" name="app_logout_time" id="app_logout_time"  maxlength="10" onkeypress="return restrictAlphabets(event);" value="{{$subscription[0]->app_logout_time}}" onkeyup="update_user(this,'app_logout_time')"  placeholder="App Logout Time" class="form-control">
                      </div>
                  </div>
                  <div class="col-sm-3">
                     <div class="form-group">
                      <label for="default_cycle">Cycle</label>
                      <input type="text" name="default_cycle" id="default_cycle"  maxlength="10" onkeypress="return restrictAlphabets(event);" value="{{$subscription[0]->default_cycle}}" onkeyup="update_user(this,'default_cycle')"  placeholder="No Of Admin" class="form-control">
                      </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-3">
                     <!-- <a href="{{url('admin/subscriptions')}}" class="text-center fa fa-arrow-left"> <b>Back</b></a> -->
                  </div>
                  <div class="col-sm-3">
                    <div class="text-center alertmessage"></div>
                  </div>
                </div>
                
                  
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                <h2 class="heading-h2">{{ucfirst($form['title'])}}</h2>

                  @if(isset($subscription) && count($subscription))
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                     <tr>
                       
                        <th style="width: 150px;" >FORM/Name</th>
                        <th style="width: 60px;" >Subsciption</th>
                        <th style="width: 150px;" >FORM/Name</th>
                        <th style="width: 60px;" >Subsciption</th>
                      </tr>
                    </thead>
                     <tbody>
                      <tr>
                        <td ><b>Add Pick Up</b></td>
                        <td>
                        <div class="onoffswitch">
                              <input type="checkbox" onchange="update_form(this)" name="form1" value="form1" @if($subscription[0]->form1=='1') checked @endif class="onoffswitch-checkbox" id="form1" tabindex="0" >
                              <label class="onoffswitch-label" for="form1">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                        </td>
                        <td ><b>Add Near Misses</b></td>
                        <td>
                        <div class="onoffswitch">
                              <input type="checkbox" onchange="update_form(this)" name="form11" value="form11" @if($subscription[0]->form11=='1') checked @endif class="onoffswitch-checkbox" id="form11" tabindex="0" >
                              <label class="onoffswitch-label" for="form11">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                        </td>
                      </tr>
                      <tr>
                        <td ><b>PickUps Report</b></td>
                        <td>
                        <div class="onoffswitch">
                              <input type="checkbox" onchange="update_form(this)" name="form2" value="form2" @if($subscription[0]->form2=='1') checked @endif class="onoffswitch-checkbox" id="form2" tabindex="0">
                              <label class="onoffswitch-label" for="form2">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                        </td>
                         <td ><b>All Near Misses</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form12" value="form12" @if($subscription[0]->form12=='1') checked @endif class="onoffswitch-checkbox" id="form12" tabindex="0" >
                                <label class="onoffswitch-label" for="form12">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                      </tr>
                      <tr>
                                              
                          <td ><b>PickUps Calender</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form3" value="form3" @if($subscription[0]->form3=='1') checked @endif class="onoffswitch-checkbox" id="form3" tabindex="0" >
                                <label class="onoffswitch-label" for="form3">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>Last Month Miss Reports</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form13" value="form13" @if($subscription[0]->form13=='1') checked @endif class="onoffswitch-checkbox" id="form13" tabindex="0" >
                                <label class="onoffswitch-label" for="form13">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                      </tr>
                      <tr>
                        <td ><b>6 Monthly Compliance Reports</b></td>
                        <td>
                        <div class="onoffswitch">
                              <input type="checkbox" onchange="update_form(this)" name="form4" value="form4" @if($subscription[0]->form4=='1') checked @endif class="onoffswitch-checkbox" id="form4" tabindex="0" >
                              <label class="onoffswitch-label" for="form4">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                        </td>
                        <td ><b>Near Miss Monthly Reports v2</b></td>
                        <td>
                        <div class="onoffswitch">
                              <input type="checkbox" onchange="update_form(this)" name="form14" value="form14" @if($subscription[0]->form14=='1') checked @endif class="onoffswitch-checkbox" id="form14" tabindex="0" >
                              <label class="onoffswitch-label" for="form14">
                                  <span class="onoffswitch-inner"></span>
                                  <span class="onoffswitch-switch"></span>
                              </label>
                        </div>
                        </td>
                      </tr>
                      <tr>
                         <td ><b>All Compliance Index Reports</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form5" value="form5" @if($subscription[0]->form5=='1') checked @endif class="onoffswitch-checkbox" id="form5" tabindex="0" >
                                <label class="onoffswitch-label" for="form5">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>Add Returns</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form15" value="form15" @if($subscription[0]->form15=='1') checked @endif class="onoffswitch-checkbox" id="form15" tabindex="0" >
                                <label class="onoffswitch-label" for="form15">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </td>
                      </tr>
                      <tr>
                         <td ><b>Add Patients</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form6" value="form6" @if($subscription[0]->form6=='1') checked @endif class="onoffswitch-checkbox" id="form6" tabindex="0" >
                                <label class="onoffswitch-label" for="form6">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>All Returns</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form16" value="form16" @if($subscription[0]->form16=='1') checked @endif class="onoffswitch-checkbox" id="form16" tabindex="0" >
                                <label class="onoffswitch-label" for="form16">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </td>
                      </tr>
                      <tr>
                         <td ><b>New Patients Reports</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form7" value="form7" @if($subscription[0]->form7=='1') checked @endif class="onoffswitch-checkbox" id="form7" tabindex="0" >
                                <label class="onoffswitch-label" for="form7">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                           <td ><b>Add Audit</b></td>
                            <td>
                            <div class="onoffswitch">
                                  <input type="checkbox" onchange="update_form(this)" name="form17" value="form17" @if($subscription[0]->form17=='1') checked @endif class="onoffswitch-checkbox" id="form17" tabindex="0" >
                                  <label class="onoffswitch-label" for="form17">
                                      <span class="onoffswitch-inner"></span>
                                      <span class="onoffswitch-switch"></span>
                                  </label>
                              </div>
                            </td>
                      </tr>
                      <tr>
                         <td ><b>Patients Picked Up Last Month</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form8" value="form8" @if($subscription[0]->form8=='1') checked @endif class="onoffswitch-checkbox" id="form8" tabindex="0" >
                                <label class="onoffswitch-label" for="form8">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>All Audit</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form18" value="form18" @if($subscription[0]->form18=='1') checked @endif class="onoffswitch-checkbox" id="form18" tabindex="0" >
                                <label class="onoffswitch-label" for="form18">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                          </td>
                      </tr>
                      <tr>
                        <td ><b>Add Checking</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form9" value="form9" @if($subscription[0]->form9=='1') checked @endif class="onoffswitch-checkbox" id="form9" tabindex="0" >
                                <label class="onoffswitch-label" for="form9">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>Add Notes For Patients</b></td>
                          <td>
                          
                            <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form19" value="form19" @if($subscription[0]->form19=='1') checked @endif class="onoffswitch-checkbox" id="form19" tabindex="0" >
                                <label class="onoffswitch-label" for="form19">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            
                            </div>
                          </td>
                      </tr>
                      <tr>
                        <td ><b>Checking Reports</b></td>
                          <td>
                          <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form10" value="form10" @if($subscription[0]->form10=='1') checked @endif class="onoffswitch-checkbox" id="form10" tabindex="0" >
                                <label class="onoffswitch-label" for="form10">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                          </div>
                          </td>
                          <td ><b>Note For Patients Reports</b></td>
                          <td>
                            <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form20" value="form20" @if($subscription[0]->form20=='1') checked @endif class="onoffswitch-checkbox" id="form20" tabindex="0" >
                                <label class="onoffswitch-label" for="form20">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            
                            </div>
                          </td>
                      </tr>
                      <!-- <tr>
                         <td ><b>Sms Tracking Reports</b></td>
                          <td>
                            <div class="onoffswitch">
                                <input type="checkbox" onchange="update_form(this)" name="form21" value="form21"  @if($subscription[0]->form21=='1') checked @endif class="onoffswitch-checkbox" id="form21" tabindex="0" >
                                <label class="onoffswitch-label" for="form21">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            
                            </div>
                          </td>
                          <td></td>
                          <td></td>
                      </tr> -->
                    </tbody>
                   
                  </table>
                  @endif


                 
                  

                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->



            </div><!-- /.col -->
          </div><!-- /.row -->
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

      <footer class="main-footer">
        <!-- <div class="pull-right hidden-xs">
          <b>Version</b> 2.0
        </div> -->
        <strong>Copyright &copy; {{date('Y')}}-{{date('Y')+1}} <a href="{{url('/')}}">PackPeak</a>.</strong> All rights reserved.
      </footer>
    </div><!-- ./wrapper -->
         <!-- jQuery 2.1.3 -->
    <!-- <script src="{{ URL::asset('admin/plugins/jQuery/jquery-2.1.3.min.js') }}"></script> -->
    <script src="{{ URL::asset('admin/plugins/jQueryUI/jquery-1.12.4.js') }}" type="text/javascript"></script>  
    <!-- jQuery UI 1.11.2 -->
    <script src="{{ URL::asset('admin/plugins/jQueryUI/jquery-ui.js') }}" type="text/javascript"></script>  
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
   
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>  
    
    <!-- DATA TABES SCRIPT -->
    <script src="{{ URL::asset('admin/plugins/datatables/jquery.dataTables.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/datatables/dataTables.bootstrap.js')}}" type="text/javascript"></script>
    <!--  This Use  For Dashboard Graph Only -->

    <!-- Morris.js charts -->
    <!-- <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
    <script src="{{ URL::asset('admin/plugins/ajax/libs/raphael/raphael-min.js') }}"></script>
    <script src="{{ URL::asset('admin/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
    <!-- Sparkline -->
    <script src="{{ URL::asset('admin/plugins/sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <!-- jvectormap -->
    <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ URL::asset('admin/plugins/knob/jquery.knob.js') }}" type="text/javascript"></script>

    <!--  This Use  For Dashboard Graph Only -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI&libraries=places"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<!--AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI    AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y -->
    <!--   Dropzone Js -->
    <script type="text/javascript" src="{{ URL::asset('admin/plugins/dropzone/js/dropzone.js')}}"></script>

   <!-- InputMask -->
   <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.extensions.js')}}" type="text/javascript"></script>


    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ URL::asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <!-- <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script> -->
    <!-- Slimscroll -->
    <script src="{{ URL::asset('admin/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{{ URL::asset('admin/plugins/fastclick/fastclick.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('admin/dist/js/app.min.js') }}" type="text/javascript"></script>
    <!-- <script src="{{ URL::asset('admin/dist/js/pages/dashboard.js') }}" type="text/javascript"></script> -->
     <script src="{{ URL::asset('admin/plugins/ajax/libs/moment/moment.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('admin/dist/js/demo.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/daterangepicker/jquery.comiseo.daterangepicker.js') }}" type="text/javascript"></script>
    
   
    <script src="{{ URL::asset('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

    <script src="{{ URL::asset('admin/dist/js/bootstrap-tagsinput.min.js')}}" type="text/javascript"></script>
    

   
    <!-- daterangepicker -->
    <script src="{{ URL::asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>

      <script type="text/javascript">



  

      //  For   Bootstrap  datatable 
      var subcri_id={{$form['id']?$form['id']:'0'}};
      var access_id={{$subscription[0]->id?$subscription[0]->id:'0'}};
    var website_id={{$pharmacy_details[0]->website_id?$pharmacy_details[0]->website_id:'0'}};
    var host_name="{{$pharmacy_details[0]->host_name?$pharmacy_details[0]->host_name:'0'}}";
    
      $(function () {

        $('#example1').dataTable({
          "ordering": false,
          "bPaginate": true,
          "bLengthChange": true,
          "pageLength": 2,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": false
        });

      //  $('.onoffswitch input.onoffswitch-checkbox').change(function(){
        
      });

       //     restrict Alphabets  
       function restrictAlphabets(e){
          var x=e.which||e.keycode; 
          if((x>=48 && x<=57) || x==8 || x==46)
            return true;
          else
            return false;
      }
     
      function  update_user(event,form)
      {
        var status=$(event).val();
       
        if(subcri_id && status && form)
        {
          $.ajax({
                  type: "POST",
                  url: "{{url('admin/update_form_tenant_admin_technician')}}",
                  data: {'row_id':access_id,status:status,form:form,website_id:website_id,host_name:host_name,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result.success);
                      if(result.success){
                        $('.alertmessage').html('<span class="alert alert-success text-success"> data Updated.</span>');
                        setInterval( function(){$('.alertmessage').html("");},4000);
                      }
                      else
                      { 
                        $('.alertmessage').html('<span class="alert alert-danger text-danger">Somthing event wrong!...</span>');
                        setInterval( function(){$('.alertmessage').html("");},4000); 
                      }
                  }
              });
        }
        else
        { 
          $('.alertmessage').html('<span class="alert alert-danger text-danger">Somthing event wrong!...</span>');
          setInterval( function(){$('.alertmessage').html("");},4000); 
        }
      }
      function update_form(event){
        //  alert("hi"); 
        var status='on';
        if (!$(event).is(':checked')) { status='0'; } 
        else{ status='1'; }
        var form=$(event).val(); 
        console.log('status '+status+' form: '+form+' website_id '+website_id+'access_id '+access_id+' host_name '+host_name)
        // website_id  access_id
        if(subcri_id && status && form)
        {
          $.ajax({
                  type: "POST",
                  url: "{{url('admin/update_form_of_tenant')}}",
                  data: {'row_id':access_id,status:status,form:form,website_id:website_id,host_name:host_name,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result.success);
                      if(result.success){
                        var sta=(status=="1")?"on":"off"; 
                        $('.alertmessage').html('<span class="alert alert-success text-success">Now this form  is '+sta+'.</span>');
                        setInterval( function(){$('.alertmessage').html("");},4000); 
                      }
                      else
                      { 
                        $('.alertmessage').html('<span class="alert alert-danger text-danger">Somthing event wrong!...</span>');
                        setInterval( function(){$('.alertmessage').html("");},4000); 
                      }
                  }
              });
        }
        else
        { 
          $('.alertmessage').html('<span class="alert alert-danger text-danger">Somthing event wrong!...</span>');
          setInterval( function(){$('.alertmessage').html("");},4000); 
        }
      }

     /* Start  The  map  Address    Code  */
  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(20.268455824834792,85.84099235520011);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();

  var placeSearch, autocomplete;


  function initialize(){

     autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode']});


    ///
    var mapOptions = {
      zoom: 18,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

    marker = new google.maps.Marker({
      map: map,
      position: myLatlng,
      draggable: true
    });

    geocoder.geocode({'latLng': myLatlng }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
              $('#latitude,#longitude').show();
              $('#map_address').val(results[0].formatted_address); 
              // $('#address').val(results[0].formatted_address);
              $('#latitude').val(marker.getPosition().lat());
              $('#longitude').val(marker.getPosition().lng());
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
          }
      }
    });

    google.maps.event.addListener(marker, 'dragend', function() {

    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
            $('#map_address').val(results[0].formatted_address); 
            $('#address').val(results[0].formatted_address);
            $('#latitude').val(marker.getPosition().lat());
            $('#longitude').val(marker.getPosition().lng());
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);
        }
      }
    });
  });

}


  google.maps.event.addDomListener(window, 'load', initialize);

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle(
            {center: geolocation, radius: position.coords.accuracy});
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }
     
    </script>

  </body>
</html>
