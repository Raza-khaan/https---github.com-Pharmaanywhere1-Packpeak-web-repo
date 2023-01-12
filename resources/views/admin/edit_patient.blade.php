@extends('admin.layouts.mainlayout')
@section('title') <title>Update Patients</title>

<style>
.d-none {
    display: none !important;
}

.d-block {
    display: block !important;
}

 

</style>
 @endsection
 @section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
 <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <div class="pharma-add report-add">
                      <a href="{{url('admin/patients')}}" class="active">New Patient</a>
            <a href="{{url('admin/pickups_reports')}}">Pickups</a>
            <a href="{{url('admin/checkings_report')}}">Checking</a>
            <a href="{{url('admin/all_near_miss')}}">Near Miss</a>
            <a href="{{url('admin/all_returns')}}">Return</a>
            <a href="{{url('admin/all_audits')}}">Audit</a>
            <a href="{{url('admin/notes_for_patients_report')}}">Patient Notes</a>
        </div>
        <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
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


    <div class="pharma-add pharma-add-mobile">
        <a href="#">New Patient</a>
        <a href="#">Pickups</a>
        <a href="#">Checking</a>
        <a href="#">Near Miss</a>
        <a href="#">Return</a>
        <a href="#">Audit</a>
        <a href="#">Patient Notes</a>
    </div>
        <!-- Main content -->
        <section class="content">


        
        <div class="reports-breadcrum">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Patient</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a class="btn reset-btn" href="#">Reset</a>
                <!-- <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Add Patient<i
                        class="ml-2 fas fa-arrow-circle-right"></i></button> -->
            </div>

        </div>

        <div class="report-forms">
          <div class="row">
          <div class="col-md-6">
        
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
           
                
                <form  id="addPatientForm" style="width:100%" role="form" action="{{url('admin/update_patient/'.$patient[0]->website_id.'/'.$patient[0]->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="patient-information">
              <h3>Patient Information</h3>
                <div class="row">
                    <input style="display:none" name="ieseditform"  id ="ieseditform" value="1"/>
                <div class="form-group col-md-12">
                            
                              <label for="company_name">Company Name <span class="text-danger"> *</span> </label>
                                  @if(count($all_pharmacies)  && isset($all_pharmacies))
                                  <select style="display:none" class="form-control" name="company_name" id="company_name">
                                    <option value="">Please Select</option>
                                    @foreach($all_pharmacies as $row)
                                      @if($row->website_id==$patient[0]->website_id)
                                      
                                      <option selected value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}} </option>
                                      @else
                                      <option  value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}} </option>
                                      @endif 
                                    @endforeach 
                                    
                                </select>
                                  @endif

                                  <input readonly  required id="txtcompany" class="typeahead form-control" type="text">
                            
                    </div>

                    <div class="form-group col-md-6">
                              <label for="first_name">First Name <span class="text-danger"> *</span> </label>
                              <input type="text" class="form-control" maxlength="20" value="{{$patient[0]->first_name}}"   id="first_name" name="first_name" placeholder="First Name..">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">Last Name <span class="text-danger"> *</span> </label>
                                <input type="text" class="form-control" maxlength="20" value="{{$patient[0]->last_name}}"   id="last_name" name="last_name" placeholder="Last Name..">
                            </div>
                  
                    
                            <div class="col-md-6 eg-date">
                                <label for="dob">Date Of Birth <span class="text-danger"> *</span> </label>
                                
                                
                                <div style="display:none"  class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input style="width:135px" type="text" class="form-control" name="dob_date" 
                                    id="dob_date" placeholder="15" onchange="checkDOB()">
                            </div>
                            <div  style="display:none" class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input style="width:135px" type="text" class="form-control" name="dob_month" 
                                    id="dob_month" placeholder="09" onchange="checkDOB()">
                            </div>
                            <div  style="display:none" class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input style="width:135px" type="text" class="form-control" name="dob_year" 
                                    id="dob_year" placeholder="1990" onchange="checkDOB()">
                            </div>
                                
                                <input onchange="setDobDate()" onchange="checkDOB()" type="text" class="datepicker" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $patient[0]->dob)->format('d/m/Y')}}"  name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('Y-m-d')}}" >
                              </div>
                              <div style="display:none" class="col-md-12 eg-date">
                                <small class="form-text text-muted">e.g dd/mm/yy</small>
                                <span class="input-group-prepend"></span>
                                <input   type="text" name="cdob" data-provide="datepicker" 
                                onchange="setDobDate()" aria-hidden="true" id="datepicker" class="datepicker" placeholder="Date of Birth" onchange="checkDOB()">
                                <!-- <i class="fa fa-2x fa-calendar " aria-hidden="true"   ></i> -->
                            </div>
                              
                             
                            <div class="form-group col-md-12">
                                <label>Packs Location</label>
                            </div>
                           
                            <div class=" form-group col-12 col-md-12">
                              <div class="row" style="margin-left:0px">
                              @if(isset($all_Location)  && count($all_Location))
                               @foreach($all_Location as  $value)
                               <div class="custom-control custom-checkbox">        
                               <input class="custom-control-input" id="SafeCheck_<?= $value->id ?>" type="checkbox" {{ (is_array(explode(',',$patient_location[0]->locations)) and in_array($value->id, explode(',',$patient_location[0]->locations))) ? ' checked' : '' }} name="location[]" class="minimal " value="{{$value->id}}"  />                              
                               <label class="custom-control-label"
                                     for="SafeCheck_<?= $value->id ?>">&nbsp;{{$value->name}}&nbsp;</label>
                                 &nbsp;
                                 </div>
                               @endforeach
                            @endif
                              </div>
                           
                        
                         </div>
                         
                            

                            


                           
                            



                        
                            </div>
                            </div>

</div>



                        <div class="col-md-6">
                        <div class="patient-information">
                        <div class="row">
                        <h3>Contact Information</h3>

                        <div class="col-md-12">
                                <label for="inputState">Phone Number</label>
                            </div>

                            <div class="form-group col-4 col-md-3">
                                <select onchange ="updatephonenumberlength()" id="inputState" class="form-control">
                                <option  value="0">(..)</option>
                                    <option selected value="1">+61</option>
                                   
                                </select>
                            </div>
                            <div class="form-group col-8 col-md-9">
                            
                            <input minlength="9" maxlength="9" type="text" class="form-control" value="{{$patient[0]->phone_number}}" onkeypress="return restrictAlphabets(event);" id="phone_number"   name="phone_number" placeholder="+613214569875">
                        </div>
                      <!-- textarea -->
                      <div class="form-group col-md-12">
                      <label>Address: <a href="#" data-toggle="modal" data-target="#my_map_Modal">Set in
                                        maps</a></label>
                            <input type="text" class="form-control"   id="address"   name="address" value="{{$patient[0]->address}}" placeholder="Address.."/>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="facility">Enter Facility <span class="text-danger"> *</span> </label>
                            <!-- <input type="text" name="facility" id="facility" value="{{$patient[0]->facility->name}}" list="facilitylist"  class="form-control" placeholder="Enter Facility"> -->
                            <!-- <datalist id="facilitylist"> -->
                            <select id="facility" name="facility" class="form-control">
                            @if(isset($all_facilities))
                            @foreach($all_facilities as $row)

                            @if($row->name==$patient[0]->facility->name)
                            <option selected value="{{$row->name}}">{{$row->name}}</option>
                            
                            @else
                            <option  value="{{$row->name}}">{{$row->name}}</option>
                            @endif 
                            
                            @endforeach @endif
                            </select>
                            <!-- </datalist> -->
                        </div>

                        <div class="form-group otherinput"></div>
                         <!-- <label>Get a text  when picked up/Delivered ?</label> -->
                         <div class="col-md-12 form-group">
                         <div class="custom-control custom-checkbox">
                                  <input type="checkbox" @if($patient[0]->text_when_picked_up_deliver=='1') checked @endif  name="up_delivered" id="up_delivered"    class="custom-control-input minimal" />&nbsp;
                                  <input type="checkbox" name="up_delivered" id="up_delivered"
                                        class="custom-control-input minimal" />
                                        <label class="custom-control-label" for="up_delivered">Get a text message when
                                        medication is picked up/ delivered</label>
                            </div>
                            </div>
                        
                           
                            
                        @if($patient[0]->mobile_no!=$patient[0]->phone_number)

                        <div class="col-md-12 form-group" id="same_as">
                                 
                                 <input type="checkbox" @if($patient[0]->mobile_no!=$patient[0]->phone_number) checked @endif name="same_as_above" id="same_as_above"  class="minimal" />&nbsp;
                                 <span class='load_mobile2' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                 <label>{{__('Send the message to a different number')}} &nbsp;&nbsp;
                               </label>
                       </div>

                        <div class="form-group col-4 col-md-4  mobile_no">
                        <select id="inputState" name="code" class="form-control">
                        <option selected>+61</option>
                        </select>
                        </div>
                        <div class="form-group col-8 col-md-8  mobile_no">
                        <input  value="{{$patient[0]->	mobile_no}}" type="text" class="form-control" maxlength="9" minlenght="9"
                        onkeypress="return restrictAlphabets(event);" id="mobile_no" name="mobile_no"
                        placeholder="Enter Mobile Number">
                        <small id="lblmobilenumber" style="color:red;display:none"> Enter Mobile Number *</small>
                        </div>
                        @else

                        <div class="col-md-12 form-group {{old('up_delivered')? 'hey':'d-none'}} " id="same_as">
                                 
                                 <input type="checkbox" @if($patient[0]->mobile_no!=$patient[0]->phone_number) checked @endif name="same_as_above" id="same_as_above"  class="minimal" />&nbsp;
                                 <span class='load_mobile2' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                 <label>{{__('Send the message to a different number')}} &nbsp;&nbsp;
                               </label>
                       </div>

                        <div class="form-group col-4 col-md-4 d-none mobile_no">
                        <select id="inputState" name="code" class="form-control">
                        <option selected>+61</option>
                        </select>
                        </div>
                        <div class="form-group col-8 col-md-8 d-none mobile_no">
                        <input  value="{{$patient[0]->mobile_no}}" type="text" class="form-control" maxlength="9" minlenght="9"
                        onkeypress="return restrictAlphabets(event);" id="mobile_no" name="mobile_no"
                        placeholder="Enter Mobile Number">
                        <small id="lblmobilenumber" style="color:red;display:none"> Enter Mobile Number *</small>
                        </div>
                        @endif

                        


                          <div class="form-group col-md-12">
                          <button type="submit" class="btn btn-primary btn-block">Update Patient</button>
                          </div>
                  
                              
                              
                              

                              
                       </div>       
                       </div>
                        </div>
                        <!-- <div class="box-footer">
                          <button type="submit" class="btn btn-primary">Add A Driver</button>
                        </div> -->
                       
                 

                </form>
                <!-- /.box-header -->
              <!-- /.box -->


          </div>
          
</div>
          <!-- /.row -->
        </section><!-- /.content -->



         

      </div><!-- /.content-wrapper -->


<!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Select Address</h4>  
            <button type="button" class="close" data-dismiss="modal">&times;</button>
              
            </div>
            <form action="#"  method="post" >
            {{ csrf_field() }}
              <div class="modal-body" style="padding:0px; " >
                <input type="hidden"  name="event_date"  id="event_date" />
                <div id="myMap" style="height:350px;  width:100%;     position: static; "></div>
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



      <!-- Modal for Patient Dob -->
    <div class="modal fade" id="patientDob_Modal" role="dialog">
        <div class="modal-dialog modal-xs">
          <div class="modal-content" style="height: 195px;" >
            <div class="modal-header">
              <p class="errorfornotchecked text-danger text-center"></p>
              <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
              <h4 class="modal-title text-center">Confirmation</h4>
            </div>

            
              <div class="modal-body text-center" style="padding:0px; " >
                <div class="form-group">
                   <label>
                     <span>I have acknowledging to add patient below 18 year  </span>
                     <input type="checkbox"  name="accept_age" id="accept_age"  class="minimal" />
                   </label>
                   
                </div>
                <div class="form-group">
                  <button type="button" class="btn btn-primary age_yes" >Yes</button>
                  <button type="button" class="btn btn-primary age_no" >No</button>
                </div>
                
              </div>
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button> -->
            </div>
            
          </div>
        </div>
      </div>
 


<!-- Modal for Patient Dob -->
<div class="modal fade" id="patientDob_Modal" role="dialog">
    <div class="modal-dialog modal-xs">
        <div class="modal-content" style="height: 192px;">
            <div class="modal-header">
                <p class="errorfornotchecked text-danger text-center"></p>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title text-center">Confirmation</h4>
            </div>


            <div class="modal-body text-center" style="padding:0px; ">
                <div class="form-group">
                    <label>
                        <span>I have acknowledging to add patient below 18 year </span>
                        <input type="checkbox" name="accept_age" id="accept_age" class="minimal" />
                    </label>

                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary age_yes">Yes</button>
                    <button type="button" class="btn btn-primary age_no">No</button>
                </div>

            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button> -->
            </div>

        </div>
    </div>
</div>
@endsection


@section('customjs')


<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


    <script type="text/javascript">

function updatephonenumberlength()
    {
        var inputState = $("#inputState").val();
        if(inputState ==0)
        {
            $("#phone_number").attr('minlength','10');
            $("#phone_number").attr('maxlength','10');
        }

        if(inputState >  0 )
        {
            $("#phone_number").attr('minlength','9');
            $("#phone_number").attr('maxlength','9');
        }  
    }


$("#addPatientForm").on('submit', function(e) {
        
        $('.alertmsg').html('');
        var Mobilenumber = $("#mobile_no").val();
            var c = document.getElementById('same_as_above');
            if (c.checked) 
            {
                
                if(Mobilenumber == "")
                {
                    $("#mobile_no").focus();
                    $("#lblmobilenumber").fadeIn();
                    e.preventDefault();
                    return;    
                }

            } 
            else 
            { 
                $("#lblmobilenumber").fadeOut();
            }
          
        });



$("input[type=checkbox][name='same_as_above']").change(function(event) {
        if ($(this).is(':checked')) {
            $('.mobile_no').removeClass('d-none');
        } else {
            $('.mobile_no').addClass('d-none');
        }
    });



function setDobDate() {
            var dob = $("#dob").val();

            if(dob != ''){
                const myArr = dob.split("/");

                console.log(dob);
                var dt = new Date(dob);
                
                console.log("getDay() : " + dt.getDate() ); 
                month = myArr[0];/*dt.getDate();*/
                day = myArr[1];/*dt.getMonth()+1;*/
                year = myArr[2];/*dt.getFullYear();*/
                $('#dob_year').val(year);
                $('#dob_month').val(month);
                $('#dob_date').val(day);

            //     var dob = '<input type="text" class="form-control" max="09/07/2021" name="dob" value="' +
            // dateString + '" id="dob">';
            
        $('#dob').val(dob);
            }
            var dateString = $('#dob_year').val()+'/'+$('#dob_month').val()+"/"+$('#dob_date').val();
            checkDOB(); 
            console.log(dateString);
             
        }

var getUrl = window.location;
var baseurl = getUrl.protocol+"//"+getUrl.host+'/admin/' ;


$("#txtcompany").autocomplete({


source: function (request, response) {

    $.ajax({

        url: baseurl+"autocomplete",

        type: "GET",

        dataType: "json",

        data: { prefix: request.term },

        success: function (data) {

            response($.map(data, function (item) {



                return {
                    label: item.company_name + ' -  ' + item.name 
                    // label: 'Name: ' + item.name + ', ID: ' + item.name + ', MC#: ' + item.name

                    , website_id: item.website_id,
                };

            }))

        }

    })

},

select: function (event, ui) {
    
    $("#company_name").val(ui.item.website_id);

}

});

    $(document).ready(function(){

      var  dateofbirth= $("#dob").val();
      
      $("#dob_date").val(dateofbirth.substring(3, 5));
      $("#dob_month").val(dateofbirth.substring(0, 2));
      $("#dob_year").val(dateofbirth.substring(6, 10));

      var companyname = $("#company_name option:selected").text();
  $("#txtcompany").val(companyname);
            // var m=$('#up_delivered').val(); 
            var m=$("input[type=checkbox][name='up_delivered']:checked").val();
           if(m=='undefined')
           {
            $('#mobile_no').html(""); 
           }
           else if(m=='1')
           {
               $('#mobile_no').html('<label for="mobile_no">Mobile</label><input type="text" class="form-control" maxlength="10"  onkeypress="return restrictAlphabets(event);" id="mobile_no"  name="mobile_no" placeholder="+613214569875">');
           }

           $('#dob').change(function(){

              var today = new Date();
              var olday = new Date(this.value);
              // console.log(dateDiff(olday, today));

              if(dateDiff(olday, today)<18){
                  //  $('#patientDob_Modal').modal('toggle'); 
              }
        }); 
        

         /*  ENd of the Add Patient  By  AJax */
    var m = $("input[type=checkbox][name='up_delivered']:checked").val();
    if (m == 'undefined') {
        $('#mobile_no').html("");
    } else if (m == '1') {
        console.log(m);
        $('#mobile_no').show();
    }


        $('.age_yes').click(function(){

          if($("#accept_age").prop('checked') == true){
             $('.errorfornotchecked').html("");
            //  $('#patientDob_Modal').modal('toggle');
          }
          else{
            $('.errorfornotchecked').html('<span >Accept  acknowledgment about age.</span>'); 
          }
                        
        }); 
        $('.age_no').click(function(){
          $('#dob').val("");
          $('#patientDob_Modal').modal('toggle');
        }); 

        

          
           
           
       

            
    });

    // if($("#up_delivered").prop('checked') == true){
    //         $('#same_as').css('display','block'); 
    //     }
    //     else{
    //       $('#same_as').css('display','none'); 
    //     }

    $("input[type=checkbox][name='up_delivered']").change(function(event) {
      
      
      if ($(this).is(':checked')) {
          $('#same_as').removeClass('d-none');
      } else {
          $('#same_as').addClass('d-none');
      }
  });

    function dateDiff(dateold, datenew)
    {
      var ynew = datenew.getFullYear();
      var mnew = datenew.getMonth();
      var dnew = datenew.getDate();
      var yold = dateold.getFullYear();
      var mold = dateold.getMonth();
      var dold = dateold.getDate();
      var diff = ynew - yold;
      if(mold > mnew) diff--;
      else
      {
        if(mold == mnew)
        {
          if(dold > dnew) diff--;
        }
      }
      return diff;
    }


          $(function () {

        // Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){}); 
        $('#dob').datepicker({
            format: "dd/mm/yyyy",
            endDate: new Date(), 
            autoclose: true
          });
          $('#dob').on('keyup keypress keydown', function(e){
              e.preventDefault();
          });

        //  //iCheck for checkbox and radio inputs
        //   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //   checkboxClass: 'icheckbox_minimal-blue',
        //   radioClass: 'iradio_minimal-blue'
        // });
        // //Red color scheme for iCheck
        // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        //   checkboxClass: 'icheckbox_minimal-red',
        //   radioClass: 'iradio_minimal-red'
        // });
        // //Flat red color scheme for iCheck
        // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        //   checkboxClass: 'icheckbox_flat-green',
        //   radioClass: 'iradio_flat-green'
        // }); 
        

          $("input[type=checkbox][name='up_delivered']").on('ifToggled', function(event){
             $('.load_mobile').css('display','block'); 
              var checked = $(this).closest('div.icheckbox_minimal-blue').hasClass("checked");
              //  checked ==  false  ||  unchecked ==  true 
              if(checked==false)
              {
                $('#same_as').css('display','block'); 
                $('#same_as').iCheck('uncheck');
                // $('#mobile_no').html('<label for="mobile_no">Mobile</label><input type="text" class="form-control" maxlength="10"  onkeypress="return restrictAlphabets(event);" id="mobile_no"  name="mobile_no" placeholder="+613214569875">');
              }
              else
              {
                $('#same_as').css('display','none'); 
                $('#mobile_no').html("");
              }
              $('.load_mobile').css('display','none');
            });
            $("input[type=checkbox][name='same_as_above']").on('ifToggled', function(event){
             $('.load_mobile2').css('display','block'); 
              var checked = $(this).closest('div.icheckbox_minimal-blue').hasClass("checked");
              //  checked ==  false  ||  unchecked ==  true 
              if(checked==false)
              {
                $('#mobile_no').html('<label for="mobile_no">Mobile</label><input type="text" class="form-control" maxlength="10"  onkeypress="return restrictAlphabets(event);" id="mobile_no"  name="mobile_no" placeholder="+613214569875">'); 
              }
              else
              {
                $('#mobile_no').html("");
                
              }
              $('.load_mobile2').css('display','none');
              
            });

      });
     
    /*Other input  */
      $('#facility').change(function(){
           if($(this).val()=='4'){
              $('.otherinput').html('<input type="text" name="other_facility" id="other_facility" class="form-control"  placeholder="Other Facility">'); 
            } 
           else
           {
            $('.otherinput').html('');
           }
        });
   


      //     restrict Alphabets  
      function restrictAlphabets(e){
          var x=e.which||e.keycode; 
          if((x>=48 && x<=57) || x==8 ||
            (x>=35 && x<=40)|| x==46)
            return true;
          else
            return false;
      }

     

  /* Start  The  map  Address    Code  */

  var options = {
  
  componentRestrictions: {country: "AU"}
 };

  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(-25.274399,133.775131);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();

  var placeSearch, autocomplete;


  function initialize(){

     autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), options);


    ///
    var mapOptions = {
      zoom: 5,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      componentRestrictions: {country: "AU"}
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
    




        function checkDOB()
    {
        console.log("i am aherer");
        if($('#dob_date').val() > 0 ){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in date");
        }
        if($('#dob_month').val() > 0 ){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in month");
        }
        if($('#dob_year').val() > 0){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in year");
        }

        if($('#dob_date').val() > 0 &&  $('#dob_month').val() > 0 &&  $('#dob_year').val() > 0){
            var dateString = $('#dob_year').val()+'/'+$('#dob_month').val()+"/"+$('#dob_date').val();
            
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            console.log("my age is ");
            console.log(age);
            
            if(age< 18){
                if(confirm('Patient age is less than 18 years. Do you want to continue?')){

                }else{
                // alert(result.success);
                $('#same_as').css('display',
                    'none !important');
                $("#addPatientForm")[0].reset();
                $('input[type=checkbox]').parent()
                    .removeClass("checked");
                $('#mobile_no').html("");
                $('#same_as').addClass('d-none');
                $('.alertmsg').html(
                    '<div class="alert alert-success"> <strong>Patient</strong> Updated Successfully.</div>'
                );}
            }
        }

        
        console.log("Yahooo"+$('#dob_date').val()+$('#dob_month').val()+$('#dob_year').val());
    }
      

    </script>
@endsection
