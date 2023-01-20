@extends('tenant.layouts.mainlayout')
@section('title') <title>Update Technician</title>
@endsection

@section('content')

<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>{{__('Update')}} {{__('Technician')}} </h2>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">
        </div>
    </div>
</div>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <div class="reports-breadcrum" style="margin-bottom:0px">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit  User</li>
    </ol>
</nav>


</div>

        <!-- Main content -->
        <div class="report-forms">
          <div class="row">
          
              <!-- general form elements -->
              <div class="col-md-6 m-auto">
              <div class="patient-information">
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
              <div class="row">
                    <form action="{{ url('technician/edit/'.$technician->id) }}" method="post" >  <!--dashboard-->
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                               <div class="form-group">
                                <label for="first_name">{{__('First Name')}}<span style="color:red">*</span></label>
                                  <input type="text" onkeypress="return restrictNumerics(event);" name="first_name" required value="{{$technician->first_name}}" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name"/>
                                  @error('first_name')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                </div>
                                <div class="form-group">
                                <label for="last_name">{{__('Last Name')}}<span style="color:red">*</span></label>
                                    <input type="text" required onkeypress="return restrictNumerics(event);" value="{{$technician->last_name}}" name="last_name" class="form-control @error('last_name') is-invalid @enderror"  placeholder="Last Name"/>
                                    @error('last_name')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                               
                                <div class="form-group">
                                    <label for="phone">{{__('Phone')}}<span style="color:red">*</span></label>
                                    <div class="form-group">
                                    <!-- <div class="input-group"> -->
                                     <!--  <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>&nbsp;
                                      </div> -->
                                      <!-- <div class="input-group-addon">
                                        +04
                                      </div> -->
                                      <input minlength="10" maxlength="10" type="text" required value="{{$technician->phone}}" name="phone" class="form-control @error('phone') is-invalid @enderror"  onkeypress="return restrictAlphabets(event);"  placeholder="Phone"/>
                                      @error('phone')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                   <label for="address">{{__('Address   Or')}} <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">{{__('set to  map marker')}}</a> <span style="color:red">*</span></label>
                                   <input name="address"  required style="resize:none;" id="address" onFocus="geolocate()" class="form-control @error('address') is-invalid @enderror" placeholder="Enter address" value="{{$technician->address}}" />
                                    @error('address')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                <label for="pin">{{__('Pin')}}  </label>
                                    <input type="text" value="{{$technician->pin}}" onkeypress="return restrictAlphabets(event);" maxlength="4" minlength="4" required name="pin" class="form-control @error('pin') is-invalid @enderror" placeholder="Pin"/>
                                    @error('pin')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <!-- <a href="{{url('/')}}" class="text-center">I already have a membership</a> -->
                            </div><!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="email">{{__('Email')}} </label>
                                    <input type="email"  value="{{$technician->email}}" readonly disabled class="form-control @error('email') is-invalid @enderror" placeholder="Email"/>
                                    @error('email')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                <label for="password">{{__('Password')}}</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"/>
                                    <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="togglePassword"></i>
                                    </div>
                                    </div>
                                    @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                <label for="confirm_password">{{__('Confirm Password')}} </label>
                                <div class="input-group">
                                    <input type="password"  name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password"/>
                                    <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="toggleConfirmPassword"></i>
                                    </div>
                                    </div>
                                    @error('confirm_password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                <label for="pin">{{__('Username')}}  </label>
                                    <input type="text" readonly disabled value="{{$technician->username}}"  maxlength="20" minlength="6"   class="form-control @error('username') is-invalid @enderror" placeholder="Username"/>
                                    @error('username')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                
                               

                                <div class="form-group">
                                <label for="role">{{__('Role Type')}} <span style="color:red">*</span></label>
                                    <select required="required" name="role" class="form-control @error('role') is-invalid @enderror" >
                                      <option value="technician" @if($technician->roll_type=='technician') selected @endif>Technician</option>
                                      <option value="admin" @if($technician->roll_type=='admin') selected @endif >Admin</option>
                                    </select>
                                    @error('role')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                              
                                
                               
                            </div><!-- /.col -->
                            

                            <div class="col-md-12">
                                <div class="checkbox icheck">
                                    <label><input  checked type="checkbox" value="1" name="term" required class=" @error('term') is-invalid @enderror"> <span style="color:red">*</span> {{__('I agree to the general terms and conditions .')}} .</label>
                                    @error('term')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-flat">{{__('Submit')}}</button>
</div>
                     
                        </div>
                    </form>     
                </div><!-- /.box-header -->

</div>
              </div><!-- /.box -->


          </div>   <!-- /.row -->
</div><!-- /.content -->



         

      </div><!-- /.content-wrapper -->



 
      <!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">{{__('Select Address')}}</h4>
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
              <button type="button" class="btn btn-primary" data-dismiss="modal">{{__('Done')}}</button>
            </div>
            </form>
          </div>
        </div>
      </div>

@endsection


@section('customjs')


<script type="text/javascript">

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
  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(-25.274399,133.775131);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();

  var placeSearch, autocomplete;
  var options = {
    // 
    componentRestrictions: {country: "AU"}
   };

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

      $(function () {

        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });

      });
     

    </script>


@endsection
