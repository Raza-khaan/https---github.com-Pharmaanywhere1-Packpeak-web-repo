@extends('admin.layouts.mainlayout')
@section('title') <title>Update Technician</title>

@endsection





@section('content')
 <!-- Content Wrapper. Contains page content -->
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

    <form class="report-form" action="{{ url('admin/update_technician/'.$technician[0]->website_id.'/'.$technician[0]->id) }}" method="post"> 

      <div class="reports-breadcrum">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a  href="{{url('admin/dashboard')}}">
                     
                      Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a class="btn reset-btn" href="#" onclick="resetvalues()">Reset</a>
                <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Update User<i class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div>

        </div>
 <div class="content-wrapper">
        

        <!-- Main content -->
        <div class="report-forms">

        <div >
        @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
            @endif
            @if($errors->any())
                <div class="col-md-6 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            
                        @endforeach
                    </ul>
                </div>
            @endif
      </div>
          <div class="row">

            
              <!-- general form elements -->

              <div class="col-md-6">
                <div class="patient-information">
                <h3>User Information</h3>

                <form action="{{ url('admin/update_technician/'.$technician[0]->website_id.'/'.$technician[0]->id) }}" method="post" >  <!--dashboard-->
                        {{ csrf_field() }}
                        <div class="row">
                          <input value="{{$technician[0]->website_id}}"  id="companyidreset" hidden/>
                          <input value="{{$technician[0]->username}}" id="usernamereset" hidden/>
                          <input value="{{$technician[0]->pin}}" id="pinreset" hidden/>
                          <input value="{{$technician[0]->roll_type}}"  id="rolltypereset" hidden/>
                          <input  value="{{$technician[0]->first_name}}"  id="firstnamereset" hidden/>
                          <input  value="{{$technician[0]->last_name}}" id="lastnamereset" hidden/>
                          <input value="{{$technician[0]->phone}}" id="phonenumberreset" hidden/>
                          <input value ="{{$technician[0]->address}}" id="addressreset" hidden/>
                          <input value="{{$technician[0]->longitude}}" id="longreset" hidden/>
                          <input value="{{$technician[0]->latitude}}" id="latreset" hidden/>

                        <div class="form-group col-md-12">
                                <label for="inputState">Company Name<span>*</span></label>
                                @if(count($all_pharmacies) && isset($all_pharmacies))
                                <select id="company_name" class="form-control" name="company_name" required>
                                    <option value="">Please Select</option>
                                    @foreach($all_pharmacies as $row)
                                    <option value="{{$row->website_id}}" 
                                    @if($row->website_id == $technician[0]->website_id)
                                        {{'selected'}} @endif >{{$row->company_name}} - {{$row->name}}
                                      </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            

                            
                            <div class="form-group col-md-12">
                         <label>   Email <span>*</span></label>
<input type="text"  name ="email" readonly   
value="{{$technician[0]->email}}"  required="required" class="form-control" placeholder="Email"/>



                                </div>



                                
                                <div class="form-group col-md-6 confirm-pass">
                          <label>      Password<span>*</span> </label>
                                <div class="input-group">
                                    <input require type="password" min="10" max="10"  name="password" id="password" class="form-control" placeholder="*************"/>
                                   
                                    <!--<i class="fas fa-check-circle" id="toggleConfirmPassword"></i>-->
                                   
                                   </div>
                                </div>
                                <div class="form-group col-md-6 confirm-pass">
                                <label>      Confirm Password<span>*</span> </label>
                                
                                <div class="input-group">
                                    <input require type="password" min="10" max="10" name="password_confirmation" id="confirm_password" class="form-control" placeholder="*************"/>
                                    
                                    <i class="fas fa-check-circle" id="toggleConfirmPassword"></i>
                                    
                                   </div>
                                </div>

                                <div class="form-group col-md-12">
                                <label for="email">{{__('Username')}} </label>
                                    <input id="txtusername"  type="text"  readonly  value="{{$technician[0]->username}}"  class="form-control" placeholder="username"/>
                                </div>

                                <div class="form-group col-md-12">
                                <label for="pin">{{__('Pin')}} </label>
                                    <input type="text" maxlength="4" id="pin" minlength="4" onkeypress="return restrictAlphabets(event);"   value="{{$technician[0]->pin}}" name="pin" class="form-control" placeholder="Pin"/>
                                </div>

                                

                            <div class="form-group col-md-12">
                                <label for="roll">Role Type <span>*</span></label>
                                <select required="required" id="ddlroletype" name="roll_type"  class="form-control ">
                                @if($technician[0]->roll_type == "technician")
                                       
                                <option selected  value="technician">Technician</option>
                                    <option  value="admin">Admin</option>
                                    @else if($technician[0]->roll_type == "admin")  
                                    <option   value="technician">Technician</option>
                                    <option selected  value="admin">Admin</option>
                                        @endif > 
                                    


                                </select>
                            </div>
                          <!--    <div class="col-md-offset-5 col-md-7">
                                  <div class="form-group">
                                    <label for="company_name">Company Name <span class="text-danger"> * </span></label>
                                    @if(count($all_pharmacies)  && isset($all_pharmacies))
                                      @foreach($all_pharmacies as $row)
                                       @if($row->website_id==$technician[0]->website_id)
                                        <input type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
                                        <option value="{{$row->website_id}}" @if(old('company_name')==$row->website_id)
                                        {{'selected'}} @endif >{{$row->company_name}} - {{$row->name}}</option>
                                      
                                        @endif
                                      @endforeach
                                    @endif
                                  </div>
                              </div> -->
                            
                            

                        </div>
                    </form> 
                </div>
              </div>

              <div class="col-md-6">
                <div class="patient-information">
                <h3>Contact Information</h3>
 
                <div class="row">
                               <div class="form-group col-md-6">
                                <label for="first_name">{{__('First Name')}} <span class="text-danger"> * </span></label>
                                    <input id="txtfirstname" type="text" required="required" onkeypress="return restrictNumerics(event);"  name="first_name" value="{{$technician[0]->first_name}}" class="form-control" placeholder="First Name"/>
                                </div>
                                <div class="form-group col-md-6">
                                <label for="last_name">{{__('Last Name')}}  <span class="text-danger"> * </span></label>
                                    <input id="txtlastname" type="text" onkeypress="return restrictNumerics(event);"  name="last_name" class="form-control" value="{{$technician[0]->last_name}}" placeholder="Last Name"/>
                                </div>

                                <div class="col-md-12">
                                <label for="inputState">Phone Number</label>
                            </div>
                                <div class="form-group col-4 col-md-3">
                                <select onchange ="updatephonenumberlength()" id="inputState" class="form-control">
                                <option  value="0">(..)</option>
                                    <option value="1" selected="">+61</option>
                                </select>
                            </div>
                               
                            <div class="form-group col-8 col-md-9">
                            <input id="txtphone" type="text" value="{{$technician[0]->phone}}"  required="required" name="phone" class="form-control" minlength="9" maxlength="9" onkeypress="return restrictAlphabets(event);"  placeholder="Enter Phone Number"/>
                            </div>
                               
                                <div class="form-group col-md-12">
                                   <label for="address">{{__('Address:')}} <span class="text-danger"> * </span> <a href="#" data-toggle="modal" data-target="#my_map_Modal" style="cursor: pointer;">{{__('set to  map marker')}}</a> </label>
                                   <input id="txtaddress" name="address" required="required" style="resize:none;" id="address" onFocus="geolocate()" class="form-control" placeholder="Enter address" value ="{{$technician[0]->address}}">
                                
                                <input id="txtlongitude" name = "long" hidden   value="{{$technician[0]->longitude}}"/>
                                <input id="txtlatitude" name = "lat"    hidden value="{{$technician[0]->latitude}}"/>
                              
                              </div>
                                <!-- <a href="{{url('/')}}" class="text-center">I already have a membership</a> -->
                            </div><!-- /.col -->
                            <div class="col-xs-6">
                                

                

                                <div class="checkbox icheck">
                                    <label> <input type="checkbox" checked name="term"> <span class="text-danger"> * </span> {{__('I agreed to the Packpeaks Terms, Conditions and Privacy Policy .')}} . </label>
                                </div>
                                
                              <!-- <button type="submit" class="btn btn-primary btn-flat">{{__('Update')}}</button> -->
                            </div><!-- /.col -->
                            </div>
              </div>


                 
                        
                


          </div>   <!-- /.row -->
    </div><!-- /.content -->



         

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
                <div id="myMap" style="height:435px;  width:100%;     position: static; "></div>
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

      </div>

      @endsection

      </form>
@section('customjs')


<script type="text/javascript">


function updatephonenumberlength()
    {

        var inputState = $("#inputState").val();
        if(inputState ==0)
        {
            $("#txtphone").attr('minlength','10');
            $("#txtphone").attr('maxlength','10');
        }

        if(inputState >  0 )
        {
            $("#txtphone").attr('minlength','9');
            $("#txtphone").attr('maxlength','9');
        }
        
    }


$(document).ready(function () 
{
    $("#company_name").select2();
});

function resetvalues()
{
  $("#company_name").val($("#companyidreset").val());
  $("#txtusername").val($("#usernamereset").val());
  $("#pin").val($("#pinreset").val());
  $("#ddlroletype").val($("#rolltypereset").val());
  $("#txtfirstname").val($("#firstnamereset").val());
  $("#txtlastname").val($("#lastnamereset").val());
  $("#txtphone").val($("#phonenumberreset").val());
  $("#txtaddress").val($("#addressreset").val());
  $("#txtlongitude").val($("#longreset").val());
  $("#txtlatitude").val($("#latreset").val());
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

     function restrictNumerics(e){
        var x=e.which||e.keycode; 
        if((x>=65 && x<=90) || x==8 ||
        (x>=97 && x<=122)|| x==95)
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
