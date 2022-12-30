    @extends('tenant.layouts.mainlayout')
    @section('title') <title>dashboard </title>

    <style type="text/css">
.fc-popover.fc-more-popover
{
  width:30%;
}
.pharmici-info
{
  padding:40px;
}
.fc-title
{
  white-space:normal;
}
    [class^="icon-"], [class*=" icon-"] {
        background-image: url("public/admin/bootstrap/glyphicons/glyphicons-halflings.png");
    }
    .fc-basic-view td.fc-day-number, .fc-basic-view td.fc-week-number span {
          padding-top: 2px;
          padding-bottom: 2px;
          font-size: 12px;
          cursor:pointer;
      }
      div.fc.fc-unthemed  {
          font-size: 60%;
          border: 1px solid #eee;
      }
      div.fc.fc-unthemed h2{
          font-size: 18px
      }
      div.fc.fc-unthemed  th {
        font-size: 12px !important;
    }
    .nav-tabs-custom>.nav-tabs>li.active>a{
      background-color: #3c8dbc !important;
      color:#fff !important;
      font-weight: 800 !important;
    }

    .warning-box{
      -webkit-animation: warns 0.6s linear 0s infinite alternate;
      animation: warns 0.6s linear 0s infinite alternate;
    }


.width100{ width: 100% !important; }
.width100 .ui-datepicker-inline{ width: 100% !important; min-height: 400px; height: auto; }
.ui-widget.ui-widget-content{ display: none !important; }
.datepicker{
  display:none !important;
}

.tooltip > .tooltip-inner {
  border: 1px solid;
  padding: 10px;
  max-width: 450px;
  color: black;
  text-align: left;
  background-color: white;
  background: white;

  opacity: 1.0;
  filter: alpha(opacity=100);
}

#legend {
  color: black;
  padding: 5px 10px;
}
#legend span{
      padding: 5px;
    border-radius: 50%;
}
#legend .red{
  color: #55ce63;
}
.fc-content
{
  color:white;
}

/* #legend .red{
  color: #f56954;
} */
#legend .orange{
  color: #ffbc35;
}

/* #legend .orange{
  color: #f39c12;
} */

 </style>
    @endsection
    @section('content')
    <!-- Content Wrapper. Contains page content -->

    
<!-- <div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Dashboard /Control Panel
        <a class="small-logo-mobile" href="#">
          <img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt="">
        </a>
        <div class="user-menu">
        </div>
    </div>
</div> -->



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notes From Patient</h5>
        <button style="opacity:0.9" type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i style="color:red" class="fa fa-times-circle" aria-hidden="true"></i>
        </button>
      </div>
      <div class="modal-body">
        <p id="lblpatientnotes"></p>
      </div>
     
    </div>
  </div>
</div>
          <div class="content-wrapper">
           
            @php
              $Subscription=App\Models\tenant\AccessLevel::find(1);
            @endphp



            <div class="phrma-info">
            <h2>Admin Dashboard</h2>
            <div class="row">
              <div class="col-md-2">
                <div class="pharmici-info">
                  <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/editing.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$usedsms}}/{{$Allowedsms}}</span>
                      
                      
                      <p>Available SMS</p>
                      <br/>
                      
                      <a href="#">More Info</a>
                    </div>
                  </div>
                </div>
              </div>


              

              @if(!empty(session('phrmacy')['roll_type']) && session('phrmacy')['roll_type']=='admin')
              <div class="col-md-2">
                <div class="pharmici-info">


                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store pharma" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/pharmacy.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allPharmacists}}</span>
                      <p>Admin</p>
                      <br/>
                      
                      <a  href="{{url('all_admin')}}">More Info</a>
                    </div>
                  </div>

                  
                </div>
              </div>
              @else
              <div class="col-md-3">
                <div class="pharmici-info">

                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store pharma" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/pharmacy.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allReturns}}</span>
                      <p>Patients Return</p>
                      <br/>
                      <a  href="{{url('all_returns')}}">More Info</a>
                    </div>
                  </div>
                </div>
              </div>
              @endif



              <div class="col-md-2">
                <div class="pharmici-info">

                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store assist" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/assistant.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allPatients}}</span>
                      <p>Patients</p>
                      <br/>
                      <a  href="{{url('new_patients_report')}}">More Info</a>
                    </div>
                  </div>


                  
                </div>
              </div>


              <div class="col-md-2">
                <div class="pharmici-info">


                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store patient" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/patient.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allTechnicians}}</span>
                      <p>Users</p>
                      <br/>
                      <a  href="{{url('all_technician')}}">More Info</a>
                    </div>
                  </div>


                  
                </div>
              </div>



              <div class="col-md-2" >
                <div class="pharmici-info">


                
                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store" >
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/calendar.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allPickups}}</span>
                      <p>Pickups calender</p>
                      <br/>
                      <a  href="{{url('pickups_calender')}}">More Info</a>
                    </div>
                  </div>

                  
                </div>
              </div>

              <div class="col-md-2" >
                <div class="pharmici-info">

                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                      <div class="store"  style="background-color:#FFCA3A">
                      <img class="img-fluid" src="http://127.0.0.1:8000/admin/images/bell.png" alt="">
                      </div>
                      <br/>
                      <br/>
                      <span style="font-weight:bold">{{$allPickups}}</span>
                      <p>Notifications</p>
                      <br/>
                      <a  href="{{url('pickups_notifications')}}">More Info</a>
                    </div>
                  </div>

                
                </div>
              </div>



              
            </div>
          </div>


           
            <section class="content" style="min-height: 0px; ">
            
             
              <!-- <div class="row">
                <div class="col-lg-1"></div> -->

                <!-- <div class="col-lg-2 col-xs-6" style="background:linear-gradient(to bottom right, #609931, #87bc27);color:white;margin:10px">
                  
                  <div class="small-box bg-#55ce63">
                    <div class="inner">
                      <h3> {{$usedsms}}/{{$Allowedsms}}</h3>
                      <p>Available SMS</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a  href="#"  class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div> -->



               <!-- @if(!empty(session('phrmacy')['roll_type']) && session('phrmacy')['roll_type']=='admin')
                <div class="col-lg-2 col-xs-6" style="background:linear-gradient(to bottom right, #609931, #87bc27);color:white;margin:10px">
                  
                  <div class="small-box bg-#55ce63">
                    <div class="inner">
                      <h3>{{$allPharmacists}}</h3>
                      <p>Admins</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{url('all_admin')}}"  class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
               @else
                <div class="col-lg-2 col-xs-6"  style="background:linear-gradient(to bottom right, #609931, #87bc27);color:white;margin:10px" >
                 
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>{{$allReturns}}</h3>
                      <p>Patients Return</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{url('all_returns')}}" class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
               @endif -->
               <!-- @if($Subscription->form7)
                <div class="col-lg-2 col-xs-6" style="background:linear-gradient(to bottom right, #ef7f00, #f7b200);color:white;margin:10px">
                  
                  <div class="small-box bg-#55ce63">
                    <div class="inner">
                      <h3>{{$allPatients}}</h3>
                      <p>Patients </p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{url('new_patients_report')}}" class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                @endif -->
                <!-- <div class="col-lg-2 col-xs-6" style="background:linear-gradient(to bottom right, #1f6abb, #4f9cf2);color:white;margin:10px">
                 
                  <div class="small-box bg-aqua">
                    <div class="inner">
                      <h3>{{$allTechnicians}}</h3>
                      <p>Users</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{url('all_technician')}}" class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                @if($Subscription->form3)
                <div class="col-lg-2 col-xs-6" style="background:linear-gradient(to bottom right, #0aa361, #1adc88);color:white;margin:10px">
                  
                   <div class="small-box bg-red warning-box">
                    <div class="inner">
                      <h3>{{$allPickups}} </h3>
                      <p class="text">PickUp Calendar</p>
                    </div>
                    <div class="icon">
                      <i class="ion ion-car"></i>
                    </div>

                    <a href="{{url('pickups_calender')}}" class="small-box-footer text-white">More info <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                @endif -->
              

              <!-- </div> -->


            </section><!-- /.content -->


            <!-- Main content -->
            <section class="content" style="margin-bottom:20px">
            <div class="row">
              <div class="col-md-12 alert_message">
              @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
              @endif
              </div>
              </div>
              <div class="row">
                <div class="col-md-12">

                <div class="patient-information">



            <div class="box box-primary">
              <div id="legend">

              <div class="row">

              <p><span class="red"><i class="fa fa-circle"></i></span> Old Pickups</p>
          <p><span class="orange"><i class="fa fa-circle"></i></span> New Pickups</p>
              </div>
        
        </div>
              <div class="box-body no-padding">

                <!-- THE CALENDAR -->
                <div id="calendarD"></div>
                <span class="pull-right text-success text-bold totalPickupOfMonth"> </span>
              </div><!-- /.box-body -->
            </div><!-- /. box -->
          
          </div><!-- /.col -->

              </div><!-- /.row -->
            </section><!-- /.content -->

  </div>

  

          </div><!-- /.content-wrapper -->

          <!-- Modal -->

      @endsection

@section('customjs')



<script src="{{ URL::asset('admin/plugins/jQueryUI/jquery-ui.js') }}" type="text/javascript"></script>  
<script src="{{ URL::asset('admin/plugins/fullcalendar/fullcalendar.min.js') }}"></script>






<script>

$(document).ready(function()
{
  $("#lblmainheading").html("Admin Dashboard");
});
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function init_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    init_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    $('#calendarD').fullCalendar({
      
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      defaultView: 'basicWeek',
      aspectRatio: 1.5,
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      // timeFormat: 'H(:mm)',
      timeFormat: 'h(:mm)a',
      height: 430,
      eventLimit: 2, // allow "more" link when too many events
      eventLimitText: "More",
      //Random default events
      events    : [
      @if(count($pickups) > 0)
        @foreach($pickups as $pickup)
        @if(isset($pickup->patients->first_name) && $pickup->patients->first_name!="")
        @php
          $location_ids=explode(',',$pickup->location);
          $locations=App\Models\Admin\Location::select('name')->whereIn('id', $location_ids)->get();
        @endphp
          {
            id:'{{$pickup->id}}',
            title          : ', {{$pickup->patients->first_name." ".$pickup->patients->last_name .', '.$pickup->patients->address}}',
            start          : '{{$pickup->created_at}}',
            backgroundColor: '#55ce63',
            Color: 'white',
            //red
            //backgroundColor: '#f56954', //red
            // borderColor    : '#f56954',//red
            // url            : "{{url('pickups/show/'.$pickup->id)}}",
            //description: 'Pickup of Patient {{$pickup->patients->first_name." ".$pickup->patients->last_name}}'
            description: '<center><h4>Pickup Details</h4></center>\
            <table style="width:300px !important; ">\
            <tr><th>Patient Name</th>\
            <td>{{$pickup->patients->first_name.' '.$pickup->patients->last_name .' '.$pickup->patients->address }}</td></tr>\
            <tr><th>Dob</td><td>{{\Carbon\Carbon::createFromFormat("Y-m-d", $pickup->dob)->format("d/m/Y")}}</td><tr>\
            <tr><th>Number of Weeks </th><td>{{$pickup->no_of_weeks}}</td></tr>\
            <tr><th>Who is Picking up </th><td>{{$pickup->pick_up_by}}</td></tr>\
            @if($pickup->pick_up_by=="carer")\
            <tr><th>Carer Name </th><td>{{$pickup->carer_name}}</td></tr>\
            @endif\
            <tr><th>Location </th><td>@if(isset($locations) && count($locations))\
                              @php $locationarray=array(); @endphp\
                                 @foreach($locations as $row)\
                                   @php array_push($locationarray,$row->name); @endphp \
                                 @endforeach\
                              {{implode(',',$locationarray)}}\
                            @endif \
            </td></tr>\
            <tr><th>Note From Patient </th><td>{{substr($pickup->notes_from_patient,0,5)}}...</td></tr>\
            <tr><th>Paramcy Signature </th><td><img src="{{$pickup->pharmacist_sign}}" alt="" style="height:40px; width:100px;"></td></tr>\
            <tr><th>Patient Signature </th><td><img src="{{$pickup->patient_sign}}" alt="" style="height:40px; width:100px;"></td></tr>\
            <tr><th>Created Date</td><td>{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$pickup->created_at )->format("d/m/Y")}}</td><tr>\
            </table>'
          },
        @endif
        @endforeach

        @foreach($nextPickupList as $key=>$row)
        @if(isset($row["patients"]["first_name"]) && $row["patients"]["first_name"]!="")
          {
            // title          : '{{$row["patients"]["first_name"]." ".$row["patients"]["last_name"] ." ".$row["patients"]["address"]}}',
            title          : ',{{$row["patients"]["first_name"]." ".$row["patients"]["last_name"] .", ".$row["patients"]["address"]}}',
            start          : '{{$row["created_at"]}}',
            backgroundColor: '#ffbc35',
            signature:'1211',
            // backgroundColor: '#f39c12',
             //red
            // borderColor    : '#f56954',//red
           // description: 'Next schedule Pickup of Patient {{$row["patients"]["first_name"]." ".$row["patients"]["last_name"]}}'
           description: '<center><h4>Pickup Details</h4></center>\
            <table style="width:300px !important; ">\
            <tr><p id="lbltesting">testing</p><th>Patient Name</th>\
            <td>{{$row["patients"]["first_name"].' '.$row["patients"]["last_name"]}}</td></tr>\
            <tr><th>Dob</td><td>{{\Carbon\Carbon::createFromFormat("Y-m-d", $row["dob"])->format("d/m/Y")}}</td><tr>\
            <tr><th>Number of Weeks </th><td>{{$row["no_of_weeks"]}}</td></tr>\
            <tr><th>Who is Picking up </th><td>{{$row["pick_up_by"]}}</td></tr>\
            @if($row["pick_up_by"]=="carer")\
            <tr><th>Carer Name </th><td>{{$row["carer_name"]}}</td></tr>\
            @endif\
            <tr><th>Location </th><td>@if(isset($locations) && count($locations))\
                              @php $locationarray=array(); @endphp\
                                 @foreach($locations as $row2)\
                                   @php array_push($locationarray,$row2->name); @endphp \
                                 @endforeach\
                              {{implode(',',$locationarray)}}\
                            @endif \
            </td></tr>\
            <tr><th>Note From Patient </th><td>{{substr($row["notes_from_patient"],0,5)}}...</td></tr>\
            <tr><th>Paramcy Signature </th><td><img src="{{$row["pharmacist_sign"]}}" alt="" style="height:40px; width:100px;"></td></tr>\
            <tr><th>Patient Signature </th><td><img src="{{$row["patient_sign"]}}" alt="" style="height:40px; width:100px;"></td></tr></table>'

          },
        @endif
        @endforeach
        @endif

      ],

      eventAfterRender: function(info, element) {
          $(element).tooltip({
            title: "info.description",
            placement: 'top',
            trigger: 'hover',
            html: true,
            container: 'body'
          });
      },
      editable  : false,
      droppable : false,
      dayClick: function (date, allDay, jsEvent, view) {
              // alert('Clicked on: ' + date.format());
              var d = new Date();
              var currentDate=moment(d).format('YYYY-MM-DD');
              var clickedDate=date.format();
              if(clickedDate ==  currentDate ){
                 window.location = "{{url('pickups')}}/?day=" + date.format();
              }

      },
      eventClick: function(arg)
      {
        // console.log(arg.event.title);
          var id = arg.id;
          var URL="pickups_reports_calender/"+id;
          window.open(URL, '_blank');
          
      },

      drop      : function (date, allDay) { // this function is called when something is dropped

        // retrieve the dropped element's stored Event Object
        var originalEventObject = $(this).data('eventObject')

        // we need to copy it, so that multiple events don't have a reference to the same object
        var copiedEventObject = $.extend({}, originalEventObject)

        // assign it the date that was reported
        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        // render the event on the calendar
        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
        // $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);



        // is the "remove after drop" checkbox checked?
        if ($('#drop-remove').is(':checked')) {
          // if so, remove the element from the "Draggable Events" list
          $(this).remove()
        }

      },
      viewRender: function(view, element) {
        let newDate= view.calendar.getDate()._d;
        var getDate=moment(new Date(newDate)).format('YYYY-MM-DD');

          console.log(getDate)
          let day = moment(getDate, 'YYYY/MM/DD').date();
          let month = 1 + moment(getDate, 'YYYY/MM/DD').month();
          let year = moment(getDate, 'YYYY/MM/DD').year();
          // console.log(day);
          // console.log(month);
          // console.log(year);
          $.ajax({
            type: "POST",
            url: "{{url('getAllPickupForMonth')}}",
            data: {'month':month,'year':year,"_token":"{{ csrf_token() }}"},
            success: function(result){
              console.log("calender result");
              console.log(result)
              $('.totalPickupOfMonth').html('This Month Pickup :'+result);
            }
          });
      }
    })

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>

@endsection
