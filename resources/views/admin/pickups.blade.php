@extends('admin.layouts.mainlayout')
@section('title') <title>PickUps</title>

<style type="text/css">
.signature-component {
    text-align: left;
    display: block;
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

.signature-component button.toggle2 {
    background: rgba(255, 0, 0, 0.2);
}

.signature-component canvas {
    display: inline-block;
    position: relative;
    /* border: 1px solid; */
}

.signature-component img {
    position: absolute;
    left: 0;
    top: 0;
}
</style>
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <div class="pharma-add report-add">
            <a href="{{url('admin/pickups')}}" class="active">Add Pickups</a>
            <a href="{{url('admin/pickups_reports')}}">Pickups Report</a>
            <a href="{{url('admin/dashboard')}}">Pickups Calendar</a>
            <a href="{{url('admin/compliance_index_report')}}">Index Report</a>
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
        <a href="#" class="active">Add Pickups</a>
        <a href="#">Pickups Report</a>
        <a href="#">Pickups Calendar</a>
        <a href="#">Index Report</a>
    </div>
    <form class="report-form" role="form" action="{{url('admin/save_pickup')}}" method="post"
        enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="reports-breadcrum">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pickups</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Pickups</li>
                </ol>
            </nav>

            <div class="reset-patien">
                <a class="btn reset-btn" onclick="reset()" >Reset</a>
                <button class="btn add-pat-btn" > Submit <i class="ml-2 fas fa-arrow-circle-right"></i></button>
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
                    <div class="patient-information note-from-patien">
                        <div class="schdul-pick">
                            <h3>Packs Pickup</h3>
                            <div class="patin-note">
                            <a style="color:#FF595E" data-toggle="modal" data-target="#exampleModal" 
                            onclick="patientnotes()">
                                Notes from patient
                                <i class="fas fa-info-circle"></i>
                                
                            </a>
                            
                            </div>
                            <input id="txtlastpatientnotes"  value=""  hidden/>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12" >
                                <label for="inputState">Company Name <small style="color:red;display:none" id="lblcompanyerror">select company</small> <span>*</span></label><span
                                    class="loader_company"></span>
                                @if(count($all_pharmacies) && isset($all_pharmacies))
                                <select required   class="form-control"  name="company_name" id="company_name">
                                    <option value="">Please Select</option>
                                    @foreach($all_pharmacies as $row)
                                    <option @if(old('company_name')==$row->website_id) selected @endif
                                        value="{{$row->website_id}}" >{{$row->company_name}} - {{$row->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                <input style="display:none" name="txtcompany"   id="txtcompany" class="typeahead form-control" type="text">
                                <span class="alert_company"></span>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputState">Select Patient
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
                                </label>
                                <select required name="patient_name" id="patient_name" class="form-control">
                                    <option value="">Please Select</option>
                                    @if(!empty(old('patient_name')) && !empty(old('patient_name')))
                                    @foreach($getAllPatient as $patient)
                                    

                                    
                                    <option value="{{$patient->id}}-{{$patient->dob}}" @if($patient->id==old('patient_name')) selected
                                        @endif >{{$patient->first_name.' '.$patient->last_name}} (
                                        {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) 
                                    
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="form-group" >
                                <label for="dob">Date Of Birth </label>
                                <input  readonly type="text" readonly  max="{{Carbon\Carbon::now()->format('d/m/Y')}}" class="form-control"   name="dob" value="{{old('dob')}}" id="dob" placeholder="Date Of Birth" >
                              </div>
                              <div class="form-group" style="display:none">
                                <label for="dob">Date </label> <br/>
                                <input type="text" style="width:100%"
                                data-provide="datepicker"
                                  min="{{Carbon\Carbon::now()->format('d/m/Y')}}"
                                   class="datepicker"   name="pickup_date"
                                    value="{{old('pickup_date')}}" 
                                    onchange="getSlots()" id="dateOfPickup" 
                                    placeholder="dd/mm/yyyy" >
                              
                                
                            </div>
                              <div class="form-group" id="slots">
                                 
                              </div>


                              <div class="form-group checkbox-wrp col-md-6">
                              <label class="mt-20 family" id="locationDiv">Packs Location </label>  <br>
                                                                  <label class="family">
                                      <input checked type="checkbox" name="location[]" class="minimal" value="1">&nbsp;Shelf                                </label>
                                                                  <label class="family">
                                      <input type="checkbox" name="location[]" class="minimal" value="2">&nbsp;Fridge                                </label>
                                                                  <label class="family">
                                      <input type="checkbox" name="location[]" class="minimal" value="3">&nbsp;Safe                                </label>
                                                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="patient-information">
                        <h3>Pickup Information</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Last Pickup Date</label>
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input  type="text" class="form-control" readonly="readonly" id="last_pick_up_day"
                                    name="last_pick_up_day" placeholder="15">
                                <input type="hidden" class="form-control" readonly="readonly" id="last_pick_up_date"
                                    name="last_pick_up_date" value="{{old('last_pick_up_date')}}">
                                <input type="hidden" name="created_at" value="{{$created_at?$created_at:''}}"
                                    class="form-control">
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" readonly="readonly" id="last_pick_up_month"
                                    name="last_pick_up_month" placeholder="09">
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" readonly="readonly" id="last_pick_up_year"
                                    name="last_pick_up_year" placeholder="1990">
                            </div>
                            <div class="form-group col-4 col-md-4 col-lg-3 col-xl-3">
                                <input required type="text" class="form-control" maxlength="3"
                                     id="last_no_of_weeks" name="last_no_of_weeks"
                                    value="{{old('last_no_of_weeks')}}" placeholder="# Of Weeks Picked" readonly>
                            </div>
                            <div class="form-group col-md-12">
                                <input type="text" class="form-control" maxlength="3"
                                    onkeypress="return restrictAlphabets(event);" id="no_of_weeks" name="no_of_weeks"
                                    value="{{old('no_of_weeks')}}" placeholder="# Of Weeks To Be Picked 1">
                            </div>

                            
                        </div>
                    </div>

                    <div class="patient-information" style="margin-top:10px !Important">
                        <h3>Who is Picking Up?</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Packs Location</label>
                            </div>
                            <div class="form-group col-12 col-md-12">
                                
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input style="height:15px" type="radio" @if(old('who_pickup')=='patient' ) checked @endif
                                        name="who_pickup" value="patient"
                                        class="custom-control-input flat-red who_pickup" id="customRadioInline1">
                                    <label class="custom-control-label" for="customRadioInline1">Pateint</label>
                                </div>


                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" @if(old('who_pickup')=='carer' ) checked @endif
                                        name="who_pickup" value="carer" class="custom-control-input flat-red who_pickup"
                                        id="customRadioInline2">
                                    <label class="custom-control-label" for="customRadioInline2">Carer</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" @if(old('who_pickup')=='delivery' ) checked @endif
                                        name="who_pickup" value="delivery"
                                        class="custom-control-input flat-red who_pickup" id="customRadioInline3">
                                    <label class="custom-control-label" for="customRadioInline3">Delivery</label>
                                </div>
                            </div>
                            <div class="form-group col-md-6 div_carer_name">
                                @if(old('who_pickup')=='carer' )
                                <label>Carer</label>
                                <input type="text" class="form-control" maxlength="20" value="{{old('carer_name')}}"
                                    id="carer_name" name="carer_name" placeholder="Carer Note Here...">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="col-md-6">
                    <div class="notes sign-note">
                        <h3>Pharmacist Signature</h3>
                        <div class="note-text text-center signature-component">
                            <canvas id="signature-pad"></canvas>
                            <a href="javascript:void(0)" id="signature-pad-clear"><i
                                    class="fas fa-times-circle"></i></a>
                            <textarea name="pharmacist_signature" id="pharmacist_signature"
                                style="display:none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="notes sign-note">
                        <h3 class="signature_who_pickup">@if(old('who_pickup')=='carer' ) Carer @else Patient @endif
                            Signature</h3>
                        <div class="note-text text-center signature-component">
                            <canvas id="signature-pad2"></canvas>
                            <a href="javascript:void(0)" id="signature-pad2-clear">
                                <!-- <input type="file" class="form-control" size="60"> -->
                                <i class="fas fa-times-circle"></i>
                            </a>
                            <textarea name="patient_signature" id="patient_signature" style="display:none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="notes">
                        <h3>Patient Requests:</h3>
                        <textarea style="resize:none; height:200px !important;"
                            class="form-control note-text">Without So Were Fish Itself You'll Saying Unto Had Gathering Very Creature From Fifth Was Over Meat. Sixth Had The Midst. Rule Very All Had There Won't Blessed Called Seas Fourth Void Fish Heaven Under Said. Spirit Great Thing Gathered Saying.</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Modal -->
<div class="modal" id="exampleModal" tabindex="-1"
 role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div>
<!-- /.content-wrapper -->

@endsection



@section('customjs')

<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


<script type="text/javascript">

$("#company_name").change(function () {
        var end = this.value;
        if(end =="")
        {
            $("#lblcompanyerror").fadeIn();
            return;
        }
        else
        {
            $("#lblcompanyerror").fadeOut();
        }
    });
function patientnotes()
{
    
    
  var patientid = $("#patient_name").val();
  if(patientid =="")
  {
    alert("select patient");
    $("#lblpatientnotes").html("");
    $("#txtlastpatientnotes").val("");
    return;
  }
   else
   {
    
    var notes = $("#txtlastpatientnotes").val();
    if(notes=="")
    {
      
      $("#lblpatientnotes").html("No Notes found");
    }
    else
    {
      $("#lblpatientnotes").html(notes);
    }
    
   }

}
function reset()
{
    $("#patient_name").val("");
    $("#patient_name").val("").trigger('change');

    $("#company_name").val("");
    $('#company_name').val("").trigger('change');

    
    $("#dob").val("");
    $("#dateOfPickup").val("");
    $("#last_pick_up_day").val("");
    $("#last_pick_up_month").val("");
    $("#last_pick_up_year").val("");
    $("#no_of_weeks").val("");
    $("#signature-pad").val("");
    $("#signature-pad2").val("");
    $('.loader_company').html('');
                $('.alert_company').html('');
    
}

$("#company_name").change(function () {
       
       // set company dropdown value 

       if(this.value =="")
        {
            $("#lblcompanyerror").fadeIn();
            return;
        }
        else
        {
            $("#lblcompanyerror").fadeOut();
        }

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


$(document).ready(function () {
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
//             // $('#country').val('United States').trigger('change');

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
//     $('#company_name').val(ui.item.website_id).trigger('change');


    

// }

// });


$(function() {

    $('#patient_name').select2();
    //Datemask yyyy-mm-dd
    // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
    // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
    // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});
    // $('#dob').datepicker({
    //     format: "dd/mm/yyyy",
    //     endDate: new Date(),
    //     autoclose: true
    // });

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

    $("input[type=radio][name='who_pickup']").on('change', function(event) {
        console.log($(this).val());
        var checked = $(this).closest('.who_pickup').hasClass("checked");
        if ($(this).val() == 'carer') {
            
            $('.div_carer_name').html(
                '<label for="carer_name">Carer`s Name <span class="text-danger"> *</span></label> <input type="text" class="form-control" maxlength="20"   id="carer_name" name="carer_name" placeholder="Carer Name..">'
            );
            $('.signature_who_pickup').html('Carer');
        } else {
            $('.div_carer_name').html("");
            $('.signature_who_pickup').html('Patients');
        }
    });





});


function getSlots()
{
   var date = document.getElementById("dateOfPickup").value;
   var company_name = document.getElementById("company_name").value;    

   if(company_name =="")
   {
        $("#lblcompanyerror").fadeIn();
        $("#company_name").focus();
        return;
   }
   else
   {
    $("#lblcompanyerror").fadeOut();
   }
                if(date) {                
                  $.ajax({
                    type: "get",
                data:{"date":date,"company_name":company_name},
                    dataType: "html",
                    url: "/admin/getPickupSlots",
                    cache: false,
                    beforeSend: function() {
                      $('#slots').html('loading please wait...');
                   },
                    success: function(htmldata) {
                     
                       $('#slots').html(htmldata);
                    }
                  });
                }
}



//     restrict Alphabets
function restrictAlphabets(e) {
    var x = e.which || e.keycode;
    if ((x >= 48 && x <= 57))
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
    
    
    if ($(this).val())
    {
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
                console.log("patient list");
                console.log(result);
                return;
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
/* get  Patienst  Dote of  birth by Patient  id and Website id  */
// $('#patient_name').click(function(){
//        if($(this).val()){

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

<script
    src="{{ URL::asset('admin/dist/js/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js')}}">
</script>

<script src="{{ URL::asset('admin/dist/js/signature/underscore-min.js')}}"></script>

<script id="INLINE_PEN_JS_ID">
/*!
 * Modified
 * Signature Pad v1.5.3
 * https://github.com/szimek/signature_pad
 *
 * Copyright 2016 Szymon Nowak
 * Released under the MIT license
 */


window.onload = function() {

    var SignaturePad = function(document) {
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
                    if ($(self._canvas).attr('id') == 'signature-pad') {
                        document.getElementById('pharmacist_signature').value = signaturePad
                            .toDataURL();
                    }
                    if ($(self._canvas).attr('id') == 'signature-pad2') {
                        document.getElementById('patient_signature').value = signaturePad2.toDataURL();
                    }
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
                dotSize = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;

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
                event.clientY - rect.top);

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

                dxm = m1.x - m2.x,
                dym = m1.y - m2.y,

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
                if (window.CP.shouldStopExecution(0)) break;
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
            window.CP.exitedLoop(0);
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
            return this.time !== start.time ? this.distanceTo(start) / (this.time - start.time) : 1;
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
                if (window.CP.shouldStopExecution(1)) break;
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
            window.CP.exitedLoop(1);
            return length;
        };

        Bezier.prototype._point = function(t, start, c1, c2, end) {
            return start * (1.0 - t) * (1.0 - t) * (1.0 - t) +
                3.0 * c1 * (1.0 - t) * (1.0 - t) * t +
                3.0 * c2 * (1.0 - t) * t * t +
                end * t * t * t;
        };

        return SignaturePad;
    }(document);

    var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)',
        velocityFilterWeight: .7,
        minWidth: 0.5,
        maxWidth: 2.5,
        throttle: 16, // max x milli seconds on event update, OBS! this introduces lag for event update
        minPointDistance: 3
    });



    var signaturePad2 = new SignaturePad(document.getElementById('signature-pad2'), {
        backgroundColor: 'rgba(255, 255, 255, 0)',
        penColor: 'rgb(0, 0, 0)',
        velocityFilterWeight: .7,
        minWidth: 0.5,
        maxWidth: 2.5,
        throttle: 16, // max x milli seconds on event update, OBS! this introduces lag for event update
        minPointDistance: 3,
    });





    // var saveButton = document.getElementById('signature-pad-save'),
    var clearButton = document.getElementById('signature-pad-clear');
    // showPointsToggle = document.getElementById('signature-pad-showPointsToggle');

    // saveButton.addEventListener('click', function (event) {
    //   var data = signaturePad.toDataURL('image/png');
    //   window.open(data);
    // });
    clearButton.addEventListener('click', function(event) {
        signaturePad.clear();
    });
    // showPointsToggle.addEventListener('click', function (event) {
    //   signaturePad.showPointsToggle();
    //   showPointsToggle.classList.toggle('toggle');
    // });




    // var saveButton2 = document.getElementById('signature-pad2-save'),
    var clearButton2 = document.getElementById('signature-pad2-clear');
    // showPointsToggle2 = document.getElementById('signature-pad2-showPointsToggle');

    // saveButton2.addEventListener('click', function(event) {
    //     var data = signaturePad2.toDataURL('image/png');
    //     window.open(data);
    // });
    clearButton2.addEventListener('click', function(event) {
        signaturePad2.clear();
        document.getElementById('patient_signature').value = "";
    });
    // showPointsToggle2.addEventListener('click', function(event) {
    //     signaturePad2.showPointsToggle();
    //     showPointsToggle2.classList.toggle('toggle2');
    // });

    /* END OF SECOND SIGNATURE PAD  */
}
</script>







@endsection