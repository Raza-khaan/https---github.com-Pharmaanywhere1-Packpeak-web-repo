@extends('admin.layouts.mainlayout')
@section('title') <title>Packed</title>

<style type="text/css">

input.select2-search__field
{
    height:27px !important;
}
.signature-component {
    text-align: left;
    display: inline-block;
    max-width: 100%;
}

.signature-component h1 {
    margin-bottom: 0;
}

.signature-component h2 {
    margin: 0;
    font-size: 100%;
}

.signature-component button {
    padding: 1em;
    background: transparent;
    box-shadow: 2px 2px 4px #777;
    margin-top: .5em;
    border: 1px solid #777;
    font-size: 1rem;
}

.signature-component button.toggle {
    background: rgba(255, 0, 0, 0.2);
}

.signature-component canvas {
    display: block;
    position: relative;
    border: 1px solid;
}

.signature-component img {
    position: absolute;
    left: 0;
    top: 0;
}
</style>
<style>
.dt-buttons button {
    background: rgb(192, 229, 248) !important;
    border-color: rgb(255, 255, 255) !important;
    color: blue;
    font-weight: italic;
    color: #1f89bb;

    /* right: -1062%;
    bot    tom: 90; */
}

.btn-group,
.btn-group-vertical {
    flex-direction: column !important;
}
</style>
@endsection
@section('content')
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Packed</h2>
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
                         <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My
                            Profile</a>
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

    <div class="pharma-register">
        <h2>Add Checkings</h2>
    </div>
    <form class="report-form" id="form" role="form" action="{{url('admin/save_packed')}}" method="post"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="reports-breadcrum">
                    <div class="row">
                        <div class="col-md-8">
                            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Packed</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add   Pack</li>
                            </ol>
                            </nav>
                            
                        </div>
                       <div class="col-md-4" >
                        <div class="reset-patien">
                        <a type="reset" onclick="reset()" class="btn reset-btn">Reset</a>
                            <a target="_blank" class="btn btn-md btn-primary" style="font-size:17px;color:white;padding:
                            10px 12px"
                                href="{{url('admin/patients')}}">Add Patient <i class="fa fa-plus"></i></a>
                        </div>
                       
                        </div>
                        
                        
            </div>
        </div>


        <!-- general form elements -->
        <div class="col-md-6 m-auto">
            <div class="row">
                <div class="update-information" style="width:100%">
                    <div class="notes">
                        <h3 class="text-center">Add Pack</h3>
                        <div class="col-md-12">
                            
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

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="company_name">Company Name <span class="text-danger">
                                        *</span></label> <span class="loader_company"></span>
                                @if(count($all_pharmacies) && isset($all_pharmacies))
                                <select  required class="form-control" name="company_name" id="company_name">
                                    <option value="">--Select Company --</option>
                                    @foreach($all_pharmacies as $row)
                                    <option @if(old('company_name')==$row->website_id) selected @endif
                                        value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @endif
                                <span class="alert_company"></span>
                                <input  style="height:40px;display:none"   id="txtcompany" class="typeahead form-control" type="text">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="patient_name">Patient Name <span class="text-danger"> *</span>
                                </label><br>
                                <!--<select name="patient_name[]" id="patient_name" class="form-control">
                                </select>-->
                                <select onchange="getchagecount()" required style="height:30px" name="patient_name[]" id="patient_name" class="form-control js-example-basic-multiple"  multiple="multiple">

                                <!-- <select name="patient_name[]" id="patient_name" class="form-control js-example-basic-multiple"  multiple="multiple"> -->
                                     
                                    @if(!empty(old('patient_name')) && !empty(old('patient_name')))
                                    @foreach($getAllPatient as $patient)
                                    <option value="{{$patient->id}}" @if($patient->id==old('patient_name'))
                                        selected @endif >{{$patient->first_name.' '.$patient->last_name}} (
                                        {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>


                        </div>
                        @if(!empty(old('patient_name')))
                        @php
                        $CompanyName=old('company_name');
                        $patientId=old('patient_name');
                        $get_user=App\User::get_by_column('website_id',$CompanyName);
                        config(['database.connections.tenant.database' => $get_user[0]->host_name]);
                        DB::purge('tenant');
                        DB::reconnect('tenant');
                        $getAllPatient=App\Models\Tenant\Patient::get();
                        DB::disconnect('tenant');
                        @endphp

                        {{isset($get)?$get:''}}
                        @endif
                        
                       

                        <div class="row">
                            <!-- <div class="form-group">
                                 <label for="dob">Date Of Birth</label>
                                <input type="date" class="form-control"   name="dob" id="dob" placeholder="Date Of Birth" >
                              </div> -->
                            <div class="form-group col-md-12">
                                <label for="no_of_weeks">Number of Weeks <span class="text-danger"> *</span>
                                </label>
                                <input required type="text" class="form-control" maxlength="3"
                                    onkeypress="return restrictAlphabets(event);" id="no_of_weeks"
                                    value="{{old('no_of_weeks')}}" name="no_of_weeks" placeholder="No Of Weeks">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><label>Location </label></div>
                            <div class="col-md-6">
                                @if(isset($all_Location) && count($all_Location))
                                @foreach($all_Location as $value)
                                
                                    <label>
                                    @if ($loop->first)
                                    <input id="chklocation_{{$value->id}}" checked type="checkbox"
                                            {{ (is_array(old('location')) and in_array($value->id, old('location'))) ? 'checked' : '' }}
                                            name="location[]" class="minimal locationclass "
                                            value="{{$value->id}}" />&nbsp;{{$value->name}} &nbsp;
                                    @else
                                    <input id="chklocation_{{$value->id}}" type="checkbox"
                                            {{ (is_array(old('location')) and in_array($value->id, old('location'))) ? 'checked' : '' }}
                                            name="location[]" class="minimal locationclass "
                                            value="{{$value->id}}" />&nbsp;{{$value->name}} &nbsp;

                                    @endif
                                        
                                    </label>
                                
                                @endforeach
                                @endif
                                
                            </div>
                            <div class="col-md-2" style="">
                            <label for="dd"></label>
                            
                            <label id="ddDiv">
                            <input onchange="checksafe()" type="checkbox" name="dd" id="dd" class="  minimal" value="1">&nbsp;DD                                </label>

                            
                                </div>
                            
                        </div>

                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="pharmacist_signature"> Pharmacist Signature <span class="text-danger">
                                        *</span></label>
                                        <div  class="signature-component btn btn-group">
                                  <!-- <button type="button" id="save">Save</button> -->
                                  <button type="button" id="clear">Clear</button>
                                  <!-- <button type="button" id="showPointsToggle">Show points?</button> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12" >
                                <section class="signature-component" >
                                    <canvas    id="signature-pad"></canvas>
                                    <label id="signaturerequired" style="color:red;display:none">Draw signature*</label>
                                    <textarea    name="pharmacist_signature" id="pharmacist_signature" style="display:none">
                                    </textarea>
                                </section>
                            </div>

                            
                        </div>
                        
                        <!-- textarea -->
                        <div class="row" id="notesdiv">
                            <div class="form-group col-md-12">
                                <label for="note">Note For Patient</label>
                                <textarea class="form-control" style="resize: none;" rows="6" name="note" id="note"
                                    placeholder="Note for Patient ...">{{old('note')}}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn update-btn">Add</button>
                            </div>
                            <div class="form-group col-md-6">
                                <input onclick="reload()"  type="button" id="reset" class="btn reset-btn" value="Reset" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
</div>
</form>
</div>
</div><!-- /.box-header -->
</div><!-- /.box -->



@endsection


@section('customjs')

<script
    src="{{ URL::asset('admin/plugins/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js') }}">
</script>

<script src="{{ URL::asset('admin/plugins/signature/underscore-min.js') }}"></script>


<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


<script type="text/javascript">

function checksafe()
{
    if($('#dd').is(":checked"))
    {
        $('#chklocation_3').prop('checked', true);
    }
    else
    {
        $('#chklocation_3').prop('checked', false);
    }
    
}
function reload()
{
    location.reload();
}

function reset()
{
    $("#company_name").val("");
    $("#company_name").trigger("change");

    $("#patient_name").val("");
    $("#patient_name").trigger("change");

    $("#no_of_weeks").val();
    $("#note").val();
    $("#signature-pad").val();
    $("#pharmacist_signature").val();
    $('.loader_company').html('');
                $('.alert_company').html('');


var clearButton = document.getElementById('clear');

clearButton.addEventListener('click', function(event) {
signaturePad.clear();
document.getElementById('pharmacist_signature').value = "";
});
}

function getchagecount()
{
    var ddvalue = $("#patient_name").val();

    if(ddvalue.length>1)
    {
    $("#notesdiv").fadeOut();
    $("#notesdiv").val("");
    }
    else
    {
        $("#notesdiv").fadeIn();
    }
}

$('#form').submit(function() {
    if ($.trim($("#pharmacist_signature").val()) === "") {
        // alert('you did not fill out one of the fields');
        $("#signaturerequired").fadeIn();
        return false;
    }
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
    $('#company_name').val(ui.item.website_id).trigger('change');

    // set company dropdown value 

    $.ajax({
            type: "POST",
            url: "{{url('admin/get_patients_by_website_id')}}",
            data: {
                'website_id': ui.item.website_id,
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(result) {
                // console.log(result);
                $('.loader_company').html('');
                $('.alert_company').html('');
                $('select[id="company_name"]').css('border', 'none');
                $('#patient_name').html(result);
                $('#patient_name').on('change', function(e) {
                    if (this.value) {
                        var ob = $(this).children('option:selected');
                        var dob = ob.attr('data-dob');
                        var lastPickupDate = ob.attr('data-lastPickupDate');
                        var lastPickupWeek = ob.attr('data-lastPickupWeek');
                        var lastNoteForPatient = ob.attr('data-lastNoteForPatient');
                        var lastLocation = ob.attr('data-lastLocation');
                        var last_pick_up_by = ob.attr('data-last_pick_up_by');
                        var last_carer_name = ob.attr('data-last_carer_name');
                        var last_noteForPatient = ob.attr('data-last_noteForPatient');
                        var last_noteForPatientDate = ob.attr(
                            'data-last_noteForPatientDate');
                        if (dob) {
                            $('#dob').val(moment(dob).format('DD/MM/YYYY'));
                        } else {
                            $('#dob').val("");
                        }
                        if (lastPickupDate) {
                            var lastPickupDateArr = lastPickupDate.split(" ");
                            var lastPickupDateArr = lastPickupDateArr[0].split("-");
                            console.log(lastPickupDateArr);
                            var day = lastPickupDateArr[2];
                            var month = lastPickupDateArr[1];
                            var year = lastPickupDateArr[0];
                            $('#last_pick_up_day').val(day);
                            $('#last_pick_up_month').val(month);
                            $('#last_pick_up_year').val(year);
                            $('#last_pick_up_date').val(moment(lastPickupDate).format(
                                'DD/MM/YYYY'));
                            $('#last_no_of_weeks').val(lastPickupWeek);
                        } else {
                            $('#last_pick_up_day').val("");
                            $('#last_pick_up_month').val("");
                            $('#last_pick_up_year').val("");
                            $('#last_pick_up_date').val("");
                        }
                        if (lastPickupWeek) {
                            $('#weeks_last_picked_up').val(lastPickupWeek);
                        } else {
                            $('#weeks_last_picked_up').val('');
                        }

                        // $('#note').(lastNoteForPatient);
                        if (lastNoteForPatient) {
                            lastNoteForPatient = ' ( ' + lastNoteForPatient + ' )  ';
                        }
                        $('.lastnote').html(lastNoteForPatient);
                        //  if(last_noteForPatient || last_noteForPatientDate){
                        // if (last_noteForPatient) {
                        //     $('.notes_for_patient_div').html(
                        //         '<label for="no_of_weeks">{{__('
                        //         Note For Patient ')}}</label><br/>\
                        //     <textarea name="notes_for_patient" style="resize:none;" readonly id="notes_for_patient" class="form-control" >' +
                        //         last_noteForPatient + ' ( note added ' + moment(
                        //             last_noteForPatientDate).format(
                        //             'DD/MM/YYYY HH:mm:ss A') + ' )</textarea>');
                        // } else {
                        //     $('.notes_for_patient_div').html('');
                        // }

                        if (lastLocation) {
                            let arr = lastLocation.split(',');
                            // console.log(arr.length)
                            if (arr.length) {
                                $('input[type=checkbox]').parent().removeClass("checked");
                                // $('input[type=checkbox]').removeAttr("checked");
                                for (var i = 0; i < arr.length; i++) {
                                    // console.log(arr[i])
                                    // console.log(typeof arr[i])
                                    $('input[type=checkbox][value=' + arr[i] + ']').parent()
                                        .addClass("checked");
                                    $('input[type=checkbox][value=' + arr[i] + ']').attr(
                                        "checked", 'checked');
                                }
                            }
                        } else {
                            $('input[type=checkbox]').parent().removeClass("checked");
                            // $('input[type=checkbox]').removeAttr("checked");
                        }
                        if (last_pick_up_by) {
                            // console.log(last_pick_up_by)
                            $('input[type=radio]').parent().removeClass("checked");
                            // $('input[type=radio]').removeAttr("checked");
                            $('input[type=radio][value=' + last_pick_up_by + ']').parent()
                                .addClass("checked");
                            $('input[type=radio][value=' + last_pick_up_by + ']').attr(
                                "checked", "checked");

                        } else {
                            $('input[type=radio]').parent().removeClass("checked");
                            // $('input[type=radio]').removeAttr("checked");
                        }
                        if (last_carer_name) {
                            $('.div_carer_name').html(
                                '<label for="carer_name">Carer`s Name <span class="text-danger"> *</span></label> <input type="text" class="form-control" maxlength="20" value="' +
                                last_carer_name +
                                '"  id="carer_name" name="carer_name" placeholder="Carer Name..">'
                            );
                        } else {
                            $('.div_carer_name').html("");
                        }
                        // console.log(last_carer_name)

                        if (lastNoteForPatient) {
                            $('.lastnotedate').html(moment(lastPickupDate).format(
                                'DD/MM/YYYY'));
                        } else {
                            $('.lastnotedate').html("");
                        }



                    }


                });
            }
        });

}

});

$(function() {



    $('#patient_name').select2({multiple: true,placeholder: "Select Patient"});
    // $('#patient_name').select2();
    //Datemask yyyy-mm-dd
    // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
    // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){}); 


    //iCheck for checkbox and radio inputs
    // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    //     checkboxClass: 'icheckbox_minimal-blue',
    //     radioClass: 'iradio_minimal-blue'
    // });
    // //Red color scheme for iCheck
    // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
    //     checkboxClass: 'icheckbox_minimal-red',
    //     radioClass: 'iradio_minimal-red'
    // });
    // //Flat red color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //     checkboxClass: 'icheckbox_flat-green',
    //     radioClass: 'iradio_flat-green'
    // });

    $('input[name="dd"]').on('ifChanged', function(event) {
        if ($(this).prop('checked')) {
            $('input[type=checkbox][value=3]').iCheck('check');
        } else {
            $('input[type=checkbox][value=3]').iCheck('uncheck');
        }
    });

    $('input[type=checkbox][value=3]').on('ifChanged', function(event) {
        if ($(this).prop('checked')) {
            $('input[name="dd"]').iCheck('check');
        } else {
            $('input[name="dd"]').iCheck('uncheck');
        }
    });






});

//     restrict Alphabets  
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57) || x == 8 ||
        (x >= 35 && x <= 40) || x == 46)
        return true;
    else
        return false;
}

$('#patient_name').click(function() {
    if ($('#company_name').val() == false) {
        $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>');
        $('select[id="company_name"]').css('border', '1px solid red');
    }
});
$('#dob').click(function() {
    if ($('#company_name').val() == false) {
        $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>');
        $('select[id="company_name"]').css('border', '1px solid red');
    }
});

/* get All Patient  List  By  Website id */
$('#company_name').click(function() {
    if ($(this).val()) {

        $.ajax({
            type: "POST",
            url: "{{url('admin/get_patients_by_website_id')}}",
            data: {
                'website_id': $(this).val(),
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(result) {
                // console.log(result);
                $('.loader_company').html('');
                $('.alert_company').html('');
                $('select[id="company_name"]').css('border', 'none');
                $('#patient_name').html(result);
                console.log(result);
                // $('#patient_name').select2({multiple: true,placeholder: "Select Patient"});
                $('#patient_name').select2(

                ).on('change', function(e) {
                    if (this.value) {
                        var ob = $(this).children('option:selected');
                        var last_CheckingLocation = ob.attr(
                            'data-last_CheckingLocation');
                        var last_CheckingDD = ob.attr('data-last_CheckingDD');
                        let hasDD = false;
                        if (last_CheckingLocation) {
                            let arr = last_CheckingLocation.split(',');
                            if (arr.length) {
                                //$('input[name="location[]"]').parent().removeClass("checked");
                                // $('input[name="location[]"]').removeAttr("checked");
                                $('input[name="location[]"]').iCheck("uncheck");
                                for (var i = 0; i < arr.length; i++) {
                                    $('input[name="location[]"][value=' + arr[i] + ']')
                                        .iCheck('check');
                                    if (arr[i] == 3) {
                                        hasDD = true;
                                    }
                                }
                            }
                        } else {
                            // $('input[name="location[]"]').parent().removeClass("checked");
                            // $('input[name="location[]"]').removeAttr("checked");
                            $('input[name="location[]"]').iCheck('uncheck');
                        }

                        if (last_CheckingDD == '1' || hasDD == true) {
                            // $('input[name="dd"][value=1]').parent().addClass("checked");
                            // $('input[name="dd"][value=1]').attr("checked",'checked');
                            $('input[name="dd"][value=1]').iCheck('check');

                        } else {
                            // $('input[name="dd"]').parent().removeClass("checked");
                            // $('input[name="dd"]').removeAttr("checked");
                            $('input[name="dd"]').iCheck('uncheck');
                        }





                    }


                });


            }
        });
    }
});
/* get  Patienst  Dote of  birth by Patient  id and Website id  */


// $('#patient_name').click(function(){
//        if($(this).val()){
//          alert("sfd"); 
//           $.ajax({
//               type: "POST",
//               url: "{{url('admin/get_patient_dob')}}",
//               data: {website_id:$('#company_name').val(),patient_id:$('#patient_name').val(),"_token":"{{ csrf_token() }}"},
//               beforeSend: function() {
//                 $('.loader_patient').html('<i class="fa fa-spinner fa-spin"></i>');
//               },
//               success: function(result){
//                 $('.loader_patient').html('');
//                 if(result.dob)
//                 {
//                      $('#dob').val(result.dob); 
//                 }

//               }
//           });
//        } 
//     });
</script>

<script id="INLINE_PEN_JS_ID">

$(document).ready(function () {
        $("#company_name").select2();
        });


        $("#company_name").change(function () {
       
       // set company dropdown value 

    $.ajax({
            type: "POST",
            url: "{{url('admin/get_patients_by_website_id')}}",
            data: {
                'website_id': this.value,
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(result) {
                
                console.log(result);
                $('.loader_company').html('');
                $('.alert_company').html('');
                $('select[id="company_name"]').css('border', 'none');
                $('#patient_name').html(result);
                $('#patient_name').on('change', function(e) {
                    if (this.value) {
                        var ob = $(this).children('option:selected');
                        var dob = ob.attr('data-dob');
                        
                        
                        var lastPickupDate = ob.attr('data-lastPickupDate');
                        var lastPickupWeek = ob.attr('data-lastPickupWeek');
                        var lastNoteForPatient = ob.attr('data-lastNoteForPatient');
                        
                        $("#txtlastpatientnotes").val(lastNoteForPatient);
                        var lastLocation = ob.attr('data-lastLocation');
                        var last_pick_up_by = ob.attr('data-last_pick_up_by');
                        var last_carer_name = ob.attr('data-last_carer_name');
                        // var last_noteForPatient = ob.attr('data-last_noteForPatient');
                        
                        var last_noteForPatientDate = ob.attr(
                            'data-last_noteForPatientDate');
                        if (dob) {
                            $('#dob').val(moment(dob).format('DD/MM/YYYY'));
                        } else {
                            $('#dob').val("");
                        }
                        if (lastPickupDate) {
                            var lastPickupDateArr = lastPickupDate.split(" ");
                            var lastPickupDateArr = lastPickupDateArr[0].split("-");
                            console.log(lastPickupDateArr);
                            var day = lastPickupDateArr[2];
                            var month = lastPickupDateArr[1];
                            var year = lastPickupDateArr[0];
                            $('#last_pick_up_day').val(day);
                            $('#last_pick_up_month').val(month);
                            $('#last_pick_up_year').val(year);
                            $('#last_pick_up_date').val(moment(lastPickupDate).format(
                                'DD/MM/YYYY'));
                            $('#last_no_of_weeks').val(lastPickupWeek);
                        } else {
                            $('#last_pick_up_day').val("");
                            $('#last_pick_up_month').val("");
                            $('#last_pick_up_year').val("");
                            $('#last_pick_up_date').val("");
                        }
                        if (lastPickupWeek) {
                            $('#weeks_last_picked_up').val(lastPickupWeek);
                        } else {
                            $('#weeks_last_picked_up').val('');
                        }

                        // $('#note').(lastNoteForPatient);
                        if (lastNoteForPatient) {
                            lastNoteForPatient = ' ( ' + lastNoteForPatient + ' )  ';
                        }
                        $('.lastnote').html(lastNoteForPatient);
                        //  if(last_noteForPatient || last_noteForPatientDate){
                        // if (last_noteForPatient) {
                        //     $('.notes_for_patient_div').html(
                        //         '<label for="no_of_weeks">{{__('
                        //         Note For Patient ')}}</label><br/>\
                        //     <textarea name="notes_for_patient" style="resize:none;" readonly id="notes_for_patient" class="form-control" >' +
                        //         last_noteForPatient + ' ( note added ' + moment(
                        //             last_noteForPatientDate).format(
                        //             'DD/MM/YYYY HH:mm:ss A') + ' )</textarea>');
                        // } else {
                        //     $('.notes_for_patient_div').html('');
                        // }

                        if (lastLocation) {
                            let arr = lastLocation.split(',');
                            // console.log(arr.length)
                            if (arr.length) {
                                $('input[type=checkbox]').parent().removeClass("checked");
                                // $('input[type=checkbox]').removeAttr("checked");
                                for (var i = 0; i < arr.length; i++) {
                                    // console.log(arr[i])
                                    // console.log(typeof arr[i])
                                    $('input[type=checkbox][value=' + arr[i] + ']').parent()
                                        .addClass("checked");
                                    $('input[type=checkbox][value=' + arr[i] + ']').attr(
                                        "checked", 'checked');
                                }
                            }
                        } else {
                            $('input[type=checkbox]').parent().removeClass("checked");
                            // $('input[type=checkbox]').removeAttr("checked");
                        }
                        if (last_pick_up_by) {
                            // console.log(last_pick_up_by)
                            $('input[type=radio]').parent().removeClass("checked");
                            // $('input[type=radio]').removeAttr("checked");
                            $('input[type=radio][value=' + last_pick_up_by + ']').parent()
                                .addClass("checked");
                            $('input[type=radio][value=' + last_pick_up_by + ']').attr(
                                "checked", "checked");

                        } else {
                            $('input[type=radio]').parent().removeClass("checked");
                            // $('input[type=radio]').removeAttr("checked");
                        }
                        if (last_carer_name) {
                            $('.div_carer_name').html(
                                '<label for="carer_name">Carer`s Name <span class="text-danger"> *</span></label> <input type="text" class="form-control" maxlength="20" value="' +
                                last_carer_name +
                                '"  id="carer_name" name="carer_name" placeholder="Carer Name..">'
                            );
                        } else {
                            $('.div_carer_name').html("");
                        }
                        // console.log(last_carer_name)

                        if (lastNoteForPatient) {
                            $('.lastnotedate').html(moment(lastPickupDate).format(
                                'DD/MM/YYYY'));
                        } else {
                            $('.lastnotedate').html("");
                        }



                    }


                });
            }
        });
    });
        /*!
 * Modified
 * Signature Pad v1.5.3
 * https://github.com/szimek/signature_pad
 *
 * Copyright 2016 Szymon Nowak
 * Released under the MIT license
 */
var SignaturePad = (function(document) {
    "use strict";

    var log = console.log.bind(console);

    var SignaturePad = function(canvas, options) {
        var self = this,
            opts = options || {};

        this.velocityFilterWeight = opts.velocityFilterWeight || 0.7;
        this.minWidth = opts.minWidth || 0.5;
        this.maxWidth = opts.maxWidth || 2.5;
        this.dotSize = opts.dotSize || function() {
            return (self.minWidth + self.maxWidth) / 2;
        };
        this.penColor = opts.penColor || "black";
        this.backgroundColor = opts.backgroundColor || "rgba(0,0,0,0)";
        this.throttle = opts.throttle || 0;
        this.throttleOptions = {
            leading: true,
            trailing: true
        };
        this.minPointDistance = opts.minPointDistance || 0;
        this.onEnd = opts.onEnd;
        this.onBegin = opts.onBegin;

        this._canvas = canvas;
        this._ctx = canvas.getContext("2d");
        this._ctx.lineCap = 'round';
        this.clear();

        // we need add these inline so they are available to unbind while still having
        //  access to 'self' we could use _.bind but it's not worth adding a dependency
        this._handleMouseDown = function(event) {
            if (event.which === 1) {
                self._mouseButtonDown = true;
                self._strokeBegin(event);
            }
        };

        var _handleMouseMove = function(event) {
            event.preventDefault();
            if (self._mouseButtonDown) {
                self._strokeUpdate(event);
                if (self.arePointsDisplayed) {
                    var point = self._createPoint(event);
                    self._drawMark(point.x, point.y, 5);
                }
            }
        };

        this._handleMouseMove = _.throttle(_handleMouseMove, self.throttle, self.throttleOptions);
        //this._handleMouseMove = _handleMouseMove;

        this._handleMouseUp = function(event) {
            if (event.which === 1 && self._mouseButtonDown) {
                self._mouseButtonDown = false;
                self._strokeEnd(event);
                document.getElementById('pharmacist_signature').value = signaturePad.toDataURL();
            }
        };

        this._handleTouchStart = function(event) {
            if (event.targetTouches.length == 1) {
                var touch = event.changedTouches[0];
                self._strokeBegin(touch);
            }
        };

        var _handleTouchMove = function(event) {
            // Prevent scrolling.
            event.preventDefault();

            var touch = event.targetTouches[0];
            self._strokeUpdate(touch);
            if (self.arePointsDisplayed) {
                var point = self._createPoint(touch);
                self._drawMark(point.x, point.y, 5);
            }
        };
        this._handleTouchMove = _.throttle(_handleTouchMove, self.throttle, self.throttleOptions);
        //this._handleTouchMove = _handleTouchMove;

        this._handleTouchEnd = function(event) {
            var wasCanvasTouched = event.target === self._canvas;
            if (wasCanvasTouched) {
                event.preventDefault();
                self._strokeEnd(event);
            }
        };

        this._handleMouseEvents();
        this._handleTouchEvents();
    };

    SignaturePad.prototype.clear = function() {
        var ctx = this._ctx,
            canvas = this._canvas;

        ctx.fillStyle = this.backgroundColor;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        this._reset();
    };

    SignaturePad.prototype.showPointsToggle = function() {
        this.arePointsDisplayed = !this.arePointsDisplayed;
    };

    SignaturePad.prototype.toDataURL = function(imageType, quality) {
        var canvas = this._canvas;
        return canvas.toDataURL.apply(canvas, arguments);
    };

    SignaturePad.prototype.fromDataURL = function(dataUrl) {
        var self = this,
            image = new Image(),
            ratio = window.devicePixelRatio || 1,
            width = this._canvas.width / ratio,
            height = this._canvas.height / ratio;

        this._reset();
        image.src = dataUrl;
        image.onload = function() {
            self._ctx.drawImage(image, 0, 0, width, height);
        };
        this._isEmpty = false;
    };

    SignaturePad.prototype._strokeUpdate = function(event) {
        var point = this._createPoint(event);
        if (this._isPointToBeUsed(point)) {
            this._addPoint(point);
        }
    };

    var pointsSkippedFromBeingAdded = 0;
    SignaturePad.prototype._isPointToBeUsed = function(point) {
        // Simplifying, De-noise
        if (!this.minPointDistance)
            return true;

        var points = this.points;
        if (points && points.length) {
            var lastPoint = points[points.length - 1];
            if (point.distanceTo(lastPoint) < this.minPointDistance) {
                // log(++pointsSkippedFromBeingAdded);
                return false;
            }
        }
        return true;
    };

    SignaturePad.prototype._strokeBegin = function(event) {
        this._reset();
        this._strokeUpdate(event);
        if (typeof this.onBegin === 'function') {
            this.onBegin(event);
        }
    };

    SignaturePad.prototype._strokeDraw = function(point) {
        var ctx = this._ctx,
            dotSize = typeof(this.dotSize) === 'function' ? this.dotSize() : this.dotSize;

        ctx.beginPath();
        this._drawPoint(point.x, point.y, dotSize);
        ctx.closePath();
        ctx.fill();
    };

    SignaturePad.prototype._strokeEnd = function(event) {
        var canDrawCurve = this.points.length > 2,
            point = this.points[0];

        if (!canDrawCurve && point) {
            this._strokeDraw(point);
        }
        if (typeof this.onEnd === 'function') {
            this.onEnd(event);
        }
    };

    SignaturePad.prototype._handleMouseEvents = function() {
        this._mouseButtonDown = false;

        this._canvas.addEventListener("mousedown", this._handleMouseDown);
        this._canvas.addEventListener("mousemove", this._handleMouseMove);
        document.addEventListener("mouseup", this._handleMouseUp);
    };

    SignaturePad.prototype._handleTouchEvents = function() {
        // Pass touch events to canvas element on mobile IE11 and Edge.
        this._canvas.style.msTouchAction = 'none';
        this._canvas.style.touchAction = 'none';

        this._canvas.addEventListener("touchstart", this._handleTouchStart);
        this._canvas.addEventListener("touchmove", this._handleTouchMove);
        this._canvas.addEventListener("touchend", this._handleTouchEnd);
    };

    SignaturePad.prototype.on = function() {
        this._handleMouseEvents();
        this._handleTouchEvents();
    };

    SignaturePad.prototype.off = function() {
        this._canvas.removeEventListener("mousedown", this._handleMouseDown);
        this._canvas.removeEventListener("mousemove", this._handleMouseMove);
        document.removeEventListener("mouseup", this._handleMouseUp);

        this._canvas.removeEventListener("touchstart", this._handleTouchStart);
        this._canvas.removeEventListener("touchmove", this._handleTouchMove);
        this._canvas.removeEventListener("touchend", this._handleTouchEnd);
    };

    SignaturePad.prototype.isEmpty = function() {
        return this._isEmpty;
    };

    SignaturePad.prototype._reset = function() {
        this.points = [];
        this._lastVelocity = 0;
        this._lastWidth = (this.minWidth + this.maxWidth) / 2;
        this._isEmpty = true;
        this._ctx.fillStyle = this.penColor;
    };

    SignaturePad.prototype._createPoint = function(event) {
        var rect = this._canvas.getBoundingClientRect();
        return new Point(
            event.clientX - rect.left,
            event.clientY - rect.top
        );
    };

    SignaturePad.prototype._addPoint = function(point) {
        var points = this.points,
            c2, c3,
            curve, tmp;

        points.push(point);

        if (points.length > 2) {
            // To reduce the initial lag make it work with 3 points
            // by copying the first point to the beginning.
            if (points.length === 3) points.unshift(points[0]);

            tmp = this._calculateCurveControlPoints(points[0], points[1], points[2]);
            c2 = tmp.c2;
            tmp = this._calculateCurveControlPoints(points[1], points[2], points[3]);
            c3 = tmp.c1;
            curve = new Bezier(points[1], c2, c3, points[2]);
            this._addCurve(curve);

            // Remove the first element from the list,
            // so that we always have no more than 4 points in points array.
            points.shift();
        }
    };

    SignaturePad.prototype._calculateCurveControlPoints = function(s1, s2, s3) {
        var dx1 = s1.x - s2.x,
            dy1 = s1.y - s2.y,
            dx2 = s2.x - s3.x,
            dy2 = s2.y - s3.y,

            m1 = {
                x: (s1.x + s2.x) / 2.0,
                y: (s1.y + s2.y) / 2.0
            },
            m2 = {
                x: (s2.x + s3.x) / 2.0,
                y: (s2.y + s3.y) / 2.0
            },

            l1 = Math.sqrt(1.0 * dx1 * dx1 + dy1 * dy1),
            l2 = Math.sqrt(1.0 * dx2 * dx2 + dy2 * dy2),

            dxm = (m1.x - m2.x),
            dym = (m1.y - m2.y),

            k = l2 / (l1 + l2),
            cm = {
                x: m2.x + dxm * k,
                y: m2.y + dym * k
            },

            tx = s2.x - cm.x,
            ty = s2.y - cm.y;

        return {
            c1: new Point(m1.x + tx, m1.y + ty),
            c2: new Point(m2.x + tx, m2.y + ty)
        };
    };

    SignaturePad.prototype._addCurve = function(curve) {
        var startPoint = curve.startPoint,
            endPoint = curve.endPoint,
            velocity, newWidth;

        velocity = endPoint.velocityFrom(startPoint);
        velocity = this.velocityFilterWeight * velocity +
            (1 - this.velocityFilterWeight) * this._lastVelocity;

        newWidth = this._strokeWidth(velocity);
        this._drawCurve(curve, this._lastWidth, newWidth);

        this._lastVelocity = velocity;
        this._lastWidth = newWidth;
    };

    SignaturePad.prototype._drawPoint = function(x, y, size) {
        var ctx = this._ctx;

        ctx.moveTo(x, y);
        ctx.arc(x, y, size, 0, 2 * Math.PI, false);
        this._isEmpty = false;
    };

    SignaturePad.prototype._drawMark = function(x, y, size) {
        var ctx = this._ctx;

        ctx.save();
        ctx.moveTo(x, y);
        ctx.arc(x, y, size, 0, 2 * Math.PI, false);
        ctx.fillStyle = 'rgba(255, 0, 0, 0.2)';
        ctx.fill();
        ctx.restore();
    };

    SignaturePad.prototype._drawCurve = function(curve, startWidth, endWidth) {
        var ctx = this._ctx,
            widthDelta = endWidth - startWidth,
            drawSteps, width, i, t, tt, ttt, u, uu, uuu, x, y;

        drawSteps = Math.floor(curve.length());
        ctx.beginPath();
        for (i = 0; i < drawSteps; i++) {
            // Calculate the Bezier (x, y) coordinate for this step.
            t = i / drawSteps;
            tt = t * t;
            ttt = tt * t;
            u = 1 - t;
            uu = u * u;
            uuu = uu * u;

            x = uuu * curve.startPoint.x;
            x += 3 * uu * t * curve.control1.x;
            x += 3 * u * tt * curve.control2.x;
            x += ttt * curve.endPoint.x;

            y = uuu * curve.startPoint.y;
            y += 3 * uu * t * curve.control1.y;
            y += 3 * u * tt * curve.control2.y;
            y += ttt * curve.endPoint.y;

            width = startWidth + ttt * widthDelta;
            this._drawPoint(x, y, width);
        }
        ctx.closePath();
        ctx.fill();
    };

    SignaturePad.prototype._strokeWidth = function(velocity) {
        return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
    };

    var Point = function(x, y, time) {
        this.x = x;
        this.y = y;
        this.time = time || new Date().getTime();
    };

    Point.prototype.velocityFrom = function(start) {
        return (this.time !== start.time) ? this.distanceTo(start) / (this.time - start.time) : 1;
    };

    Point.prototype.distanceTo = function(start) {
        return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
    };

    var Bezier = function(startPoint, control1, control2, endPoint) {
        this.startPoint = startPoint;
        this.control1 = control1;
        this.control2 = control2;
        this.endPoint = endPoint;
    };

    // Returns approximated length.
    Bezier.prototype.length = function() {
        var steps = 10,
            length = 0,
            i, t, cx, cy, px, py, xdiff, ydiff;

        for (i = 0; i <= steps; i++) {
            t = i / steps;
            cx = this._point(t, this.startPoint.x, this.control1.x, this.control2.x, this.endPoint.x);
            cy = this._point(t, this.startPoint.y, this.control1.y, this.control2.y, this.endPoint.y);
            if (i > 0) {
                xdiff = cx - px;
                ydiff = cy - py;
                length += Math.sqrt(xdiff * xdiff + ydiff * ydiff);
            }
            px = cx;
            py = cy;
        }
        return length;
    };

    Bezier.prototype._point = function(t, start, c1, c2, end) {
        return start * (1.0 - t) * (1.0 - t) * (1.0 - t) +
            3.0 * c1 * (1.0 - t) * (1.0 - t) * t +
            3.0 * c2 * (1.0 - t) * t * t +
            end * t * t * t;
    };

    return SignaturePad;
})(document);

var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)',
    velocityFilterWeight: .7,
    minWidth: 0.5,
    maxWidth: 2.5,
    throttle: 16, // max x milli seconds on event update, OBS! this introduces lag for event update
    minPointDistance: 3,
});
// var saveButton = document.getElementById('save'),
var clearButton = document.getElementById('clear');
// var   showPointsToggle = document.getElementById('showPointsToggle');

// saveButton.addEventListener('click', function(event) {
//     var data = signaturePad.toDataURL('image/png');
//     window.open(data);
// });
clearButton.addEventListener('click', function(event) {
    signaturePad.clear();
    document.getElementById('pharmacist_signature').value = "";
});
// showPointsToggle.addEventListener('click', function(event) { 
//     signaturePad.showPointsToggle();
//     showPointsToggle.classList.toggle('toggle');
// });
</script>

@endsection