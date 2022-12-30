@extends('tenant.layouts.mainlayout')
@section('title') <title>Add Patients</title> 
<style>
  .d-none{
    display: none !important;
  }
  .d-block{
    display: block !important;
  }
  .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top

  {
    width:15% !Important;
  }
</style>
@endsection
@section('content')



 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
      

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
            <div class="alertmsg"></div>
              
                <form role="form" id="addPatientForm" action="javascript:void(0)" {{url('save_patient')}}  method="post" enctype="multipart/form-data" id="add_patient">
                


                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add New Patient</li>
    </ol>
</nav>


</div>
<div class="report-forms">

<div class="row">

                        <div class="col-md-6">
                        <div class="patient-information">
                        <h3>Personal Information</h3> 
                        <div class="row">
                        
                            <div class="form-group col-md-6">
                              <label for="first_name">{{__('First Name')}} <span style="color:red">*</span></label>
                              <input  value="{{old('first_name')}}" onkeypress="return restrictNumerics(event);" type="text" class="form-control @error('first_name') is-invalid @enderror" maxlength="20"   id="first_name" name="first_name" placeholder="First Name..">
                              @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                              @enderror
                           </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">{{__('Last Name')}}<span style="color:red">*</span></label>
                                <input  value="{{old('last_name')}}" onkeypress="return restrictNumerics(event);" type="text" class="form-control @error('last_name') is-invalid @enderror" maxlength="20"   id="last_name" name="last_name" placeholder="Last Name..">
                                  @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">{{__('Date Of Birth')}} <span style="color:red">*</span></label>
                                
                                <input  value="{{old('dob')}}"  data-provide="datepicker" 
                                 type="text" class="form-control datepicker @error('dob') is-invalid @enderror"
                                   id="dob" onchange="DOB(this)" name="dob"   data-mask   
                                   placeholder="dd/mm/yyyy" >
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>

                            

                            
                            
                            <div class="form-group checkbox-wrp col-md-6">
                            <label for="dob">{{__('Location')}} </label> <br/>
                            @foreach($locations as $location)
                               
                                <label>
                                    <input  {{ (is_array(old('location')) and in_array($location->name, old('location'))) ? ' checked' : '' }} type="checkbox" name="location[]"  class="minimal @error('no_of_weeks') is-invalid @enderror" value="{{$location->id}}"  readonly="readonly"/>&nbsp;{{$location->name}}</label>
                                <label>
                              @endforeach
                              @error('location[]')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                              @enderror
                            </div>
                            <!-- <label>Get a text  when picked up/Delivered ?</label> -->
                            <div class="form-groupnone col-md-6">
                            <label for="phone">{{__('Mobile Number')}}  <span style="color:red">*</span></label>
                            <input type="text"  value="{{old('phone_number')?old('phone_number'):'04'}}" class="form-control checkIsPhoneNo  @error('phone_number') is-invalid @enderror" maxlength="10" minlength="10" onkeypress="return restrictAlphabets(event);" id="phone"  name="phone_number" placeholder="XXXXX XXXXX">
                            @error('phone_number')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="facility">{{__('Facility')}}<span style="color:red">*</span></label>
                            <!-- <input type="text" value="{{old('facilities_id')}}"  list="facilitylist" name="facilities_id" id="facility" class="form-control @error('facilities_id') is-invalid @enderror" placeholder="Enter Facility"> -->
                            
                            
                            
                            
                            <select name ="facilities_id" id="facility" style="width:100%">
                            @php $facility_name='';  @endphp
                              @foreach($facilities as $facility)
                                @php
                                  if(old('facilities_id')==$facility->id){
                                    $facility_name=$facility->name;
                                  }
                                @endphp
                                <option  {{old('facilities_id')==$facility->id?'selected':''}} value="{{$facility->name}}">{{$facility->name}}</option>
                              @endforeach
                            </select>
                            @error('facilities_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                           
                            

                         
                        <div class="form-group col-md-12">
                             @if ($Smssendlimit == 0)
                               <p style="color:red">SMS Limit Reached<p>
                               <input disabled="disabled" type="checkbox"  {{old('up_delivered')?'checked':''}} name="up_delivered" id="up_delivered"  class="minimal @error('up_delivered') is-invalid @enderror" />&nbsp;        
                        <input style="display:none" value ="{{$Smssendlimit}}"/>
                        <label>{{__('Get a text  when picked up/Delivered ?')}}  <strong> ({{$usedsms}}/{{$Allowedsms}} Sms Used) </strong> &nbsp;&nbsp; 
                                  
                                  <span class='load_mobile' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                </label>
                                @error('up_delivered')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                             @else
                             
                               <input  type="checkbox"  {{old('up_delivered')?'checked':''}} name="up_delivered" id="up_delivered"  class="minimal @error('up_delivered') is-invalid @enderror" />&nbsp;        
                        <input style="display:none" value ="{{$Smssendlimit}}"/>
                        <label>{{__('Get a text  when picked up/Delivered ?')}}  <strong> ({{$usedsms}}/{{$Allowedsms}} Sms Used) </strong> &nbsp;&nbsp; 
                                  
                                  <span class='load_mobile' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                </label>
                                @error('up_delivered')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                             @endif     

                       
                            </div>
                            
                        <div class="form-group col-md-12">
                            <label for="address">Address </label> Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map </a>
                            <input type="text" class="form-control"   id="address"   name="address" placeholder="Address.."/>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block addPatientBtn">{{__('Add Patient')}}</button>
                        </div>
                        </div>
</div>

</form>
                        <div class="col-md-6">
                        <div class="patient-information">
                        <h1><b>OR </b></h1> 
                        <a href="{{url('Patients.csv')}}" class="btn btn-success btn-xs pull-right">Csv Format Download</a>


                        <!-- <a href="{{url('public/Patients.csv')}}" class="btn btn-success btn-xs pull-right">Csv Format Download</a> -->

                    <form role="form" action="{{url('import_patients')}}" method="post" enctype="multipart/form-data" >
                    {{ csrf_field() }}
                    <div class="form-group">
                      <label for="first_name">Select Patients Csv  <span style="color:red">*</span></label>
                      <input type="file"  name="patient_file" accept=".csv,.pdf" placeholder="Choose your Patients File" 
                      class="form-control">
                      <br><p><b>!!! For Locations use '1' for shelf,
                      '2' for fridge and '3' for safe in location coloumn as in sample
                      csv file</b></p>                            
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Import Patients</button>

                    </form>




      



                        <div class="form-group" id="facility_other_desc_div" style="display:{{$facility_name==strtolower('Other')?'block':'none'}};">
                            <label for="facility_other_desc">{{__('Other Description')}}<span style="color:red">*</span></label>
                                <input  value="{{old('facility_other_desc')}}" type="text" class="form-control @error('facility_other_desc') is-invalid @enderror" maxlength="20"   id="facility_other_desc" name="facility_other_desc" placeholder="Facility other description">
                               
                              @error('facility_other_desc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                        </div>
                        <div class="form-group" style="display:{{old('up_delivered') && old('same_as_above')==false?'none':'block'}};" id="mobile_no_div">
                            
                        </div>
                        <div class="form-group {{old('up_delivered')? 'hey':'d-none'}}"  id="same_as" >
                                <label>{{__('Secondary Number')}} 
                                  <input type="checkbox"  {{old('same_as_above')?'checked':''}} name="same_as_above" id="same_as_above"  class="minimal @error('same_as_above') is-invalid @enderror" />&nbsp;
                                  <span class='load_mobile2' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                </label>
                                @error('same_as_above')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        <div class="row">
                          <div class="col-md-4">
                          
                          </div>
                       </div>

                        </div>
                        </div>

                       
                 </div>
                 </div>

                


                <!-- /.box-header -->
                <div class="box-footer">
                  
			
                

                
                </div><!-- bOx -foooter -->
              


          </div>   <!-- /.row -->
        
       

<!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Select Address</h4>
            </div>
            <form action="#"  method="post" >
            {{ csrf_field() }}
              <div class="modal-body" style="padding:0px; " >
                <input type="hidden"  name="event_date"  id="event_date" />
                <div id="myMap" style="height:400px;  width:100%;     position: static; "></div>
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
          <div class="modal-content">
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
@endsection


@section('customjs')


<script type="text/javascript">

function DOB(birthday)
{

  var today = new Date();
            var birthDate = new Date(birthday.value);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if(age<18)
            {

              if(confirm('Patient age is less than 18 years. Do you want to continue?')){

          }
        else
        {
          $("#dob").val("");
        }
            }
}


    $(document).ready(function(){
      $("#lblmainheading").html("Patient Registration");
    // var now = new Date();
    // var month = (now.getMonth() + 1);               
    // var day = now.getDate();
    // if (month < 10) 
    // month = "0" + month;
    // if (day < 10) 
    // day = "0" + day;
    // var today = month + '-' + now.getFullYear() + '-' + day;
    // $('#dob').val(today);
      

         
        $("#addPatientForm").on('submit', function(e) {
            // console.log($(this).serialize()+'&_token='+'{{ csrf_token() }}')
               
               e.preventDefault();
               if($('#first_name').val() && $('#last_name').val() &&   $('#dob').val() && $('#facility').val() ){
                // $('.alertmsg').html('');
                
                $.ajax({
                  type: "POST",
                  url: "{{url('checkduplicatePatient')}}",
                  data: $('#addPatientForm').serialize()+'&_token='+'{{ csrf_token() }}',
                  beforeSend: function() {
                    $('.addPatientBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                  },
                  success: function(result){
                    

                    if(result=='1'){
                      // alert("old reciords"); 
                      if(confirm("There is another patient with the same name and DOB, are you sure you want to add this patient as well?")){
                        $.ajax({
                          type: "POST",    
                          url: "{{url('save_patient')}}",
                          data: $('#addPatientForm').serialize()+'&_token='+'{{ csrf_token() }}',
                          success: function(result){
                            
                            

                            console.log("old  record");
                            $('.addPatientBtn').html('Add Patient');
                            console.log(result);
                             if(result.errors==""){
                                // alert(result.success);
                                $("#addPatientForm")[0].reset();
                                $('input[type=checkbox]').parent().removeClass("checked");
                                $('#mobile_no_div').css("display","none");
                                $('.alertmsg').html('<div class="alert alert-success"> <strong>Patient</strong> Added Successfully.</div>');
                                $('#same_as').addClass('d-none');
                                // console.log($('#same_as'))
                              
                              }else{
                                 printErrorMsg(result.errors);
                              }
                          },
                          error:function(result){
                            $('.alertmsg').html('<div class="alert alert-success"> <strong>Patient</strong> merge Successfully.</div>');
                            console.log(result.errors);
                            return;
                            if(result.errors!=""){
                                printErrorMsg(result.errors);
                              }
                          }
                        });
                      }
                      else{
                        $('.addPatientBtn').html('Add Patient');
                      }
                       
                    }
                    else if(result=='0'){
                        // alert("new records "); 
                        $.ajax({
                          type: "POST",
                          url: "{{url('save_patient')}}",
                          data: $('#addPatientForm').serialize()+'&_token='+'{{ csrf_token() }}',
                          success: function(result){
                            console.log("new record");
                            console.log(result);

                            
                            location.reload();
                            $('.addPatientBtn').html('Add Patient');
                            
                              if(result.errors==""){
                                //  alert(result.success);
                                $("#addPatientForm")[0].reset();
                                $('input[type=checkbox]').parent().removeClass("checked");
                                $('#mobile_no_div').css("display","none");
                                 $('.alertmsg').html('<div class="alert alert-success"> <strong>Patient</strong> Added Successfully.</div>');
                                 $('#same_as').addClass('d-none');
                                // console.log($('#same_as'))
                              }else{
                                  printErrorMsg(result.errors);
                              }
                          },
                          error:function(result){
                            
                            $('.alertmsg').html('<div class="alert alert-success"> <strong>Patient</strong> Added Successfully.</div>');
                            console.log(result.errors);
                            return; 
                            if(result.errors!=""){
                                printErrorMsg(result.errors);
                              }
                          }
                        }); 
                    }
                    else{
                      $('.alertmsg').html('<div class="alert  alert-danger"> Somting went wrong! </div>'); 
                    }
                  }
                }); 
                
               
                 
               }
               else{
                 $('.alertmsg').html('<div class="alert  alert-danger"> All  * fields are required .</div>'); 
               }
        });
        
        var m=$("input[type=checkbox][name='up_delivered']:checked").val();
        if(m=='undefined')
        {
          $('#mobile_no_div').css('display','none'); 
          $('#mobile_no').removeAttr('');
        }
        else if(m=='1')
        {
          
          $('#mobile_no_div').css('display','block'); 
          $('#mobile_no').prop('',true);
        }
          
        $('#facility').change(function(){
            if($(this).children("option:selected").text()=='other'){
              $('#facility_other_desc_div').show();
              $('#facility_other_desc').prop('',true);
            }else{
              $('#facility_other_desc_div').hide();
              $('#facility_other_desc').removeAttr('');
            }

        });

        $('#add_patient').submit(function(){
          let obj1=$('input[type=checkbox][name="location[]"]');
          // alert(obj1.length);
          // obj1.css('border-color','red'); 
          // obj1.parent('div').removeClass('icheckbox_minimal-blue');
          // obj1.parent('div').addClass('icheckbox_minimal-red hover');return  false;
          let obj=$('input[type=checkbox][name="location[]"]:checkbox:checked');
          //alert(obj.length);
          if(obj.length>0){
            obj1.parent('div').removeClass('icheckbox_minimal-red hover');
            obj1.parent('div').addClass('icheckbox_minimal-blue');
            return true;
          }else{
            obj1.parent('div').removeClass('icheckbox_minimal-blue');
            obj1.parent('div').addClass('icheckbox_minimal-red hover');
          //  alert('wrong');
            return  false;
          }

         // return  false;
        });

        // $('#dob').change(function(){

        //        var today = new Date();
        //       var olday = new Date(this.value);
                    
        //       // console.log(dateDiff(olday, today));
        //       if(dateDiff(olday, today)<18){
        //            $('#patientDob_Modal').modal('toggle'); 
        //       }
        // }); 

        $('.age_yes').click(function(){

          if($("#accept_age").prop('checked') == true){
             $('.errorfornotchecked').html("");
             $('#patientDob_Modal').modal('toggle');
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


    function printErrorMsg (msg) {
            $('.alertmsg').html('<div class="alert alert-danger">\
                    <ul class="newalertdata"></ul></div>')
            $.each( msg, function( key, value ) {
                $(".newalertdata").append('<li>'+value+'</li>');
            });
    }
    

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

        // $("#dob").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
       // $("[data-mask]").inputmask();
        //iCheck for checkbox and radio inputs

        $('#dob').datepicker({
            format: "dd/mm/yyyy",
            endDate: new Date(), 
            autoclose: true
          }).on('changeDate', function(e) {
         

              var today = new Date();
              var olday = new Date(e.date);

              // console.log(dateDiff(olday, today));
              if(dateDiff(olday, today)<18){
               
                   $('#patientDob_Modal').modal('toggle'); 
              }
              // `e` here contains the extra attributes
          });

    
          $('#dob').on('keyup keypress keydown', function(e){
              e.preventDefault();
          });

        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });

        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });

        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        }); 

        
        

        $("input[type=checkbox][name='up_delivered']").on('ifToggled', function(event){
            $('.load_mobile').css('display','block'); 
              var checked = $(this).closest('div.icheckbox_minimal-blue').hasClass("checked");
              //  checked ==  false  ||  unchecked ==  true 
              if(checked==false)
              {
                $('#same_as').removeClass('d-none');
                // $('#mobile_no_div').css('display','block');
                $('#same_as').iCheck('uncheck');
              }
              else
              {
                $('#same_as').addClass('d-none'); 
                // $('#mobile_no_div').css('display','none');
                $('#mobile_no_div').html('');
              }
              $('.load_mobile').css('display','none');
            });

            $("input[type=checkbox][name='same_as_above']").on('ifToggled', function(event){

              
             
              $('.load_mobile2').css('display','block'); 
              var checked = $(this).closest('div.icheckbox_minimal-blue').hasClass("checked");
              //  checked ==  false  ||  unchecked ==  true 
            
              if(checked==false)
              {
                $('#mobile_no_div').html('<label for="mobile_no">Mobile <span class="text-danger"> *</span></label><input type="text" class="form-control" maxlength="10"  onkeypress="return restrictAlphabets(event);" id="mobile_no"  name="mobile_no" placeholder="+613214569875">');
                $('#mobile_no').prop('',true);
              }
              else
              { 
                $('#mobile_no_div').html('');
                $('#mobile_no').removeAttr('');
              }
               $('.load_mobile2').css('display','none');
              
            });

        });
     

   
   
    

      //     restrict Alphabets  
      function restrictAlphabets(e){
      var x=e.which||e.keycode; 
      if((x>=48 && x<=57) )
      return true;
      else
      return false;
     }

     function restrictNumerics(e){
        var x=e.which||e.keycode; 
        if((x>=65 && x<=90) || x==8 ||
        (x>=97 && x<=122)|| x==95 || x==32)
        return true;
        else
        return false;
      }

      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').dataTable({
          "ordering": false,
          //"bPaginate": true,
          "bLengthChange": true,
          "pageLength": 2,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });

        $('.checkIsPhoneNo').change(function(){
           if($(this).val().length !='10'){
             $(this).css('border','1px solid red'); 
           }
           else{
               $(this).css('border','1px solid lightgray');
           }

        });
      });
     


      

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

    </script>
@endsection
