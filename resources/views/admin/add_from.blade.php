@extends('admin.layouts.mainlayout')
@section('title') <title>Form Subscriptions</title>
<style>
  .form-inline {
    display: -ms-flexbox;
    -ms-flex-flow: row wrap !important;
    flex-flow: row wrap;
    -ms-flex-align: center !important;
    flex-direction: column !important;
    flex-wrap: nowrap !important;
    align-items: stretch !important;
}

#example1_wrapper > div:nth-child(1){
  display:none !important;
}
#example1_wrapper > div:nth-child(3){
  display:none !important;
}
.pre-wrp {
    /* width: 80%; */
    margin: 0px auto 50px auto;
    padding-bottom: 60px;
}
.pre-wrp .form-group input[type="text"]{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 40px;
  border-radius: 4px !important;
}
.pre-wrp .form-group input[type="date"]{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 40px;
  border-radius: 4px !important;
}
.pre-wrp .form-group input[type="password"]{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 40px;
  border-radius: 4px !important;
}

.pre-wrp .form-group textarea{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  border-radius: 4px !important;
}

.pre-wrp .form-group input[type="checkbox"]{  
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15); 
}

.pre-wrp .form-group select{   box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  border-radius: 4px !important; height: 40px;}

.pre-wrp .form-group .icheckbox_minimal-blue{ box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15); margin-right: 8px; }

.checkbox-wrp label { margin-right: 15px; }




.checkbox-wrp{ margin-top: 10px; }

.mt-20{ margin-top: 20px; }

.mt-10{ margin-top: 10px; }


.pre-wrp-in .table>tbody>tr>td, 
.pre-wrp-in .table>tbody>tr>th, 
.pre-wrp-in .table>tfoot>tr>td, 
.pre-wrp-in .table>tfoot>tr>th, 
.pre-wrp-in .table>thead>tr>td, 
.pre-wrp-in .table>thead>tr>th {
  padding: 13px 8px;

}


.pre-wrp-in input[type="text"],input[type="email"]{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 35px;
  border-radius: 4px !important; padding-left: 6px; padding-right: 6px;
}

/*.pre-wrp-in input[type=""]{
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 35px;
  border-radius: 4px !important; padding-left: 6px; padding-right: 6px;
}*/

.pre-wrp-in select {
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 35px;
  border-radius: 4px !important;
}

.pre-box-d {
  box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);
}


.pre-box-d:hover {
  box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 15px 12px rgba(0,0,0,0.22);
}

.signature-component canvas{  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15);
  height: 150px;
  border-radius: 4px !important; margin-bottom: 20px; width: 100%; }

.heading-h2 { margin-top: 0px; margin-bottom: 25px; }

  .box-wrp{ background-color: #fff; padding-top: 20px; }








 .onoffswitch {
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
    border: 2px solid #0071f2; border-radius: 15px;
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
    background-color: #0071f2; color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "OFF";
    padding-right: 10px;
    background-color: #ffffff; color: #999999;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 1.5px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 44px;
    width:22px;
    border: 2px solid #0071f2; border-radius: 15px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
} 



  .onoffswitch-label{ border: 2px solid #0071f2; }
  .onoffswitch-switch{     border: 2px solid #0071f2; }

  .onoffswitch-inner:after {     background-color: #ffffff; }
  
  .onoffswitch-inner:before, .onoffswitch-inner:after { font-size: 12px; }



/*  SELECT2  CSS STYLE*/

.select2-container--default .select2-selection--single {
    height: 40px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
    display: none;
}
#patient_name, #multiple-patient_name {
  /* for Firefox */
  -moz-appearance: none;
  /* for Chrome */
  -webkit-appearance: none;
}
.select2.select2-container .select2-selection--single{
  padding-bottom: 5px;
  padding-right: 5px;
  box-shadow: 0 1px 6px 0 rgba(34, 34, 34, 0.15);
  border-color: rgba(34, 34, 34, 0.15) !important;
  height: 40px;
  border-radius: 4px !important;
  }
  .select2.select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 40px;
      outline: 0;
  }
  .select2-container {
    
    max-width: 100%;
}


/* END of  SELECT2  CSS STYLE*/


</style>
@endsection

@section('content')

      <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
         
 <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <div class="pharma-add">
              <a href="#">Subscription Details</a>
            </div>
            <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
            <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      <span>
                      @if(!empty(session('admin')['name']))
                        {{session('admin')['name']}}  
                      @endif
                      </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a> -->
                      <a class="dropdown-item" href="{{url('admin/profile')}}">Profile</a>
                      <a class="dropdown-item" href="{{url('admin/changepassword')}}">Change Password</a>

                      <a class="dropdown-item" href="{{url('admin/logout')}}">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>


          <div >
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
            <div class="col-md-12" style="background-color:white;padding:18px">


              <div class="box">
                <div class="box-header">
                <div class="row">
                <div class="col-lg-3">
                  <h2 class="heading-h2">{{ucfirst($form['name'])}}</h2>
                  </div>
                  <div class="col-md-6">
                  <div class="text-center alertmessage"></div>
                </div>
                  <div class="col-lg-3" >
                     <a style="float:right" href="{{url('admin/subscriptions')}}" class="btn btn-primary"> <b>Back</b></a>
                  </div>
                  
                </div>
              
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
                
                </div><!-- /.box-header -->
                <div class="box-body ">
                

                  @if(isset($subscription) && count($subscription))
                  <table  class="table table-bordered table-striped">
                    <thead>
                     <tr>
                       
                        <th style="width: 150px;" >FORM/Name</th>
                        <th style="width: 60px;" >Subscription</th>
                        <th style="width: 150px;" >FORM/Name</th>
                        <th style="width: 60px;" >Subscription</th>
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
        

      </div><!-- /.content-wrapper -->
      
      @endsection


@section('customjs')

      <script type="text/javascript">
      //  For   Bootstrap  datatable 
      var subcri_id={{$form['id']?$form['id']:'0'}}

      
     

       //   restrict Alphabets  
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
                  url: "{{url('admin/update_form')}}",
                  data: {'row_id':subcri_id,status:status,form:form,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result.success){
                        
                        $('.alertmessage').html('<span class="alert alert-success text-success"> data Updated.</span>');
                        setInterval( function(){$('.alertmessage').html("");},5000);
                      }
                      else
                      { 
                        $('.alertmessage').html('<span class="alert alert-danger text-danger">Somthing event wrong!...</span>');
                        setInterval( function(){$('.alertmessage').html("");},5000); 
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
        // alert($(this).val());
        console.log(status,$(event).val());

        var form=$(event).val(); 
        // alert(subcri_id); 
        if(subcri_id && status && form)
        {
          $.ajax({
                  type: "POST",
                  url: "{{url('admin/update_form')}}",
                  data: {'row_id':subcri_id,status:status,form:form,"_token":"{{ csrf_token() }}"},
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

     

     
    </script>

@endsection
