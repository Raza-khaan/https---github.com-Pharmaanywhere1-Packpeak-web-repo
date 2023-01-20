@extends('admin.layouts.mainlayout')
@section('title') <title>Audits</title>

@endsection
@section('content')

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


<!-- Content Wrapper. Contains page content -->
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Add Audits</h2>
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

    <div class="pharma-register">
        <h2>Add Audit</h2>
    </div>
    <form role="form" action="{{url('admin/save_audit')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="reports-breadcrum m-0">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Forms</li>
                    <li class="breadcrumb-item active" aria-current="page">General Elements</li>
                </ol>
            </nav>

        </div>
        <!-- Main content -->
        <div class="row">
            <div class="col-md-6 m-auto">
                <!-- general form elements -->
                <div class="update-information">
                    <div class="notes">
                        <h3 class="text-center">Add Audit</h3>

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
                            <div class="col-md-offset-5 col-md-12">
                                <div class="form-group">
                                    <label for="company_name">Company Name <span class="text-danger">
                                            *</span></label> <span class="loader_company"></span>
                                    @if(count($all_pharmacies) && isset($all_pharmacies))
                                    <select  required  class="form-control" name="company_name" id="company_name">
                                        <option value="">--Select Company --</option>
                                        @foreach($all_pharmacies as $row)
                                        <option @if(old('company_name')==$row->website_id) selected @endif
                                            value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif

                                    <input  style="display:none"  id="txtcompany" class="typeahead form-control" type="text">
                                    <span class="alert_company"></span>
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_name">Patient Name <span class="text-danger">
                                            *</span></label>
                                     <!-- <select name="patient_name" id="patient_name" class="form-control">  -->
                                 <select onchange="getchagecount()" required style="height:30px" name="patient_name[]" id="patient_name" class="form-control js-example-basic-multiple"  multiple="multiple"> 
                                        <option value="">-- Select Patient--</option>
                                        @if(!empty(old('patient_name')) && !empty(old('patient_name')))
                                        @foreach($getAllPatient as $patient)
                                        <option value="{{$patient->id}}" @if($patient->id==old('patient_name'))
                                            selected @endif

                                            data-dob="{{$patient->dob}}"
                                            data-lastPickupDate="{{isset($patient->latestPickups->created_at)?$patient->latestPickups->created_at:''}}"
                                            data-lastPickupWeek="{{isset($patient->latestPickups->created_at)?$patient->latestPickups->no_of_weeks:''}}"
                                            data-lastNoteForPatient="{{isset($patient->latestPickups->notes_from_patient)?$patient->latestPickups->notes_from_patient:''}}"
                                            data-lastLocation="{{isset($patient->latestPickups->location)?$patient->latestPickups->location:''}}"
                                            data-last_returnStore="{{isset($patient->latestReturn->store)?$patient->latestReturn->store:''}}"
                                            data-last_returnStoreOther="{{isset($patient->latestReturn->other_store)?$patient->latestReturn->other_store:''}}"

                                            data-last_AuditStore="{{isset($patient->latestAudit->store)?$patient->latestAudit->store:''}}"
                                            data-last_AuditStoreOther="{{isset($patient->latestAudit->store_others_desc)?$patient->latestAudit->store_others_desc:''}}"

                                            >{{$patient->first_name.' '.$patient->last_name}} (
                                            {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="store">Store </label>
                                    <select name="store" id="store" class="form-control">
                                        @if(isset($all_facilities))
                                        @foreach($all_facilities as $row)
                                        <option @if(old('store')==$row->id) selected @endif
                                            value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach @endif
                                    </select>
                                </div>
                                <div class="form-group otherinput">

                                    @if(old('other_store'))
                                    <input type="text" name="other_store" value="{{old('other_store')}}" required
                                        id="other_store" class="form-control" placeholder="other store">
                                    @endif

                                </div>



                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_of_weeks">Number of weeks <span class="text-danger">
                                            *</span></label>
                                    <input type="text" class="form-control" maxlength="3"
                                        onkeypress="return restrictAlphabets(event);" id="no_of_weeks"
                                        value="{{old('no_of_weeks')}}" name="no_of_weeks" placeholder="no of weeks">
                                </div>



                                <div class="form-group">
                                    <label for="staff_initials">Staff initials </label>
                                    <input type="text" name="staff_initials" value="{{old('staff_initials')}}"
                                        id="staff_initials" class="form-control"
                                        onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"
                                        placeholder="Staff initials">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-offset-5 col-md-6">
                                        <button type="submit" class="btn update-btn">Submit</button>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <a  onclick="reset()" class="btn reset-btn">Reset</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </form>
</div> <!-- /.row -->





</div><!-- /.content-wrapper -->

@endsection


@section('customjs')


<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


<script type="text/javascript">

function reset()
{
    
    location.reload();
}

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
    $("#staff_initials").on("keyup", function() {
        $(this).val(($(this).val()).toUpperCase());
    });
    //Datemask yyyy-mm-dd
    // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
    // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});
    $('#patient_name').select2();

    // //iCheck for checkbox and radio inputs
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





});

$(document).ready(function() {

    $("#company_name").select2();
});

$('#patient_name').click(function() {
    if ($('#company_name').val() == false) {
        $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>');
        $('select[id="company_name"]').css('border', '1px solid red');
    }
});

/* get All Patient  List  By  Website id */
//  $('#company_name').click(function(){
//      if($(this).val()){

//         $.ajax({
//             type: "POST",
//             url: "{{url('admin/get_patients_by_website_id')}}",
//             data: {'website_id':$(this).val(),"_token":"{{ csrf_token() }}"},
//             beforeSend: function() {
//               $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
//             },
//             success: function(result){
//               // console.log(result);
//               $('.loader_company').html('');
//               $('.alert_company').html('');
//               $('select[id="company_name"]').css('border','none');
//               $('#patient_name').html(result);
//               $('#patient_name').select2();
//             }
//         });
//      }
//   });

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
                $('#patient_name').html(result.patient);
                $('#store').html(result.store);
                $('#patient_name').select2().on('change', function(e) {
                    if (this.value) {
                        var ob = $(this).children('option:selected');
                        var last_last_AuditStore = ob.attr('data-last_AuditStore');
                        var last_AuditStoreOther = ob.attr('data-last_AuditStoreOther');
                        if (last_last_AuditStore != "") {
                            $('#store').val(last_last_AuditStore);
                        } else {
                            $('#store').val(1);
                        }
                        if (last_AuditStoreOther != "") {
                            $('.otherinput').html(
                                '<input type="text" required name="other_store" id="other_store" value="' +
                                last_AuditStoreOther +
                                '" class="form-control"  placeholder="other store">'
                            );
                        } else {
                            $('.otherinput').html('');
                        }

                    }
                });
            }
        });
    }
});

$('#store').change(function() {
    if ($(this).val() == '5') {
        $('.otherinput').html(
            '<input type="text" name="other_store" required id="other_store" class="form-control"  placeholder="other store">'
        );
    } else {
        $('.otherinput').html('');
    }
});

//     restrict Alphabets
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57))
        return true;
    else
        return false;
}
</script>
@endsection