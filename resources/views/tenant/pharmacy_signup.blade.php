
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PeakPack || Pharmacy Registration</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Favicon  -->
    <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ URL::asset('admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Theme style -->
    <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" />
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

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                   <h1>New Pharmacy Registration </h1>
                    <form action="{{ url('register') }}" method="post" autocomplete="off">  <!--dashboard-->
                        {{ csrf_field() }}
                        
                        
                        <div class="row">
                            <div class="col-xs-6">  
                               <div class="form-group">
                                <label for="first_name">First Name</label>
                                    <input type="text"  name="first_name" class="form-control" placeholder="First Name"/>
                                </div>
                                <div class="form-group">
                                <label for="last_name">Last Name</label>
                                    <input type="text"  name="last_name" class="form-control" placeholder="Last Name"/>
                                </div>
                                <div class="form-group">
                                <label for="email">Email (<span class="text-danger">this email  will  be  use for pharmacy login also</span>)</label>
                                    <input type="text"  name="email" class="form-control" placeholder="Business Email"/>
                                </div>
                                <div class="form-group">
                                <label for="password">Password</label>
                                    <input type="password"  name="password" class="form-control" placeholder="Password"/>
                                </div>
                                <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                    <input type="password"  name="password_confirmation" class="form-control" placeholder="Confirm Password"/>
                                </div>
                                <div class="checkbox icheck">
                                    <label> <input type="checkbox" name="term"> I confirm that I have read the <a href="#" class="">privacy policy</a> and agree to the processing of my personal data by PeakPack for the stated purposes.Furthermore, by sending the form I agree to the general <a href="#" class="">terms and conditions</a>  . </label>
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
                                      <input type="text"  name="phone" class="form-control" maxlength="10" onkeypress="return restrictAlphabets(event);"  placeholder="Phone"/>
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
                                    <input type="text"  name="company_name" maxlength="50" id="company_name" class="form-control" placeholder="Company Name"/>
                                </div>
                                <div class="form-group" id="host-name-div" style="">
                                    <label>We will setup packnpeaks for you at:</label><br>
                                    https://www.<input type="text" style="border:none; " maxlength="50" name="host_name" maxlength="50" id="host_name"  autocomplete="host-name">.packpeak.com.au
                                    
                                </div>
                                <div class="form-group">
                                <label for="address">Address   Or <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">set to  map marker</a> </label>
                                   <textarea name="address" style="resize:none;" id="address" onFocus="geolocate()" class="form-control" cols="30" rows="3" placeholder="Enter address"></textarea>
                                </div>
                                
                                
                               <button type="submit" class="btn btn-primary btn-flat">Register </button>
                            </div><!-- /.col -->
                        </div>
                    </form>
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
    <script src="{{ URL::asset('admin/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI&libraries=places"></script>
<script type="text/javascript" >

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