
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PeakPack || Pharmacy Registration</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Favicon  -->
    <!-- Bootstrap 3.3.2 -->
    <!-- Font Awesome Icons -->
    <link href="{{ URL::asset('admin/bootstrap/css/style.css')}}" rel="stylesheet" />

    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Theme style -->
    <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" />

    <!-- New Links -->
    <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
   <!-- <link rel="icon" href="{{ URL::asset('admin/icon.png') }}" type="image/x-icon"/> -->
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />    
    <!-- FontAwesome 4.3.0 -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <link href="{{ URL::asset('admin/bootstrap/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 CSS --> 
     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

     <!-- New links end -->
    <style>
      
     .first {
        /* background-image: url("{{ URL::asset('admin/tourpdf/images/backbeauty.jpg') }}"); */
        background:skyblue;
        background-repeat: no-repeat, repeat;
        background-position: center; 
        background-size: cover;  
      }
      .login-box-body{
          padding-top:200px; 
      }
      .back{
        top: 128px;
        left: 620px;
        width: 150px;
        height: 45px;
        border: 1px solid var(--unnamed-color-001833);
        border: 1px solid #001833;
        border-radius: 5px;
        opacity: 1;
      }
      .back-label{
        top: 141px;
        left: 661px;
        width: 94px;
        height: 19px;
        font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 16px/24px var(--unnamed-font-family-product-sans);
        letter-spacing: var(--unnamed-character-spacing-0);
        color: var(--unnamed-color-707070);
        text-align: center;
        font: normal normal normal 16px/24px Product Sans;
        letter-spacing: 0px;
        color: #707070;
        opacity: 1;
      }
      
    </style>
 
  </head>
  <body >
    <div class="container-fluid">

   
            @if(Session::has('msg'))
            {!!  Session::get("msg") !!}
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
      <form method="POST" action="{{ url('add_pharmacist') }}">
        @csrf
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-md-4">
            <div class="row">
              <div class="update-information">
                <div class="notes">
                <div class="back" id="back" style="display:none;"><i class="fas fa-arrow-left"></i><a type="submit" onClick="NextStep()" class="back-label">Previous Step</a></div>
                <div class="text-center"><img  src="{{ URL::asset('admin/images/logo-black.png')}}" alt=""></div>
                  
                <p class="text-center" id="steplabel1">-Step 1/2 Personal Information</p>
                  <div class="row" id="step1">
                     
                  <div class="form-group col-md-12">
                      <label>Email<span>*</span></label>
                      <input type="text" value="{{old('email')}}" required="required" name="email"
                          class="form-control" placeholder="john.doe@mail.com" />

                           
                          <input type="text"    name="address"
                          class="form-control" value="LA LA LAND" hidden/>
                          <input type="text"    name="term"
                          class="form-control" value="1" hidden/>
                  </div>
                  <div class="form-group col-md-12">
                      <label>{{__('Username')}} <span>*</span></label>
                      <input type="text" name="username" value="{{old('username')}}"
                          required="required" class="form-control" placeholder="john.doe343" />
                  </div>
                  <div class="form-group col-md-6">
                      <label for="first_name">First Name<span>*</span></label>
                      <input type="text" value="{{old('first_name')}}"
                          onkeypress="return restrictNumerics(event);" name="first_name"
                          required="required" class="form-control" placeholder="First Name" />
                  </div>
                  <div class="form-group col-md-6">
                      <label>Last Name<span>*</span></label>
                      <input type="text" value="{{old('last_name')}}" required="required"
                          onkeypress="return restrictNumerics(event);" name="last_name"
                          class="form-control" placeholder="Doe" />
                  </div>
                  <div class="form-group col-md-6">
                      <label for="inputState">Phone Number</label>
                      <select id="inputState" class="form-control">
                          <option selected>+61</option>
                          <option>...</option>
                          <option>...</option>
                          <option>...</option>
                      </select>
                  </div>
                  <div class="form-group col-md-6">
                  <label for="inputState"><span>*</span></label>
                  <input type="text" required="required" name="phone" class="form-control"
                                            maxlength="10" onkeypress="return restrictAlphabets(event);"
                                            placeholder="7 7010 1111" />
                  </div>
                  <div class="form-group col-md-12">
                      <label>{{__('Pin')}} <span>*</span></label>
                      <input type="text" maxlength="4" id="pin" minlength="4"
                          onkeypress="return restrictAlphabets(event);" value="{{old('pin')}}"
                          name="pin" class="form-control" placeholder="****" />
                  </div>
                </div>
                <p class="text-center" id="steplabel2" style="display:none;">-Step 2/2 Personal Information</p>
                  <div class="row" id="step2" style="display:none;">
                  <div class="form-group col-md-12 register-plan">
                      @if(isset($all_subscription) && count($all_subscription))
                      <input type="hidden" required name="subscription" id="subscription" class="flat-red d-none"
                          id="subscription" value="" />
                      <a class="basic" id="subscription_3" >Basic Plan</a>
                      <a class="standard" id="subscription_2">Standard Plan</a>
                      <a class="premium" id="subscription_1" >Premium Plan</a>
                      @endif                  
                  </div>
                  <div class="form-group col-md-12 packpeak-set">
                      <label>We will setup Packpeaks for you at:</label>
                      <p>https://www. <input type="text" style="border:none;" value="{{old('host_name')}}"
                               name="host_name" maxlength="10" id="host_name"
                              autocomplete="host_name" placeholder="Host-name"> .packpeak.com.au</p>
                  </div>
                  <div class="form-group col-md-12">
                      <label>Company Name<span>*</span></label>
                      <input type="text" name="company_name" maxlength="50"
                          value="{{old('company_name')}}" id="company_name" required="required"
                          class="form-control" placeholder="Centree Health" />
                  </div>
                  <div class="form-group col-md-12 col-lg-12">
                      <label>Password:<span>*</span></label>
                      <input type="password" name="password" id="password" value="{{old('password')}}"
                          required="required" class="form-control" placeholder="*************" />
                  </div>
                  <div class="form-group col-md-12 col-lg-12 confirm-pass">
                      <label>Confirm Password:<span>*</span></label>
                      <input type="password" required="required" value="{{old('password_confirmation')}}"
                          name="password_confirmation" id="confirm_password" class="form-control"
                          placeholder="*************" />
                      <i class="fas fa-check-circle" id="toggleConfirmPassword"></i>
                  </div>

                  

                  <div class="form-group col-md-12">
                      <button type="submit" class="btn update-btn" >Register Pharmacy</button>
                  </div>
                </form>
                  </div>
                    <div class="form-group col-md-12">
                      <a type="submit" id="next" class="btn update-btn" onClick="NextStep()">Next</a>
                    </div>
                  </div>
                  <div class="row">
                  <div class="form-group col-md-12">
                    <p class="text-center">Already have an account?<a href="#">Login</a></p>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

            <div class="col-sm-2"></div>
        </div>
       
      
    </div><!-- /.login-box -->



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
              <button type="button" class="btn btn-primary" data-dismiss="modal">Select</button>
            </div>
            </form>
          </div>
        </div>
      </div>



    <!-- jQuery 2.1.3 -->
    <script src="{{ URL::asset('admin/plugins/jQuery/jquery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>  
    <!-- iCheck -->
    <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI&libraries=places"></script>
<script type="text/javascript" >
function NextStep(){
  var x = document.getElementById("step2");
  var y = document.getElementById("step1");
  var next = document.getElementById("next");
  var back = document.getElementById("back");
  var steplabel1 = document.getElementById("steplabel1");
  var steplabel2 = document.getElementById("steplabel2");

  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
    next.style.display = "none";
    steplabel1.style.display = "none";
    steplabel2.style.display = "block";
    back.style.display = "block";


  } else if(y.style.display === "none"){
    y.style.display = "flex";
    x.style.display = "none";
    next.style.display = "block";
    steplabel1.style.display = "block";
    steplabel2.style.display = "none";
    back.style.display = "none";
  }
  console.log("a");
}
 //     restrict Alphabets  
 function restrictAlphabets(e){
          var x=e.which||e.keycode; 
          if((x>=48 && x<=57) || x==8 ||
            (x>=35 && x<=40)|| x==46)
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

      $(function () {

          $('#company_name').keyup(function(){
               $('#host_name').val(string_to_slug(($(this).val())));
          }); 

          $('#subscription_3').click(function() {
            var subscrip_value = 1;
            $('#subscription').val(subscrip_value);
            $(this).css('background-color', 'green');
            $('#subscription_1').css('background-color', 'transparent');
            $('#subscription_2').css('background-color', 'transparent');
        });

        $('#subscription_2').click(function() {
            var subscrip_value = 2;
            $('#subscription').val(subscrip_value);
            $(this).css('background-color', 'green');
            $('#subscription_1').css('background-color', 'transparent');
            $('#subscription_3').css('background-color', 'transparent');
        });

        $('#subscription_1').click(function() {
            var subscrip_value = 3;
            $('#subscription').val(subscrip_value);
            $(this).css('background-color', 'green');
            $('#subscription_2').css('background-color', 'transparent');
            $('#subscription_3').css('background-color', 'transparent');
        });

        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });


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
/* END OF THE MAP CODE  */

     
    </script>
  </body>
</html>