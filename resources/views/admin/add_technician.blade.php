@extends('admin.layouts.mainlayout')
@section('title') <title>Add User/Admin</title>


@endsection
@section('content')
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <div class="pharma-add report-add">
            <a href="{{ url('admin/technician') }}" class="active">New User</a>
            <a href="{{ url('admin/all_technician') }}">All Users</a>
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
        <a href="{{ url('admin/technician') }}" class="active">New User</a>
        <a href="{{ url('admin/all_technician') }}">All Users</a>
    </div>
    <form class="report-form" action="{{ url('admin/save_technician') }}" method="post">

        <div class="reports-breadcrum">
        
            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a  href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New User</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a onclick="reset()" class="btn reset-btn" href="#">Reset</a>
                <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Add User<i
                        class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div>

        </div>

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
                        <h3>User Information</h3>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="inputState">Company Name<span>*</span></label>
                                @if(count($all_pharmacies) && isset($all_pharmacies))
                                <select   id="company_name" class="form-control" name="company_name" required>
                                    <option value="">Please Select</option>
                                    @foreach($all_pharmacies as $row)
                                    <option value="{{$row->website_id}}" @if(old('company_name')==$row->website_id)
                                        {{'selected'}} @endif >{{$row->company_name}} - {{$row->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                <input style="display:none"   id="txtcompany" class="typeahead form-control" type="text">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>{{__('Email')}} <span>*</span></label>
                                <input type="text" id="txtemail" name="email" value="{{old('email')}}" required="required"
                                    class="form-control" placeholder="Enter Email" />
                            </div>
                            <div class="form-group col-md-12 col-lg-6">
                                <label>{{__('Password')}}<span>*</span></label>
                                <input type="password" name="password" id="password" value="{{old('password')}}"
                                    required="required" class="form-control" placeholder="*************" />
                            </div>
                            <div class="form-group col-md-12 col-lg-6 confirm-pass">
                                <label>{{__('Confirm Password')}}<span>*</span></label>
                                <input type="password" id="confirm_password" required="required"
                                    value="{{old('password_confirmation')}}" name="password_confirmation"
                                    class="form-control" placeholder="*************" />
                                <i class="fas fa-check-circle" id="toggleConfirmPassword"></i>
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{__('Username')}} <span>*</span></label>
                                <input maxlength="20" type="text" id="username" name="username" value="{{old('username')}}" required="required"
                                    class="form-control" placeholder="john.doe343" />
                            </div>
                            <div class="form-group col-md-12">
                                <label>{{__('Pin')}} <span>*</span></label>
                                <input type="text" maxlength="4" id="pin" minlength="4"
                                    onkeypress="return restrictAlphabets(event);" value="{{old('pin')}}" name="pin"
                                    class="form-control" placeholder="****" />
                            </div>
                            <div class="form-group col-md-12">
                                <label for="roll">Role Type <span>*</span></label>
                                <select required="required" name="role" class="form-control ">
                                    <option value="technician">Technician</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="patient-information">
                        <h3>Contact Information</h3>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>{{__('First Name')}}</label>
                                <input type="text" required="required" onkeypress="return restrictNumerics(event);"
                                   id="first_name" name="first_name" value="{{old('first_name')}}" class="form-control"
                                    placeholder="John">
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{__('Last Name')}}</label>
                                <input type="text" onkeypress="return restrictNumerics(event);" name="last_name"
                                  id="last_name"  class="form-control" value="{{old('last_name')}}" placeholder="Doe">
                            </div>
                            <div class="col-md-12">
                                <label for="inputState">{{__('Phone Number')}}</label>
                            </div>
                            <div class="form-group col-4 col-md-3">
                                <select onchange ="updatephonenumberlength()" id="inputState" class="form-control">
                                <option value="0" >(..)</option>
                                    <option selected value="1">+61</option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-8 col-md-9">
                                <input id="phone" type="text" value="{{old('phone')?old('phone'):''}}" required="required"
                                    name="phone" class="form-control" minlength="9" maxlength="9"
                                    onkeypress="return restrictAlphabets(event);" placeholder="Enter Phone Number">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="address">Address: <a href="#" data-toggle="modal"
                                        data-target="#my_map_Modal" style="cursor: pointer;">Set in maps</a> <span
                                        class="text-danger">*</span></label>
                                <input name="address" required="required" style="resize:none;" id="address"
                                    onFocus="geolocate()" class="form-control" rows="3"
                                    placeholder="Enter address" value="{{old('address')}}"/>

                                    <!-- <textarea name="address" required="required" style="resize:none;" id="address"
                                    onFocus="geolocate()" class="form-control" rows="3"
                                    placeholder="Enter addressss">{{old('address')}}</textarea> -->
                            </div>
                            <div class="form-group col-md-12 icheck checkbox">
                                <label class="form-check-label">
                                    <input
                                    id="chkagree"
                                    style="opacity:1 !important;" type="checkbox"
                                        class="form-check-input" name="term" required="required" @if(old('term')=='on' )
                                        {{'checked'}} @endif>
                                    I agreed to the Packpeaks Terms, Conditions and Privacy Policy
                                    <!-- I agreed to the Packpeaks <a href="">Terms, Conditions</a> and <a href="#">Privacy -->
                                        </label>
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
            <h4 class="modal-title">Select Addresss</h4>    
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


 
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


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

function reset()
{
    $("#company_name").val("");
    $("#company_name").trigger("change");
    $("#txtemail").val("");
    $("#password").val("");
    $("#confirm_password").val();
    $("#username").val("");
    $("#pin").val("");
    $("#first_name").val("");
    $("#last_name").val("");
    $("#phone").val("");
    $("#address").val("");

    $('#chkagree').prop('checked', false); // Unchecks it


}

$(document).ready(function () 
{
    $("#company_name").select2();
});


// $("#txtcompany").autocomplete({

// source: function (request, response) {

//     $.ajax({

//         url: "autocomplete",

//         type: "GET",

//         dataType: "json",

//         data: { prefix: request.term },

//         success: function (data) {

//             response($.map(data, function (item) {



//                 return {
//                     label: item.company_name + ' -  ' + item.name 
//                     // label: 'Name: ' + item.name + ', ID: ' + item.name + ', MC#: ' + item.name

//                     , website_id: item.website_id,
//                 };

//             }))

//         }

//     })

// },

// select: function (event, ui) {
    
//     $("#company_name").val(ui.item.website_id);

// }

// });



//     restrict Alphabets
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
}

function restrictNumerics(e) {
    var x = e.which || e.keycode;
    if ((x >= 65 && x <= 90) || x == 8 ||
        (x >= 97 && x <= 122) || x == 95)
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


$('#company_name').click(function() {
    if ($(this).val()) {
        $.ajax({
            type: "POST",
            url: "{{url('admin/get_parmacydata_by_website_id')}}",
            data: {
                'website_id': $(this).val(),
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(result) {
                $('.loader_company').html('');
                $('.alert_company').html('');
                $('select[id="company_name"]').css('border', 'none');
                //  console.log(result.facility)
                console.log(result.patient)
                //  $('#patient_name').html(result.patient);
                //  $('#store').html(result.store);
                $('#pin').val(result.pin);

            }
        });
    }
});

//  For   Bootstrap  datatable
$(function() {
    // $('#company_name').keyup(function(){
    //        $('#host_name').val(string_to_slug($(this).val())); alert("hi");
    //   });
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


function initialize() {

    autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), options);


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

$(function() {

    // $('input').iCheck({
    //   checkboxClass: 'icheckbox_square-blue',
    //   radioClass: 'iradio_square-blue',
    //   increaseArea: '20%' // optional
    // });

});
</script>
@endsection