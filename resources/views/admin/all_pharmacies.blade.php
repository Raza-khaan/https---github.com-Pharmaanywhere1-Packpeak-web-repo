@extends('admin.layouts.mainlayout')
    @section('title') <title>dashboard </title> 
 <style>
  .mapposition
{
  font-size:12px !important;
  background-color:#5796cf;
  color:white !important;
  padding:4px;

}
   .form-control-sm{
    background-color: #F5F6FA !important;
    font-size: 16px !important;
    color: #707070 !important;
    line-height: 24px !important;
    font-family: 'Product-Sans-Regular' !important;
    margin-top: 20px !important;
    border: none !important;
    padding-left: 40px !important;
    
   }
   div.dataTables_wrapper div.dataTables_filter input{
     margin-left: -4em !important;
     width: 290px !important;
   }
   .main-box{
    top: 389px;
    left: 1152px;
    width: 396px;
    height: 350px;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    box-shadow: 0px 3px 20px #0000000F;
    border-radius: 15px;
    opacity: 1;
   }
   .top-box-dark{
    top: 389px;
    left: 1152px;
    width: 396px;
    height: 100px;
    background: var(--unnamed-color-001833) 0% 0% no-repeat padding-box;
    background: #001833 0% 0% no-repeat padding-box;
    border-radius: 15px;
    opacity: 1;
   }
   .circle-image{
    top: 414px;
    left: 1177px;
    width: 48px;
    height: 48px;
    opacity: 1;
   }
   .pharmacy-title{
    padding-top: 20px;
    top: 414px;
    left: 1240px;
    width: 266px;
    height: 25px;
    font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 21px/32px var(--unnamed-font-family-product-sans-medium);
    letter-spacing: var(--unnamed-character-spacing-0);
    text-align: left;
    font: normal normal normal 21px/32px Product Sans Medium;
    letter-spacing: 0px;
    color: #FFFFFF;
    text-transform: capitalize;
    opacity: 1;
   }
   .dist-text{
    position:fixed;
    top: 50px;
    left: 100px;
    height: 19px;
    font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 16px/24px var(--unnamed-font-family-product-sans);
    letter-spacing: var(--unnamed-character-spacing-0);
    text-align: left;
    font: normal 16px Product Sans;
    letter-spacing: 0px;
    color: #FFFFFF;
    opacity: 1;
   }
   .white-dot{
    width: 6px;
    height: 6px;
    background-color: #FFFFFF;
    border-radius: 50%;
    display: inline-block;
    margin: 0px 10px;
   }
   .gm-style .gm-style-iw-c{
    overflow:hidden !important;
   }
   .gm-style .gm-style-iw-d{
    overflow:hidden !important;
   }
   .gm-style .gm-style-iw-c{
     padding: 0px !important;
     /* background-color:#343a40 !important; */
   }
   .text-white{
     color: white !important;
   }

   .td1{
    font-family:Product Sans,Product Sans;
    color:#707070;
    opacity:1;
    width:33%;
    vertical-align:top;
    font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 16px/24px var(--unnamed-font-family-product-sans);
    letter-spacing: var(--unnamed-character-spacing-0);
    color: var(--unnamed-color-707070);
    text-align: left;
    font: normal normal normal 16px/24px Product Sans;
    letter-spacing: 0px;
    color: #707070;
    text-transform: capitalize;
    opacity: 1;
   }
   .td2{  
    font-family:Product Sans Medium,Regular;
    font-size:16px;
    color:#001833;
    font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 16px/24px var(--unnamed-font-family-product-sans-medium);
letter-spacing: var(--unnamed-character-spacing-0);
color: var(--unnamed-color-001833);
text-align: left;
font: normal normal normal 16px/24px Product Sans Medium;
letter-spacing: 0px;
color: #001833;
text-transform: capitalize;
opacity: 1;
   }

   tr{
     line-height:1.8;
   }
  </style>
    @endsection
    @section('content')
    <!-- Header Wrapper. Contains Header content -->
    <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <div class="pharma-add">
              <a href="#">Pharmacies</a>
              <a class="ad-p" href="{{url('admin/add_pharmacy')}}">Add Pharmacy</a>
            </div>
            <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
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


    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          <div class="pharma-add pharma-add-mobile">
              <a href="#">Pharmacies</a>
              <a href="#">Add Pharmacy</a>
          </div>

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a  href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Pharmacies List</li>
            </ol>
          </nav>
            <!-- Content Header (Page header) -->
          <div class="pharma-location">
            <div class="row">
              <div class="col-md-6 col-lg-6 col-xl-4">
                <div class="pharma-search">
                  <img src="{{ URL::asset('admin/images/logo-black.png')}}" alt="">
                  <div class="map-search">
                    <!-- <input type="text" class="form-control" placeholder="Search"> -->
                    <img src="{{ URL::asset('admin/images/Icon-search.png')}}" alt="">
                  </div>
                  <div class="pharma-loc-wrap ">
                  <table id="example2">
                  <thead style="display:none;">
                        <tr>
                          <th scope="col"></th>
                        </tr>
                  </thead>
                  <!-- <input type="hidden" id="name" value="{{$all_pharmacies}}"> -->
                  <tbody>
                    <?php
                    $i = 0;
                    ?>
                  @foreach($all_pharmacies as $row)
                  <tr> 
                    <td class="pharma-loc">
                      <div class="pharma-nam-box">
                        <p>
                        <?php 
                        echo $name_box = $row['name'][0];
                        ?>
                        </p>
                      </div>
                      <div class="pharmacy-name">
                        <p class="pharma-title"><a href="#"onclick="myClick(<?= $i ?>);">{{$row['name']}}</a></p>
                        <span id="dist_<?= $i; ?>"></span>
                        <span class="open-dot"></span><span class="open-til">Open 'til 6 pm</span>
                      </div>
                    </td>
                  </tr>
                  <?php
                    $i++;
                  ?>
                    @endforeach
                  </tbody>
                </table>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-6 col-xl-8">
                <div id="map-responsive">
                </div>
              </div>
            </div>
          </div>
      </div>
          
          <!-- /.content-wrapper -->

      @endsection

    @section('customjs')

   

</script>
<script type="text/javascript">
  var geocoder;
  var map;
  
  var markers = [];

  $('document').ready(function(){
        

      });

  function initMap(address) {


    
     // pick center coordinates for your map
     geocoder = new google.maps.Geocoder();
      // create map and say which HTML element it should appear in
      var myMapCenter = {lat: -37.81329490165279, lng:144.97151439671367};
       map = new google.maps.Map(document.getElementById('map-responsive'), { 
        center: myMapCenter,
        zoom: 14
      });


      // start
        var infowindow = new google.maps.InfoWindow();
        var locations = [];
      $.ajax({
            url: "<?php echo 'http://127.0.0.1:8000/admin/all_pharmacies_ajax'; ?>",
            dataType:"JSON",
            type: "get",
            success: function(response){

            console.log(response["all_pharmacies"]);

            var len = response["all_pharmacies"].length;

            // starting for loop to create pins on map
            for(var i=0; i<len; i++){
                    var dist = '#dist_'+i;
                    var registration_no = response["all_pharmacies"][i].registration_no;
                    
                    var company_name = response["all_pharmacies"][i].company_name;
                    var host_name = response["all_pharmacies"][i].host_name;
                    var email = response["all_pharmacies"][i].email;
                    var phone = response["all_pharmacies"][i].phone;
                    var website = response["all_pharmacies"][i].host_name;
                    var address = response["all_pharmacies"][i].address;
                    var latitude1 = response["all_pharmacies"][i].latitude;
                    var longitude1 = response["all_pharmacies"][i].longitude;
                    var content = '';
                    var latitude2 = 40.785091;
                    var longitude2 = -73.968285;
                    var meters = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(latitude1, longitude1), new google.maps.LatLng(latitude2, longitude2));
                    var miles  = meters * 0.000621;
                    $(dist).text(Math.round(miles)+ " mi");
                    
                    content = '<div class="main-box">';
                    content += '<div class="top-box-dark">';
                    content += '<div class="pharma-nam-box" style="margin:25px;"><p>A</p></div>';
                    content += '<div class="pharmacy-title">'+company_name+'</div>';
                    content += '<div class="dist-text"><span>'+Math.round(miles)+' mi</span><span class="white-dot"></span><span>Open til 9 pm</span></div>';
                    content += '</div>';
                    content += '<div class="top-box-white"></div>';
                    content += '<table class="ml-4 mt-4"> <tr><td class="text-left td1">Registeration No:</td><td class="text-left td2"><b>'+registration_no+'</b></td></tr> <tr><td class="text-left td1">Host Name:</td><td class="text-left td2"><b>'+host_name+'</b></td></tr><tr><td class="text-left td1">Email:</td><td class="text-left td2"><b>'+email+'</b></td></tr> <tr><td class="text-left td1">Phone:</td><td class="text-left td2"><b>'+phone+'</b></td></tr><tr><td class="text-left td1">Website:</td><td class="text-left td2"><b>'+website+'</b></td></tr><tr><td class="text-left td1">Address:</td><td class="text-left td2"><b>'+address+'</b></td></tr> </table>';
                    content += '</div>';
                    locations.push([content,latitude1,longitude1]);

                    var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(latitude1, longitude1),
                            map: map,
                            label: {
                            color: 'black',
                            fontWeight: 'bold',
                            text: company_name,
                            className:'mapposition'
                            },
                            
                            icon: {
                            labelOrigin: new google.maps.Point(11, 50),
                            url: 'default_marker.png',
                            size: new google.maps.Size(22, 40),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(11, 40),
                            },

  
                        });
                
            //             var img = document.createElement('img');
						// img.src = "https://img.freepik.com/free-vector/home-icon-pin-deal-isolated-white_1284-48167.jpg?size=338&ext=jpg";
						// marker.setIcon(img.src)

                        marker.addListener('click', function() {
                        var infowindow = new google.maps.InfoWindow({
                          content: '<div class="top-rect pharma-loc text-white ml-4">\
                          <div class="pharma-nam-box"><p>A</p></div><div class="pharmacy-name">\
                          <p class="pharma-title text-white">'+company_name+'</p>\
                          <span class="dist text-white">'+Math.round(miles)+' mi</span>\
                          <span class="open-dot text-white"></span><span class="open-til text-white">\
                          Open til 6 pm</span></div></div><div class="bg-white"><table class="ml-4">\
                          <tr><td class="text-left td1">Registeration No:</td><td class="text-left td2">\
                          <b>'+registration_no+'</b></td></tr> <tr><td class="text-left td1">Host Name</td>\
                          <td class="text-left td2"><b>'+host_name+'</b></td></tr><tr><td class="text-left td1">Email</td>\
                          <td class="text-left td2"><b>'+email+'</b></td></tr> <tr><td class="text-left td1">Phone</td>\
                          <td class="text-left td2"><b>'+phone+'</b></td></tr><tr><td class="text-left td1">Website</td>\
                          <td class="text-left td2"><b>'+website+'</b></td></tr><tr><td class="text-left td1">Address</td>\
                          <td class="text-left td2"><b>'+address+'</b></td></tr> </table></div>',
                        });
                        // infowindow.open(map, marker);
                        
                      });
                      
                      markers.push(marker);
  
                }// End for loop to create pins on map
                
                // on name click close information windows
                google.maps.event.addListener(map, 'click', function() {
                    infowindow.close();
                });

                for (var i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                icon: locations[i][3]
                });

                
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() 
                {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
                    }) (marker, i));

                    // Push the marker to the 'markers' array
                    markers.push(marker);
                }

                
              }
            });
      //end
      //Get User info
    
      }
    //  google.maps.event.addDomListener(window, 'load', initMap);
      
          

      function myClick(id) {
        
        
        map.setCenter(markers[id].getPosition());
        map.setZoom(14);
        google.maps.event.trigger(markers[id], 'click');
      }

        

      $(function () {
$('#example2').DataTable( {
    lengthChange: false,
    sorting:false,
    "paging": false,
    searching:true,
    language: {
        searchPlaceholder: "Search"
    },
    dom: '<"top"f>rt<"bottom">',
    // dom: 'f<>Brtpl',
     //select: true,
});

});
</script>

<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> 
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI&libraries=places&callback=initMap&libraries=geometry"></script>

@endsection

