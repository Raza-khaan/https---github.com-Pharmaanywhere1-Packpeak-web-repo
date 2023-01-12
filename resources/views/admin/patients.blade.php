@extends('admin.layouts.mainlayout')
@section('title') <title>Add Patients</title>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />


<style>
.d-none {
    display: none !important;
}

.d-block {
    display: block !important;
}

 

</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <div class="pharma-add report-add">
            <a href="#" class="active">New Patient</a>
            <a href="{{url('admin/pickups_reports')}}">Pickups</a>
            <a href="{{url('admin/checkings_report')}}">Checking</a>
            <a href="{{url('admin/all_near_miss')}}">Near Miss</a>
            <a href="{{url('admin/all_returns')}}">Return</a>
            <a href="{{url('admin/all_audits')}}">Audit</a>
            <a href="{{url('admin/notes_for_patients_report')}}">Patient Notes</a>
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
        <a href="#">New Patient</a>
        <a href="#">Pickups</a>
        <a href="#">Checking</a>
        <a href="#">Near Miss</a>
        <a href="#">Return</a>
        <a href="#">Audit</a>
        <a href="#">Patient Notes</a>
    </div>
    <form class="report-form" role="form" id="addPatientForm" action="javascript:void(0)" 
        method="post" enctype="multipart/form-data">

        <!-- <form class="report-form" role="form" id="addPatientForm" action="javascript:void(0)" {{url('admin/save_patient')}}
        method="post" enctype="multipart/form-data"> -->
        <input style="display:none" name="ieseditform"  id ="ieseditform" value="0"/>
        <div class="reports-breadcrum">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Forms</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Patient</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a class="btn reset-btn" onclick="reset()">Reset</a>
                <button type="submit" class="btn reset-btn add-pat-btn btn-flat">Add Patient<i
                        class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div>

        </div>

        <div class="report-forms">
            <div class="row">
                <div class="col-md-6">
                    <div class="alertmsg"></div>
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
                        <h3>Patient Information</h3>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="inputState">Company Name<span>*</span></label>
                                @if(count($all_pharmacies) && isset($all_pharmacies))
                                <select  required  class="form-control" name="company_name" id="company_name">
                                    <option value="">Please Select</option>
                                    @foreach($all_pharmacies as $row)
                                    <option value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @endif

                                <input  style="display:none"  id="txtcompany" class="typeahead form-control" type="text">
                            </div>
                            <div class="form-group col-md-6">
                                <label>First Name</label>
                                <input type="text" onkeypress="return restrictNumerics(event);" class="form-control"
                                  required   maxlength="20" id="first_name" value="{{old('first_name')}}" name="first_name"
                                    placeholder="John">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Last Name</label>
                                <input  required type="text" onkeypress="return  restrictNumerics(event);" class="form-control"
                                    maxlength="20" id="last_name" name="last_name" placeholder="Doe">
                            </div>
                            <div class="col-md-12">
                                <label>Date of Birth</label>
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" name="dob_date" value="{{old('dob_date')}}"
                                    id="dob_date" placeholder="15" onchange="checkDOB()">
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" name="dob_month" value="{{old('dob_month')}}"
                                    id="dob_month" placeholder="09" onchange="checkDOB()">
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" name="dob_year" value="{{old('dob_year')}}"
                                    id="dob_year" placeholder="1990" onchange="checkDOB()">
                            </div>
                            <div class="d-none" id="d_dob">
                                <input  required type="text" class="form-control" max="{{Carbon\Carbon::now()->format('d/m/Y')}}"
                                    name="dob" value="{{old('dob')}}" id="dob">
                            </div>
                            <br>

                            <div class="col-md-12 eg-date">
                                <small class="form-text text-muted">e.g dd/mm/yy</small>
                                <span class="input-group-prepend"></span>
                                <input  required type="text" name="cdob" data-provide="datepicker" 
                                onchange="setDobDate()" aria-hidden="true" id="datepicker" class="datepicker" placeholder="dd/mm/yyyy" onchange="checkDOB()">
                                <!-- <i class="fa fa-2x fa-calendar " aria-hidden="true"   ></i> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Packs Location</label>
                            </div>
                            @if(isset($all_Location) && count($all_Location))
                            @foreach($all_Location as $value)
                            <div class="col-2 col-md-2">
                                <div class="custom-control custom-checkbox">
                                @if ($loop->first)
                                <input  checked class="custom-control-input" id="SafeCheck_<?= $value->id ?>" type="checkbox"
                                        {{ (is_array(old('location')) and in_array($value->id, old('location'))) ? ' checked' : '' }}
                                        name="location[]" value="{{$value->id}}">
                                    <label class="custom-control-label"
                                        for="SafeCheck_<?= $value->id ?>">&nbsp;{{$value->name}}&nbsp;</label>
                                    &nbsp;
                                @else
                                <input   class="custom-control-input" id="SafeCheck_<?= $value->id ?>" type="checkbox"
                                        {{ (is_array(old('location')) and in_array($value->id, old('location'))) ? ' checked' : '' }}
                                        name="location[]" value="{{$value->id}}">
                                    <label class="custom-control-label"
                                        for="SafeCheck_<?= $value->id ?>">&nbsp;{{$value->name}}&nbsp;</label>
                                    &nbsp;
                                @endif
                                    
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="patient-information">
                        <h3>Contact Information</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputState">Phone Number</label>
                            </div>
                            <div class="form-group col-4 col-md-3">
                                <select onchange ="updatephonenumberlength()" id="inputState" class="form-control">
                                <option value="0" >(..)</option>
                                    <option selected value="1">+61</option>
                                </select>
                            </div>
                            <div class="form-group col-8 col-md-9">
                                <input required type="text" class="form-control" value=""
                                    minlength="9" maxlength="9" onkeypress="return restrictAlphabets(event);" id="phone_number"
                                    name="phone_number" placeholder="Enter phone">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Address: <a href="#" data-toggle="modal" data-target="#my_map_Modal">Set in
                                        maps</a></label>
                                <input required type="text" class="form-control" value="{{old('address')}}" id="address"
                                    name="address" placeholder="108-114 Jonson Street, Byron Bay NSW, Australia" />
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputState">Enter Facility<span>*</span></label>
                                <select required id="facility" name="facility" class="form-control">
                                    <option selected value>Please Select</option>
                                    @if(isset($all_facilities))
                                    @foreach($all_facilities as $row)
                                    <option value="{{$row->name}}">{{$row->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group otherinput"></div>
                            <div class="col-md-12 form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="up_delivered" id="up_delivered"
                                        class="custom-control-input minimal" />
                                    <label class="custom-control-label" for="up_delivered">Get a text message when medication is picked up/ delivered</label>
                                </div>
                            </div>
                            <div class="col-md-12 form-group {{old('up_delivered')? 'hey':'d-none'}} " id="same_as">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="same_as_above" id="same_as_above"
                                        class="custom-control-input minimal" />
                                    <label class="custom-control-label" for="same_as_above">Send the message to a
                                        different number</label>
                                </div>
                            </div>
                            <div class="form-group col-4 col-md-4 d-none mobile_no">
                                <select id="inputState" name="code" class="form-control">
                                    <option selected>+61</option>
                                </select>
                            </div>
                            <div class="form-group col-8 col-md-8 d-none mobile_no">
                                <input type="text" class="form-control" maxlength="9" minlenght="9"
                                    onkeypress="return restrictAlphabets(event);" id="mobile_no" name="mobile_no"
                                    placeholder="Enter Mobile Number">
                                    <small id="lblmobilenumber" style="color:red;display:none"> Enter Mobile Number *</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" style="display:none">
                    <div class="notes">
                        <h3>Notes:</h3>
                        <textarea style="resize:none; height:200px !important;"
                            class="form-control note-text"></textarea>
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
        <div class="modal-content" >
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



<!-- Modal for Patient Dob -->
<div class="modal fade" id="patientDob_Modal" role="dialog">
    <div class="modal-dialog modal-xs">
        <div class="modal-content" style="height: 192px;">
            <div class="modal-header">
                <p class="errorfornotchecked text-danger text-center"></p>
                <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                <h4 class="modal-title text-center">Confirmation</h4>
            </div>


            <div class="modal-body text-center" style="padding:0px; ">
                <div class="form-group">
                    <label>
                        <span>I have acknowledging to add patient below 18 year </span>
                        <input type="checkbox" name="accept_age" id="accept_age" class="minimal" />
                    </label>

                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary age_yes">Yes</button>
                    <button type="button" class="btn btn-primary age_no">No</button>
                </div>

            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button> -->
            </div>

        </div>
    </div>
</div>

@endsection


@section('customjs')

<!-- JavaScript to control the actions
         of the date picker -->
   
         <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />

   <script type="text/javascript">

function updatephonenumberlength()
    {
        var inputState = $("#inputState").val();
        if(inputState ==0)
        {
            $("#phone_number").attr('minlength','10');
            $("#phone_number").attr('maxlength','10');
        }

        if(inputState >  0 )
        {
            $("#phone_number").attr('minlength','9');
            $("#phone_number").attr('maxlength','9');
        }
        
    }


function reset()
{
    $("#company_name").val("");
    $("#first_name").val("");
    $("#last_name").val("");
    $("#dob_date").val("");
    $("#dob_month").val("");
    $("#dob_year").val("");
    $("#dob").val("");
    $("#datepicker").val("");
    $("#phone_number").val("");
    $("#address").val("");
    $("#facility").val("");
    $("#txtcompany").val("");
}


$(document).ready(function () 
{
    $("#company_name").select2();
});


    $("#txtcompany").autocomplete({

source: function (request, response) {

    $.ajax({

        url: "autocomplete",

        type: "GET",

        dataType: "json",

        data: { prefix: request.term },

        success: function (data) {

            response($.map(data, function (item) {



                return {
                    label: item.company_name + ' -  ' + item.name 
                    // label: 'Name: ' + item.name + ', ID: ' + item.name + ', MC#: ' + item.name

                    , website_id: item.website_id,
                };

            }))

        }

    })

},

select: function (event, ui) {
    
    $("#company_name").val(ui.item.website_id); 
    $('#company_name').trigger('change');

}

});


function setDobDate() {
            var dob = $("#datepicker").val();

            if(dob != ''){
                const myArr = dob.split("/");

                console.log(dob);
                var dt = new Date(dob);
                console.log("getDay() : " + dt.getDate() ); 
                day = myArr[0];/*dt.getDate();*/
                month = myArr[1];/*dt.getMonth()+1;*/
                year = myArr[2];/*dt.getFullYear();*/
                $('#dob_year').val(year);
                $('#dob_month').val(month);
                $('#dob_date').val(day);

            //     var dob = '<input type="text" class="form-control" max="09/07/2021" name="dob" value="' +
            // dateString + '" id="dob">';
            
        $('#dob').val(dob);
            }
            var dateString = $('#dob_year').val()+'/'+$('#dob_month').val()+"/"+$('#dob_date').val();
            checkDOB(); 
            console.log(dateString);
             
        }
    </script>

<script type="text/javascript">


    function checkDOB()
    {
        console.log("i am aherer");
        if($('#dob_date').val() > 0 ){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in date");
        }
        if($('#dob_month').val() > 0 ){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in month");
        }
        if($('#dob_year').val() > 0){
            var cdob = $('#dob_date').val()+'/'+$('#dob_month').val()+'/'+$('#dob_year').val();
            $("#datepicker").val(cdob);
            console.log("in year");
        }

        if($('#dob_date').val() > 0 &&  $('#dob_month').val() > 0 &&  $('#dob_year').val() > 0){
            var dateString = $('#dob_year').val()+'/'+$('#dob_month').val()+"/"+$('#dob_date').val();
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            console.log( age);
            if(age< 18){
                if(confirm('Patient age is less than 18 years. Do you want to continue?')){

                }else{
                // alert(result.success);
                $('#same_as').css('display',
                    'none !important');
                $("#addPatientForm")[0].reset();
                $('input[type=checkbox]').parent()
                    .removeClass("checked");
                $('#mobile_no').html("");
                $('#same_as').addClass('d-none');
                $('.alertmsg').html(
                    '<div class="alert alert-success"> <strong>Patient</strong> Updated Successfully.</div>'
                );}
            }
        }

        
        console.log("Yahooo"+$('#dob_date').val()+$('#dob_month').val()+$('#dob_year').val());
    }
$(document).ready(function() {
    
    /*  Start of the Add Patient  By  Ajax */
    $("#addPatientForm").on('submit', function(e) {
        // console.log($(this).serialize()+'&_token='+'{{ csrf_token() }}')
        // alert($('#addPatientForm').serialize());
        //     return;
        e.preventDefault();
        if ($('#first_name').val() && $('#last_name').val() && $('#datepicker').val() && $('#company_name')
            .val() && $('#phone_number').val() && $('#facility').val())
            {
            $('.alertmsg').html('');

            var Mobilenumber = $("#mobile_no").val();
            var c = document.getElementById('same_as_above');
            if (c.checked) 
            {
                
                if(Mobilenumber == "")
                {
                    $("#mobile_no").focus();
                    $("#lblmobilenumber").fadeIn();
                    return;    
                }

            } 
            else 
            { 
                $("#lblmobilenumber").fadeOut();
            }
            
          

            $.ajax({
                type: "POST",
                url: "{{url('admin/checkduplicatePatient')}}",
                data: $('#addPatientForm').serialize() + '&_token=' + '{{ csrf_token() }}',
                beforeSend: function() {
                    $('.addPatientBtn').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(result) {
                   
                    if (result == '1') {
                        if (confirm(
                                "The patient with the same record already exists in the system do you want to merge patient’s record?"
                            )) {
                            $.ajax({
                                type: "POST",
                                url: "{{url('admin/update_patient')}}",
                                data: $('#addPatientForm').serialize() +
                                    '&_token=' + '{{ csrf_token() }}',
                                success: function(result) {
                                    
                                    $('.addPatientBtn').html('Add Patient');

                                    // console.log(result.errors);
                                    // alert(result.success);


                                    if (result.success == 1) {
                                        // alert(result.success);
                                        $('#same_as').css('display',
                                            'none !important');
                                        $("#addPatientForm")[0].reset();
                                        $('input[type=checkbox]').parent()
                                            .removeClass("checked");
                                        $('#mobile_no').html("");
                                        $('#same_as').addClass('d-none');
                                        $('.alertmsg').html(
                                            '<div class="alert alert-success"> <strong>Patient</strong> Updated Successfully.</div>'
                                        );
                                    }
                                     else
                                      {
                                        printErrorMsg(result.errors);
                                    }
                                },
                                error: function(result) {
                                    if (result.errors != "") {
                                        printErrorMsg(result.errors);
                                    }
                                }
                            });
                        } else {
                            $('.addPatientBtn').html('Add Patient');
                        }

                    }
                     else if (result == '0') {

                        
                        $.ajax({
                            type: "POST",
                            url: "{{url('admin/save_patient')}}",
                            data: $('#addPatientForm').serialize() + '&_token=' +
                                '{{ csrf_token() }}',
                            success: function(result) {

                                
                                
                                $('.addPatientBtn').html('Add Patient');

                                $('.alertmsg').html(
                                        '<div class="alert alert-success"> <strong>Patient</strong> Added Successfully.</div>'
                                    );
                                //console.log(result)
                                console.log(result.errors)
                                
                                if (result.errors == "") {
                                    //  alert(result.success);
                                    $("#addPatientForm")[0].reset();
                                    $('input[type=checkbox]').parent()
                                        .removeClass("checked");
                                    $('#mobile_no').html("");
                                    $('#same_as').addClass('d-none');
                                    $('.alertmsg').html(
                                        '<div class="alert alert-success"> <strong>Patient</strong> Added Successfully.</div>'
                                    );
                                } else {
                                   
                                    printErrorMsg(result.errors);
                                }
                            },
                            error: function(result)
                             {
                                $('.alertmsg').html('<div class="alert alert-success">\
                    <ul class="newalertdata">Added Successfully</ul></div>')
                                // if (result.errors != "") {
                                    
                                //     printErrorMsg(result.errors);
                                // }
                            }
                        });
                    }
                     else {
                        $('.alertmsg').html(
                            '<div class="alert  alert-danger"> Somting went wrong! </div>'
                        );
                    }
                }
            });

        }
         else {
            console.log($('#first_name').val() + $('#last_name').val() + $('#datepicker').val() + $(
                    '#company_name')
                .val() + $('#phone_number').val() + $('#facility').val());

            $('.alertmsg').html('<div class="alert  alert-danger"> All * fields are required !.</div>');
        }
    });
    /*  ENd of the Add Patient  By  AJax */
    var m = $("input[type=checkbox][name='up_delivered']:checked").val();
    if (m == 'undefined') {
        $('#mobile_no').html("");
    } else if (m == '1') {
        console.log(m);
        $('#mobile_no').show();
    }

    $('.age_yes').click(function() {

        if ($("#accept_age").prop('checked') == true) {
            $('.errorfornotchecked').html("");
            $('#patientDob_Modal').modal('toggle');
        } else {
            $('.errorfornotchecked').html('<span >Accept  acknowledgment about age.</span>');
        }

    });
    $('.age_no').click(function() {
        $('#dob_date').val("");
        $('#dob_month').val("");
        $('#dob_year').val("");
        $('#dob').val("");
        $('#patientDob_Modal').modal('toggle');
    });
});

function printErrorMsg(msg) {
    $('.alertmsg').html('<div class="alert alert-danger">\
                    <ul class="newalertdata"></ul></div>')
    $.each(msg, function(key, value) {
        $(".newalertdata").append('<li>' + value + '</li>');
    });
}

function dateDiff(dateold, datenew) {
    var ynew = datenew.getFullYear();
    var mnew = datenew.getMonth();
    var dnew = datenew.getDate();
    var yold = dateold.getFullYear();
    var mold = dateold.getMonth();
    var dold = dateold.getDate();
    var diff = ynew - yold;
    if (mold > mnew) diff--;
    else {
        if (mold == mnew) {
            if (dold > dnew) diff--;
        }
    }
    return diff;
}

$(function() {
    $("#dob_year").focusout(function(event) {
        var year = $(this).val();
        var month = $('#dob_month').val();
        var day = $('#dob_date').val();
        var dateString = day + "/" + month + "/" + year;
        var olday = new Date(dateString);
        var today = new Date();
        console.log(olday);
        var dob = '<input type="text" class="form-control" max="09/07/2021" name="dob" value="' +
            dateString + '" id="dob">';
            
        $('#d_dob').html(dob);
        if (dateDiff(olday, today) < 18) {
            console.log("true");
            event.preventDefault();
            jQuery.noConflict();
            $('#patientDob_Modal').modal('toggle');
        }
    });

    $("input[type=checkbox][name='up_delivered']").change(function(event) {
        
        if ($(this).is(':checked')) {
            $('#same_as').removeClass('d-none');
            
        } else {
            $('#same_as').addClass('d-none');
        }
    });

    $("input[type=checkbox][name='same_as_above']").change(function(event) {
        if ($(this).is(':checked')) {
            $('.mobile_no').removeClass('d-none');
        } else {
            $('.mobile_no').addClass('d-none');
        }
    });

});

/*Other input  */
$('#facility').change(function() {
    if ($(this).val() == '5') {
        $('.otherinput').html(
            '<input type="text" name="other_facility" id="other_facility" class="form-control"  placeholder="Other Facility * ">'
        );
    } else {
        $('.otherinput').html('');
    }
});



function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57))
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

/* get All Patient  List  By  Website id */
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
                //  console.log(result.facility)
                $('#facilitylist').html(result.facility);

            }
        });
    }
});


//  For   Bootstrap  datatable
$(function() {

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
</script>
 
@endsection