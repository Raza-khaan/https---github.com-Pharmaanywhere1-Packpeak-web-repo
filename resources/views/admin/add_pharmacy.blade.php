@extends('admin.layouts.mainlayout')
@section('title') <title>Add Pharmacy </title>
@endsection
@section('content')

<!-- TrustBox script -->

<!-- End TrustBox script -->
<!-- Header Wrapper. Contains Header content -->
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

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <div class="pharma-register">
        <h2>Pharmacy Registration</h2>
    </div>
    <form class="report-form" action="{{ url('admin/save_pharmacy') }}" method="post" autocomplete="off">

        <div class="reports-breadcrum">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a  href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Pharmacy</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a type="reset" onclick="resetemail()" class="btn reset-btn" >Reset</a>

                <!-- <input type="reset" id="reset" class="btn reset-btn" value="Reset" /> -->
                <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Register Pharmacy<i
                        class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div>

        </div>

        <!-- Content Header (Page header) -->
        <div class="report-forms">

            <div class="row">
                <div class="col-md-6">
                    @if(Session::has('msg'))
                    {!! Session::get("msg") !!}
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
                    <div class="patient-information">
                        <h3>Pharmacy Information</h3>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-12 register-plan">
                                @if(isset($all_subscription) && count($all_subscription))
                                <input  type ="hidden"  required="required" name="subscription" id="subscription"
                                    class="flat-red d-none" id="subscription" value="" />
                                <a id="subscription_3">Basic Plan</a>
                                <a class="standard" id="subscription_2">Standard Plan</a>
                                <a class="premium" id="subscription_1">Premium Plan</a>
                            </div>
                            @endif
                            <div class="form-group col-md-12 packpeak-set">
                                <label>We will setup Packpeaks for you at:</label>
                                <p>https://www. <input required  type="text" style="border:none;" value="{{old('host_name')}}"
                                        maxlength="20" name="host_name" id="host_name"
                                        autocomplete="host_name"> .packpeak.com.au</p>
                                <input  type="text" style="border:none;" value="{{old('fvrt')}}" maxlength="50"
                                    name="fvrt" maxlength="50" id="fvrt" autocomplete="fvrt" hidden readonly>
                                <a class="favourt" onClick="toggleFavourite()"><i id="fvrt-icon"
                                        class="fas fa-heart"></i>Add to favorites</a>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Company Name<span>*</span></label>
                                <input type="text" name="company_name" maxlength="20" value="{{old('company_name')}}"
                                    id="company_name" required="required" class="form-control"
                                    placeholder="Centree Health" />
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label>Password:<span>*</span></label>
                                <input type="password" name="password" id="password" value="{{old('password')}}"
                                    required="required" class="form-control" placeholder="*************" />
                            </div>
                            <div class="form-group col-md-12 col-lg-6 confirm-pass">
                                <label>Confirm Password:<span>*</span></label>
                                <input type="password" required="required" value="{{old('password_confirmation')}}"
                                    name="password_confirmation" id="confirm_password" class="form-control"
                                    placeholder="*************" />
                                <i class="fas fa-check-circle" id="toggleConfirmPassword"></i>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address">Address: <a href="#" data-toggle="modal"
                                        data-target="#my_map_Modal" style="cursor: pointer;">Set in maps</a> <span
                                        class="text-danger">*</span></label>
                                <!-- <textarea name="address" required="required" style="resize:none;" id="address"
                                    onFocus="geolocate()" class="form-control" rows="3"
                                    placeholder="Enter address"></textarea> -->

                                    <input name="address" required="required" style="resize:none;" id="address"
                                    onFocus="geolocate()" class="form-control" rows="3"
                                    placeholder="Enter address"/>
                                    
                                <input type="hidden"  id="i_latitude" name="latitude" placeholder="Latitude" />
                                <input  type="hidden" id="i_longitude" name="longitude" placeholder="Longitude" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="patient-information">
                        <h3>Personal Information</h3>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="first_name">First Name<span>*</span></label>
                                <input type="text" value="{{old('first_name')}}"
                                    onkeypress="return restrictNumerics(event);" id="first_name" name="first_name" required="required"
                                    class="form-control" placeholder="First Name" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last Name<span>*</span></label>
                                <input type="text" value="{{old('last_name')}}" required="required"
                                    onkeypress="return restrictNumerics(event);"  id="last_name"name="last_name" class="form-control"
                                    placeholder="Doe" />
                            </div>
                            <div class="col-md-12">
                                <label for="inputState">Phone Number</label>
                            </div>
                            <div class="form-group col-4 col-md-3">
                                <select onchange="updatephonenumberlength()" id="inputState" class="form-control">
                                <option  value="0">(..)</option>    
                                <option selected value="1">+61</option>
                                </select>
                            </div>
                            <div class="form-group col-8 col-md-9">
                                <input id="phone" type="text" required="required" name="phone" class="form-control" 
                                minlength="9"
                                maxlength="9"
                                    onkeypress="return restrictAlphabets(event);" placeholder="Enter number here" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>Email<span>*</span></label>
                                <input type="email" id="txtemail" value="{{old('email')}}" required="required" name="email"
                                    class="form-control" placeholder="john.doe@mail.com" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{__('Username')}} <span>*</span></label>
                             <input    minlegth="6" type="text" id="username" name="username" value="{{old('username')}}" required="required"
                                    class="form-control" placeholder="john.doe343" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{__('Pin')}} <span>*</span></label>
                                <input type="text" maxlength="4" id="pin" minlength="4"
                                    onkeypress="return restrictAlphabets(event);" value="{{old('pin')}}" name="pin"
                                    class="form-control" placeholder="****" />
                            </div>
                            <div class="form-group col-md-12 icheck checkbox">
                                <label class="form-check-label">
                                    <input id="chkagree" style="opacity:1 !important;" type="checkbox"
                                        class="form-check-input" name="term" required="required" @if(old('term')=='on' )
                                        {{'checked'}} @endif>
                                    I agreed to the Packpeaks <a href="{{url('term_condition')}}" target="blank">Terms, Conditions</a> and <a href="{{url('privacy_policy')}}" target="blank">Privacy
                                        Policy</a></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- /.content-wrapper -->

<!-- Modal -->
<div class="modal fade" id="my_map_Modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Select Address</h4>    
            <button type="button" class="close" data-dismiss="modal">&times;</button>
                
            </div>
            <form action="#" method="post">
                {{ csrf_field() }}
                <div class="modal-body" style="padding:0px; ">
                    <input type="hidden" name="event_date" id="event_date" />
                    <div id="myMap" style="height:350px;  width:100%;     position: static; "></div>
                    <input id="map_address" type="text" style="width:600px; display:none; " /><br />
                    <input type="hidden" id="latitude" placeholder="Latitude" />
                    <input type="hidden" id="longitude" placeholder="Longitude" />
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

</script>

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

function resetemail()
{
$("#txtemail").val("");

$('#subscription_3').css('border', '1px solid #0071F2');
$('#subscription_3').css('color', '#0071F2');
$('#subscription_3').css('background-color', 'transparent');


$('#subscription_2').css('border', '1px solid #FF595E');
$('#subscription_2').css('color', '#FF595E');
$('#subscription_2').css('background-color', 'transparent');


$('#subscription_1').css('border', '1px solid #001833');
$('#subscription_1').css('color', '#001833');
$('#subscription_1').css('background-color', 'transparent');

$("#inputState").val("1");
$("#host_name").val("");
$("#password").val("");
$("#confirm_password").val("");
$("#address").val("");
$("#first_name").val("");
$("#last_name").val("");
$("#phone").val("");
$("#pin").val("");
$("#company_name").val("");
$('#chkagree').prop('checked', false);

$("#username").val("");
}


//     restrict Alphabets
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
}

/*Restrict Numeric */
function restrictNumerics(e) {
    var x = e.which || e.keycode;
    if ((x >= 65 && x <= 90) || x == 8 ||
        (x >= 97 && x <= 122) || x == 95 || x == 32)
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
$(function() {
    $('#host_name').keyup(function() {
        var m = string_to_slug($(this).val());

        $('#company_name').val(m);
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

});

/* Start  The  map  Address    Code  */

var options = {

    componentRestrictions: {
        country: "AU"
    }
};
var map;
var marker;
var myLatlng = new google.maps.LatLng(-25.274399, 133.775131);
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();

var placeSearch, autocomplete;



function initialize()
{
    
//var autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), options);


///
// var mapOptions = {
//  zoom: 5,
//  center: myLatlng,
//  mapTypeId: google.maps.MapTypeId.ROADMAP,

//  componentRestrictions: {country: "AU"}
// };

// map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

// marker = new google.maps.Marker({
//  map: map,
//  position: myLatlng,
//  draggable: true
// });

// geocoder.geocode({'latLng': myLatlng }, function(results, status) {
//  if (status == google.maps.GeocoderStatus.OK) {
//      if (results[0]) {
//          $('#latitude,#longitude').show();
//          $('#map_address').val(results[0].formatted_address);
//          // $('#address').val(results[0].formatted_address);
//          $('#latitude').val(marker.getPosition().lat());
//          $('#longitude').val(marker.getPosition().lng());
//          infowindow.setContent(results[0].formatted_address);
//          infowindow.open(map, marker);
//      }
//  }
// });

// google.maps.event.addListener(marker, 'dragend', function() {

// geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
//  if (status == google.maps.GeocoderStatus.OK) {
//    if (results[0]) {
//        $('#map_address').val(results[0].formatted_address);
//        $('#address').val(results[0].formatted_address);
//        $('#latitude').val(marker.getPosition().lat());
//        $('#longitude').val(marker.getPosition().lng());
//        infowindow.setContent(results[0].formatted_address);
//        infowindow.open(map, marker);
//    }
//  }
// });
// });

}

function initialize() {

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

        componentRestrictions: {
            country: "AU"
        }
    };

    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

    marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        draggable: true
    });

    geocoder.geocode({
        'latLng': myLatlng
    }, function(results, status) {
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

        geocoder.geocode({
            'latLng': marker.getPosition()
        }, function(results, status) {
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
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
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
<script>


function toggleFavourite() {
    var status = document.getElementById("fvrt").value;
    if (status == '1') {
        document.getElementById("fvrt").value = '0';
        document.getElementById("fvrt-icon").style.color = "pink";
        console.log('yakh');

    } else {
        document.getElementById("fvrt").value = '1';
        document.getElementById("fvrt-icon").style.color = "red";
        console.log('Thoooooo');
    }

}
</script>
@endsection