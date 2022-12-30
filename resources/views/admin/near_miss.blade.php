@extends('admin.layouts.mainlayout')
@section('title') <title>Near Miss</title>
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

.check-label {
    font-size: 13px;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Add New Near Miss</h2>
        <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>


        <div class="pharma-add report-add">
            <a href="{{url('admin/all_near_miss')}}"class="active family">Add Near Miss</a>
    </div>
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
        <h2>Add Near Miss</h2>
    </div>
    <form role="form" action="{{url('admin/save_near_miss')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <nav class="dash-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Near Miss</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Near Miss</li>
            </ol>
        </nav>
        <!-- Main content -->
        <div class="row">
            <div class="col-md-6 m-auto">

                <!-- general form elements -->

                <div class="update-information">
                    <div class="notes">
                        <h3 class="text-center">Add Near Miss</h3>
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="company_name">Company Name <span class="text-danger">
                                            *</span></label> <span class="loader_company"></span>
                                    @if(count($all_pharmacies) && isset($all_pharmacies))
                                    <select class="form-control" name="company_name" id="company_name">
                                        <option value="">Select Company</option>
                                        @foreach($all_pharmacies as $row)
                                        <option @if(old('company_name')==$row->website_id) selected @endif
                                            value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @endif
                                    <input  style="display:none" required id="txtcompany" class="typeahead form-control" type="text">
                                    <span class="alert_company"></span>
                                </div>
                                <label> Error Type </label>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 check-label">
                                            <input type="checkbox" {{old('missed_tablet')?'checked':''}}
                                                name="missed_tablet" class="minimal"
                                                value="missed_tablet" />&nbsp;Missed
                                            tablet
                                        </div>
                                        <div class="col-md-4 check-label">
                                            <input type="checkbox" {{old('extra_tablet')?'checked':''}}
                                                name="extra_tablet" class="minimal" value="extra_tablet" />&nbsp;Extra
                                            tablet
                                        </div>
                                        <div class="col-md-4 check-label">
                                            <input type="checkbox" {{old('wrong_tablet')?'checked':''}}
                                                name="wrong_tablet" class="minimal" value="wrong_tablet" />&nbsp;Wrong
                                            tablet
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="row">
                                        <div class="col-md-4 check-label">
                                            <input type="checkbox" {{old('wrong_day')?'checked':''}} name="wrong_day"
                                                class="minimal" value="wrong_day" />&nbsp;Wrong day
                                        </div>
                                        <div class="col-md-4 check-label">
                                            <input type="checkbox" {{old('other_checkbox')?'checked':''}}
                                                id="other_checkbox" name="other_checkbox" class="minimal" />&nbsp;other
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group other_field">
                                    @if(old('other_checkbox'))
                                    <label for="other">Other? <span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" minlength="3" value="{{old('other')}}"
                                        name="other" id="other" placeholder="other">
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="person_involved">Person involved <span class="text-danger">
                                            *</span></label><span class="loader_patient"></span>
                                    <input type="text" value="{{old('person_involved')}}" name="person_involved"
                                        id="person_involved" class="form-control" placeholder="Person Involved"
                                        style="text-transform: capitalize;">
                                </div>


                                <!-- textarea -->
                                <div class="form-group">
                                    <label for="Initials">Initials <span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" style="resize: none;" rows="4"
                                        name="initials" id="initials"
                                        onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"
                                        placeholder="Initials." value="{{old('initials')}}" />
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn update-btn">Add</button>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="reset" class="btn reset-btn">Reset</button>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>



@endsection


@section('customjs')



<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />

<script type="text/javascript">

$(document).ready(function(){
  
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
    //Datemask yyyy-mm-dd
    $("#dob").inputmask("yyyy-mm-dd", {
        "placeholder": "yyyy-mm-dd"
    });
    var pickerOptsGeneral = {
        format: "yyyy-mm-dd",
        autoclose: true,
        minView: 2,
        maxView: 2,
        todayHighlight: true
    }; //  ,startDate:  new Date()
    $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate', function(ev) {});


    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });

    $('#other_checkbox').on('ifChanged', function(event) {
        //alert($(this).val());
        $(this).on('ifChecked', function(event) {
            // alert("checked");
            $('.other_field').html(
                '<label for="other">Other? <span class="text-danger"> *</span></label>\
                                <input type="text" class="form-control" minlength="3"  name="other" id="other"\ placeholder="other" >'
            );
        });
        $(this).on('ifUnchecked', function(event) {
            // alert("Unchecked");
            $('.other_field').html('');
        });

    });


    $("#initials").on("keyup", function() {
        $(this).val(($(this).val()).toUpperCase());
    });




});



$('#person_involved').click(function() {
    if ($('#company_name').val() == false) {
        $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>');
        $('select[id="company_name"]').css('border', '1px solid red');
    }
});

$('#company_name').click(function() {
    if ($(this).val()) {
        $('.alert_company').html('');
        $('select[id="company_name"]').css('border', 'none');
    }
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
</script>
@endsection