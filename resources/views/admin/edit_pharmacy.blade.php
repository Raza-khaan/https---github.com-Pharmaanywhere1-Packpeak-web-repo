@extends('admin.layouts.mainlayout')
@section('title') <title>Add Pharmacy</title>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->

 <div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Pharmacy Registration</h2>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">

            <div class="profile">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
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
</div>

 <div class="content-wrapper">
      
 <div class="pharma-register">
        <h2>Pharmacy Registrationss</h2>
    </div>

        <!-- Main content -->
        <div class="report-forms">
          
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
                    <form  class="report-form" action="{{ url('admin/update_pharmacy/'.$pharmacy->website_id) }}" method="post" autocomplete="off">  <!--dashboard-->
                        {{ csrf_field() }}
                        <div class="reports-breadcrum">

                        <input name="rowid"  value="{{$id}}" hidden/>
            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a  href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Pharmacy</li>
                </ol>
            </nav>

            <!-- <div class="reset-patien">
                <a class="btn reset-btn" href="#">Reset</a>
                <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Register Pharmacy<i
                        class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div> -->

        </div>
                       
        <div class="report-forms">

<div class="row">
    <div class="col-md-6">
    <div class="patient-information">
    <div class="row">
                        <h3>Pharmacy Information</h3>
    <!-- @if(isset($all_subscription) && count($all_subscription))
                                <label class="mt-20">Subcription Plan <span class="text-danger">*</span></label>
                                <div class="form-group mt-10">
                                    @foreach($all_subscription as $key=>$row)
                                    <label>
                                      <input type="radio" required="required" name="subscription" class="flat-red"  value="{{$row->id}}" @if($pharmacy->subscription==$row->id) {{'checked'}} @elseif($key=='0') {{'checked'}} @endif /> {{ucfirst($row->title)}}
                                    </label>
                                    @endforeach
                                </div>
                                @endif -->


                                <div class="form-group col-md-12 register-plan">

                                
                                @if(isset($all_subscription) && count($all_subscription))
                                <input  required="required" name="subscription" id="subscription"
                                    class="flat-red d-none" id="subscription" value="{{$pharmacy->subscription}}" />

                                    @if($pharmacy->subscription == 3)
                                      <a style="background-color:#0071F2 !important;color:white"  id="subscription_3">Basic Plan</a>
                                      <a class="standard" id="subscription_2">Standard Plan</a>
                                      <a class="premium" id="subscription_1">Premium Plan</a>
                                    @elseif  ($pharmacy->subscription == 2)
                                    <a id="subscription_3">Basic Plan</a>
                                      <a style="background-color:#FF595E !important;color:white" class="standard" id="subscription_2">Standard Plan</a>
                                      <a class="premium" id="subscription_1">Premium Plan</a>
                                      @elseif  ($pharmacy->subscription == 1)
                                      <a   id="subscription_3">Basic Plan</a>
                                      <a class="standard" id="subscription_2">Standard Plan</a>
                                      <a  style="background-color:#001833 !important;color:white" class="premium" id="subscription_1">Premium Plan</a>
                                      @endif
                                @endif
                            </div>


                            <div class="form-group col-md-12 packpeak-set">
                                <label>We will setup Packpeaks for you at:</label>
                                <p>https://www. <input Readonly disabled type="text" style="border:none;" value="{{$pharmacy->company_name}}"
                                        maxlength="20" name="host_name" id="host_name"
                                        autocomplete="host_name"> .packpeak.com.au</p>
                                        @if($favourite==1)
                                        <input type="text" style="border:none;" value="1" maxlength="50"
                                        name="fvrt" maxlength="50" id="fvrt" hidden autocomplete="fvrt"  readonly>
                                        <a class="favourt" onClick="toggleFavourite()"><i id="fvrt-icon"
                                        class="fas fa-heart" style="color:red"></i>Add to favorites</a>
                                        @else
                                        <input type="text" style="border:none;" value="0" maxlength="50"
                                        name="fvrt" maxlength="50" id="fvrt" autocomplete="fvrt"  hidden readonly>
                                        <a class="favourt" onClick="toggleFavourite()"><i id="fvrt-icon"
                                        class="fas fa-heart" style="color:black"></i>Add to favorites</a>
                                        @endif
                            </div>


                                <div class="form-group col-md-12">
                                <label for="company_name">Company Name <span class="text-danger">*</span></label>
                                    <input type="text"  name="company_name" maxlength="20" value="{{$pharmacy->company_name}}" id="company_name" required="required" class="form-control" placeholder="Company Name"/>
                                </div>
                                <!-- <div class="form-group" id="host-name-div" style="">
                                    <label>We will setup packnpeaks for you at:</label><br>
                                    https://www.<input type="text" style="border:none;" value="{{$pharmacy->host_name}}" maxlength="50" name="host_name" maxlength="50" id="host_name"  autocomplete="host-name">.packpeak.com.au
                                    
                                </div> -->

                                <div class="form-group col-md-12 col-lg-6">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password"  name="password"  id="password"  class="form-control" placeholder="Password"/>
                                    <!-- <div class="input-group-addon" style="background:#F5F6FA;border:1px solid #F5F6FA">
                                     <i class="fas fa-check-circle" id="togglePassword"></i>
                                    </div> -->
                                   </div>
                                </div>
                                <div class="form-group col-md-12 col-lg-6">
                                <label for="password_confirmation">Confirm Password </label>
                                <div class="input-group">
                                    <input type="password"   name="password_confirmation" id="confirm_password" class="form-control" placeholder="Confirm Password"/>
                                    <div class="input-group-addon" style="background:#F5F6FA;border:1px solid #F5F6FA">
                                     <i class="fas fa-check-circle" id="toggleConfirmPassword"></i>
                                    </div>
                                    </div>
                                </div>


                               
                                
                                
                                <div class="form-group col-md-12">
                                <label for="address">Address   Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map marker</a> <span class="text-danger">*</span></label>
                                   <!-- <textarea name="address" required="required" style="resize:none;" id="address" onFocus="geolocate()" class="form-control" cols="30" rows="3" placeholder="Enter address">{{$pharmacy->address}}</textarea> -->
                                   <input name="address" required="required" style="resize:none;" id="address"
                                    onFocus="geolocate()" class="form-control" rows="3"
                                    placeholder="Enter address" value="{{$pharmacy->address}}"/>
                                    <input type="hidden"  id="i_latitude" name="latitude" value="{{$pharmacy->longitude}}" placeholder="Latitude" />
                                <input  type="hidden" id="i_longitude" name="longitude" value="{{$pharmacy->latitude}}" placeholder="Longitude" />
                                  </div>

</div>
                              </div>
    </div>

    <div class="col-md-6">
    <div class="patient-information">
    <h3>Pharmacy Information</h3>
    <div class="row">



    <div class="form-group col-md-6">
                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" value="{{$pharmacy->first_name}}" onkeypress="return restrictNumerics(event);"  name="first_name" required="required" class="form-control" placeholder="First Name"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" value="{{$pharmacy->last_name}}" required="required" onkeypress="return restrictNumerics(event);" name="last_name" class="form-control" placeholder="Last Name"/>
                                </div>
                                
                                <div class="col-md-12">
                                <label for="inputState">Phone Number</label>
                            </div>

                            <div class="form-group col-4 col-md-3">
                                <select  onchange="updatephonenumberlength()" id="inputState" class="form-control">
                                <option value="0" >(..)</option>
                                    <option value= "1"  selected>+61</option>
                                </select>
                            </div>

                            <div class="form-group col-8 col-md-9">
                                   
                                      <input    minlength="9"
                                maxlength="9" id="phone" type="text" required="required"  name="phone" value="{{$pharmacy->phone?$pharmacy->phone:'04'}}" class="form-control"  onkeypress="return restrictAlphabets(event);"  placeholder="Phone"/>
                                    </div>
                                

                                    <div class="form-group col-md-12">
                                <label for="email">Email </label>
                                    <input type="text"  value="{{$pharmacy->email}}"  readonly class="form-control" placeholder="Business Email"/>
                                </div>
                               
                               
                                <div class="form-group col-md-12">
                                <label for="email">{{__('Username')}}</label>
                                    <input type="text"  readonly value="{{$pharmacy->username}}" required="required" class="form-control" placeholder="username"/>
                                </div>

                                <div class="form-group col-md-12">
                                <label for="pin">{{__('Pin')}} </label>
                                    <input type="text" maxlength="4" value="{{$pharmacy->pin}}" id="pin" minlength="4" onkeypress="return restrictAlphabets(event);"    value="{{old('pin')}}" name="pin" class="form-control" placeholder="Pin"/>
                                </div>
                                <div class="form-group col-md-12 icheck checkbox">
                                <label class="form-check-label"><input style="opacity:1 !important;" type="checkbox"
                                        class="form-check-input" name="term" required="required" @if(old('term')=='on' )
                                        {{'checked'}} @endif>
                                    I agreed to the Packpeaks <a href="{{url('term_condition')}}" target="blank">Terms, Conditions</a> and <a href="{{url('privacy_policy')}}" target="blank">Privacy
                                        Policy</a></label>
                            </div>
                                
                                <button type="submit" class="btn btn-primary btn-flat">Update</button>
                                <!-- <a href="{{url('/')}}" class="text-center">I already have a membership</a> -->
    </div>
</div>
                        
                            
                        </div>
</div>
                        </div>
                        </div>


                    </form>     
               


        
          </div>



         

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
                <input type="hidden"  id="latitude" placeholder="Latitude"/>
                <input  type="hidden" id="longitude" placeholder="Longitude"/>
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
   function updatephonenumberlength()
    {
        var inputState = $("#inputState").val();
        if(inputState ==0)
        {
            $("#phone").attr('minlength','10');
            $("#phone").attr('maxlength','10');
        }

        if(inputState >  0 )
        {
            $("#phone").attr('minlength','9');
            $("#phone").attr('maxlength','9');
        }
        
    }
function toggleFavourite() {
    var status = document.getElementById("fvrt").value;
    if (status == '1') {
        document.getElementById("fvrt").value = '0';
        document.getElementById("fvrt-icon").style.color = "black";
        console.log('yakh');

    } else {
        document.getElementById("fvrt").value = '1';
        document.getElementById("fvrt-icon").style.color = "red";
        console.log('Thoooooo');
    }

}

$('#subscription_3').click(function() {
        var subscrip_value = 3;
        $('#subscription').val(subscrip_value);
        $(this).css('background-color', '#0071F2');
        $(this).css('color', 'white');

        $('#subscription_1').css('background-color', 'transparent');
        $('#subscription_2').css('background-color', 'transparent');

        $('#subscription_1').css('color', 'black');
        $('#subscription_2').css('color', 'black');
    });

    $('#subscription_2').click(function() {
        var subscrip_value = 2;
        $('#subscription').val(subscrip_value);
        $(this).css('background-color', '#FF595E');
        $(this).css('color', 'white');
        $('#subscription_1').css('background-color', 'transparent');
        $('#subscription_3').css('background-color', 'transparent');

        $('#subscription_1').css('color', 'black');
        $('#subscription_3').css('color', 'black');
    });

    $('#subscription_1').click(function() {
        var subscrip_value = 1;
        $('#subscription').val(subscrip_value);
        $(this).css('background-color', '#001833');
        $(this).css('color', 'white');
        $('#subscription_2').css('background-color', 'transparent');
        $('#subscription_3').css('background-color', 'transparent');

        $('#subscription_2').css('color', 'black');
        $('#subscription_3').css('color', 'black');
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

      /*Restrict Numeric */
      function restrictNumerics(e){
        var x=e.which||e.keycode; 
        if((x>=65 && x<=90) || x==8 ||
        (x>=97 && x<=122)|| x==95 || x==32)
        return true;
        else
        return false;
      }

      function string_to_slug(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to = "aaaaaeeeeiiiioooouuuunc------";

        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

        return str;
    }
      //  For   Bootstrap  datatable 
      $(function () {
        $('#company_name').keyup(function(){
               $('#host_name').val(string_to_slug($(this).val()));
          }); 
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
    
     google.maps.event.addListener(autocomplete, 'place_changed', function () {
                
        
    
                var place = autocomplete.getPlace();
				$("#i_longitude").val(place.geometry.location.lng());
				$("#i_latitude").val(place.geometry.location.lat());

            });

    ///
    var mapOptions = {
      zoom: 5,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      
      componentRestrictions: {country: "AU"}
    };

    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

// Bounds for North America
var strictBounds = new google.maps.LatLngBounds(
 new google.maps.LatLng(28.70, -127.50),
 new google.maps.LatLng(48.85, -55.90));

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
      if (strictBounds.contains(map.getCenter())) return;

    geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          
          $('#map_address').val(results[0].formatted_address); 
            $('#address').val(results[0].formatted_address);
            $('#latitude').val(marker.getPosition().lat());
            $('#longitude').val(marker.getPosition().lng());

            $('#i_latitude').val(marker.getPosition().lat());
              $('#i_longitude').val(marker.getPosition().lng());
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);

            //find country name
        for (var i=0; i < results[0].address_components.length; i++) {
          for (var j=0; j < results[0].address_components[i].types.length; j++) {
            if (results[0].address_components[i].types[j] == "country") {
              country = results[0].address_components[i];
              console.log(country.long_name)
              console.log(country.short_name)
            }
          }
        }
            
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

      $(function () {

        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });

      });
     


      



    </script>
@endsection
