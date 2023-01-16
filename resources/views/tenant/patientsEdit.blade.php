@extends('tenant.layouts.mainlayout')
@section('title') <title>Patients</title>

<style>
.datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top

{
  width:15% !Important;
}
  </style>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->

 <div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Patient Registration</h2>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">

           
        </div>
    </div>
</div>
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
             
                <form role="form" action="{{url('patients/edit/'.$patient->id)}}"  method="post" enctype="multipart/form-data" >


                
                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Modify Patient</li>
    </ol>
</nav>


</div>
                {{ csrf_field() }}
                <div class="report-forms">
                <div class="row">
                        <div class="col-md-6 m-auto">
                        <div class="patient-information">
                        <h3>Personal Information</h3>  
                        <div class="row">

                        
                            <div class="form-group col-md-6">
                              <label for="first_name">{{__('First Name')}} <span style="color:red">*</span></label>
                              <input required type="text" class="form-control" value="{{$patient->first_name}}" maxlength="20"   id="first_name" name="first_name" placeholder="First Name..">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name">{{__('Last Name')}}</label>
                                <input required type="text" class="form-control" value="{{$patient->last_name}}"  maxlength="20"   id="last_name" name="last_name" placeholder="Last Name..">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">{{__('Date Of Birth')}} <span style="color:red">*</span></label>
                                <input required onchange="DOB(this)"
                                data-provide="datepicker" 
                                type="text" class="form-control" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $patient->dob)->format('d/m/Y')}}"    name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('Y-m-d')}}" >
                              </div>
                              
                            
                            
                            <div class="form-group checkbox-wrp col-md-6">
                            <label>{{__('Location')}} </label><br/>
                              @foreach($locations as $location)
                                <label>
                                  <input type="checkbox"  {{ (is_array(explode(',',$patient_location[0]->locations)) and in_array($location->id, explode(',',$patient_location[0]->locations))) ? ' checked' : '' }} name="location[]"  class="minimal" value="{{$location->id}}"  readonly="readonly"/>&nbsp;{{$location->name}}</label>
                                <label>
                              @endforeach
                                                             
                            </div>

 
                            <div class="form-group col-md-6">
                            <label for="phone">{{__('Mobile Number')}}  <span style="color:red">*</span></label>
                            <input required type="text" class="form-control checkIsPhoneNo" value="{{$patient->phone_number}}"  minlength="10" maxlength="10" onkeypress="return restrictAlphabets(event);" id="phone"   name="phone_number" placeholder="XXXXX XXXXX">
                        </div>


                        <div class="form-group col-md-6">
                            <label for="facility">{{__('Facility')}} <span style="color:red">*</span></label>
                            <!-- <input type="text" value="{{$patient->facility->name}}"  list="facilitylist" name="facilities_id" id="facility" class="form-control @error('facilities_id') is-invalid @enderror" placeholder="Enter Facility"> -->
                            <select required  name="facilities_id" id="facility" style="width:100%">
                          
                            @if(isset($facilities))
                            @foreach($facilities as $row)

                            @if($row->name==$patient->facility->name)
                            <option selected value="{{$row->name}}">{{$row->name}}</option>
                            
                            @else
                            <option  value="{{$row->name}}">{{$row->name}}</option>
                            @endif 
                            
                            @endforeach @endif
</select>
                        </div>

                          
                        <!-- textarea -->
                        <div class="form-group col-md-6">
                            <label for="address">Address </label> Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map </a>
                            <input required type="text" class="form-control"   id="address"   name="address" value="{{$patient->address}}" placeholder="Address.."/>
                        </div>

                            <!-- <label>Get a text  when picked up/Delivered ?</label> -->
                            <div class="form-group col-md-6">
                                <label>{{__('Get a text  when picked up/Delivered ?')}} 
                                  <input type="checkbox"   {{$patient->text_when_picked_up_deliver==1?'checked=checked':''}} name="up_delivered" id="up_delivered"  class="minimal" />&nbsp;
                                  <span class='load_mobile' style="display:none;"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i></span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">{{__('Update Patient')}}</button>



                        </div>
                        </div>
                        </div>



                      

                        </div>

                        
                       </div>
           

                </form>
         


          </div>   <!-- /.row -->
        

      </label>
    </div>


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
        // var m=$('#up_delivered').val(); 
        var m=$("input[type=checkbox][name='up_delivered']:checked").val();
        if(m=='undefined')
        {
          $('#mobile_no_div').css('display','none'); 
          $('#mobile_no').removeAttr('');
        }
        else if(m=='1')
        {
          alert('helloo');
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

        // $('#add_patient').submit(function(){
        //   let obj1=$('input[type=checkbox][name="location[]"]');

        //   let obj=$('input[type=checkbox][name="location[]"]:checkbox:checked');
        //   if(obj.length>0){
        //     obj1.parent('div').removeClass('icheckbox_minimal-red hover');
        //     obj1.parent('div').addClass('icheckbox_minimal-blue');
        //     return true;
        //   }else{
        //     obj1.parent('div').removeClass('icheckbox_minimal-blue');
        //     obj1.parent('div').addClass('icheckbox_minimal-red hover');
        //     return  false;
        //   }
        // });

        $('#dob').change(function(){

               var today = new Date();
              var olday = new Date(this.value);
                    
              // console.log(dateDiff(olday, today));
              if(dateDiff(olday, today)<18){
                   $('#patientDob_Modal').modal('toggle'); 
              }
        }); 

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

        //Datemask yyyy-mm-dd
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

         //iCheck for checkbox and radio inputs
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
                $('#same_as').css('display','block'); 
                // $('#mobile_no_div').css('display','block');
                $('#same_as').iCheck('uncheck');
                $('#mobile_no').val("");
              }
              else
              {
                $('#same_as').css('display','none'); 
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
                $('#mobile_no').val('');
              }
              $('.load_mobile2').css('display','none');
              
            });

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


        /*mobile no  validate*/
        $('.checkIsPhoneNo').change(function(){
           if($(this).val().length !='10'){
             $(this).css('border','1px solid red'); 
           }
           else{
               $(this).css('border','1px solid lightgray');
           }

        });

      });


      function delete_driver(rowId)
      {
          if(confirm('Do you want  to  delete this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('delete_driver')}}",
                  data: {'row_id':rowId,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      //console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="text-success">Driver deleted...</span>');
                      }
                      else{ $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>'); }
                  }
              });
          }
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

    </script>
@endsection
