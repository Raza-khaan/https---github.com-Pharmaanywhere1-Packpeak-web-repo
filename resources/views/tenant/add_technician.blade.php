@extends('tenant.layouts.mainlayout')
@section('title') <title>Add User/Admin</title>
@endsection

@section('content')



 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <div class="reports-breadcrum" style="margin-bottom:0px">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"  ><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add New User</li>
    </ol>
</nav>


</div>

        <!-- Main content -->
        <div class="report-forms">

          <div class="row">
              <div class="col-md-6 m-auto">
              <!-- general form elements -->
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
                    <form id="frmreset" action="{{ url('add_technician') }}" method="post" >  <!--dashboard-->
                        {{ csrf_field() }}
                        <div class="row">
                        <div class="col-md-6">
                               <div class="form-group">
                                <label for="first_name">{{__('First Name')}}<span style="color:red">*</span></label>
                                  <input type="text" onkeypress="return restrictNumerics(event);" name="first_name" required value="{{old('first_name')}}" class="form-control @error('first_name') is-invalid @enderror" placeholder="First Name"/>
                                  @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                                </div>
                                <div class="form-group">
                                <label for="last_name">{{__('Last Name')}}<span style="color:red">*</span></label>
                                    <input type="text" required onkeypress="return restrictNumerics(event);" value="{{old('last_name')}}" name="last_name" class="form-control @error('last_name') is-invalid @enderror"  placeholder="Last Name"/>
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
                                      <!-- <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>&nbsp;
                                      </div>
                                      <div class="input-group-addon">
                                        +04
                                      </div> -->
                                      
                                      <input type="text" required value="" name="phone" class="form-control @error('phone') is-invalid @enderror" maxlength="10" onkeypress="return restrictAlphabets(event);"  placeholder="Phone"/>
                                      <!-- <input type="text" required value="{{old('phone')?old('phone'):'04'}}" name="phone" class="form-control @error('phone') is-invalid @enderror" maxlength="10" onkeypress="return restrictAlphabets(event);"  placeholder="Phone"/> -->
                                      @error('phone')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                      @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                   <label for="address">{{__('Address   Or')}} <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">{{__('set to  map marker')}}</a> <span style="color:red">*</span></label>
                                   
                                   <input name="address" value ="{{old('address')}}"  required style="resize:none;" id="address" 
                                   onFocus="geolocate()" class="form-control @error('address') is-invalid @enderror" 
                                  placeholder="Enter address" />

                                   <!-- <textarea name="address"  required style="resize:none;" id="address" 
                                   onFocus="geolocate()" class="form-control @error('address') is-invalid @enderror" 
                                   cols="30" rows="5" placeholder="Enter address">{{old('address')}}</textarea> -->
                                    
                                   
                                   @error('address')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                <label for="pin">{{__('Username')}}  <span style="color:red">*</span></label>
                                    <input type="text" value="{{old('username')}}"  maxlength="20" minlength="6" required name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username"/>
                                    <!-- <input type="text" value="{{old('username')}}"  maxlength="20" minlength="6" required name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Username"/> -->
                                    @error('username')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>
                                <!-- <a href="{{url('/')}}" class="text-center">I already have a membership</a> -->
                            </div><!-- /.col -->
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="email">{{__('Email')}} <span class="text-danger"></span><span style="color:red">*</span></label>
                                    <input onfocusout="verifyemail()" type="text" required  value="{{old('email')}}" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"/>
                                    @error('email')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    <small id="lblemailerror" 
                                    style="display:none;color:red">the email must be a valid email address </small>
                                </div>
                                <div class="form-group">
                                <label for="password">{{__('Password')}}<span style="color:red">*</span></label>
                                <div class="input-group">
                                    <input type="password" required name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"/>
                                    <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="togglePassword"></i>
                                    </div>
                                    @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                <label for="confirm_password">{{__('Confirm Password')}} <span style="color:red">*</span></label>
                                <div class="input-group">
                                    <input type="password" required name="confirm_password" id="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirm Password"/>
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
                                <label for="pin">{{__('Pin')}}  <span style="color:red">*</span></label>
                                    <input type="text" value="{{isset($pin)?$pin:''}}" onkeypress="return restrictAlphabets(event);" maxlength="4" minlength="4" required name="pin" class="form-control @error('pin') is-invalid @enderror" placeholder="Pin"/>
                                    @error('pin')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div>




                                <div class="form-group ">
                                <label for="roll">{{__('Role Type')}}<span style="color:red">*</span></label>
                                    <select required="required" name="role" class="form-control @error('role') is-invalid @enderror" >
                                      <option value="technician">Technician</option>
                                      <option value="admin">Admin</option>
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
                                    <label><input  {{old('term')?'checked':''}} type="checkbox" value="1" name="term" required class=" @error('term') is-invalid @enderror"> <span style="color:red">*</span> {{__('I agree to the general terms and conditions .')}} .</label>
                                    @error('term')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                </div> 
                                
                                <button id="btnsubmit"  type="submit" class="btn btn-primary btn-flat">{{__('Submit')}}</button>
                                <button id="btnreset"  class="btn reset-btn"  type="reset">Reset</button>
                          </div>
                        </div>
                    </form>
                    </div>
              </div><!-- /.box -->
</div>

          </div>   <!-- /.row -->
</div><!-- /.content -->





      </div><!-- /.content-wrapper -->




      <!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">{{__('Select Address')}}</h4>  
            <button type="button" class="close" data-dismiss="modal">&times;</button>
              


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

function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}

function verifyemail()
{
  
  
    var emailaddress = $("#email").val();
    if( !validateEmail(emailaddress))
    {
        $("#lblemailerror").fadeIn();
        $("#btnsubmit").attr("disabled", true);
        return;
    }
    else
    {
      $("#lblemailerror").fadeOut();
      $("#btnsubmit").attr("disabled", false);
    //   var model = {
		// 		Email: emailaddress,
		// 		_token: '{{csrf_token()}}'
		// 		};
		// 		$.ajax({
    //         type: "POST",
    //         url:"{{url('/verifyuser')}}",
    //           data: JSON.stringify(model),
    //           contentType: "application/json; charset=utf-8",
    //           dataType: "json",
    //           success: function (result) 
    //           {
    //             console.log(result);
    //           }
		// });

    }
}


$("#frmreset").submit(function()
{


});
  
$(document).ready(function()
{
  $("#lblmainheading").html("Add User/Admin");
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

      // $(function () {

      //   $('input').iCheck({
      //     checkboxClass: 'icheckbox_square-blue',
      //     radioClass: 'iradio_square-blue',
      //     increaseArea: '20%' // optional
      //   });

      // });


    </script>


@endsection
