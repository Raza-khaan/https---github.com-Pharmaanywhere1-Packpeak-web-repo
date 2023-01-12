@extends('admin.layouts.mainlayout')
@section('title') <title>Search</title>
<style type="text/css">
  .select2-container--default .select2-selection--single {
    height: 40px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 40px;
    display: none;
}
#patient_name, #multiple-patient_name {
  /* for Firefox */
  -moz-appearance: none;
  /* for Chrome */
  -webkit-appearance: none;
}
</style>
@endsection





@section('content')



 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Search Preview</h2>
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

        <!-- Main content -->
        <div class="report-forms">
          <div class="row" style="width:100%;margin-bottom:15px">
            @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
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

            <div class="col-md-12">
              <!-- general form elements -->
              <div class="patient-information">
              
                <form role="form" action="{{url('admin/search_patient')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                     
                <div class="row">
                <div class="col-md-3">
              <div class="form-group">
            
              <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
              @if(count($all_pharmacies)  && isset($all_pharmacies))
              <select onchange="loadpatients(this)"  class="form-control" name="company_name" required id="company_name">
              <option value="">--Select Company --</option>
              @foreach($all_pharmacies as $row)
              <option value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}</option>
              @endforeach
              </select>
              @endif
              <input   style="display:none"  id="txtcompany" class="typeahead form-control" type="text">
              <span class="alert_company"></span>
              </div>

              </div>
             


                              <div class="col-md-3">
                         <div class="form-group">
                                <label for="patient_name">Patient Name <span class="text-danger"> *</span></label>
                                <select required name="patient_name" id="patient_name" class="form-control">
                                  <option value="">-- Select Patient--</option>
                                </select>
                         </div>
</div>


<div class="col-md-3">
                            <div class="form-group">
                            
                            <label for="dob">Date Of Birth <span class="text-danger"> *</span></label>
                                <input readonly  type="text" class="form-control"  required name="dob" id="dob" placeholder="Date Of Birth" >
                               
                          </div>
                                
                              </div>
<div class="col-md-3">
<label  style ="visibility:hidden" for="patient_name">Patient Name <span class="text-danger"> *</span></label> <br/>
<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                            <button type="reset" id="reset" class="btn btn-dark">Reset</button>
</div>
                         
                      
                       
                 
                        </div>
                </form>
                
              </div><!-- /.box -->

</div>
          </div>   <!-- /.row -->
</div><!-- /.content -->



      </div><!-- /.content-wrapper -->



 

@endsection


@section('customjs')

<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


    <script type="text/javascript">

function loadpatients(data)
{
 

    $.ajax({
            type: "POST",
            url: "{{url('admin/get_patients_by_website_id')}}",
            data: {
                'website_id': data.value,
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
            // $('#country').val('United States').trigger('change');

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

  

}

});


      $(function () {
        //Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){}); 
        $('#patient_name').select2();
       
        // $('#dob').datepicker({
        //     format: "dd/mm/yyyy",
        //     endDate: new Date(), 
        //     autoclose: true
        //   });

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

        $("input[type=radio][name='who_pickup']").on('ifToggled', function(event){
        
            var checked = $(this).closest('div.iradio_flat-green').hasClass("checked");
            if($(this).val()=='carer')
            {   
              $('.div_carer_name').html('<label for="carer_name">Carer`s Name <span class="text-danger"> *</span></label> <input type="text" class="form-control" maxlength="20"  required id="carer_name" name="carer_name" placeholder="Carer Name..">');
            }
            else
            {
              $('.div_carer_name').html("");
            }
        });
         


      });
     

   
   


      //     restrict Alphabets  
      function restrictAlphabets(e){
          var x=e.which||e.keycode; 
          if((x>=48 && x<=57))
            return true;
          else
            return false;
      }

      
        

      $('#patient_name').click(function(){
           if($('#company_name').val()==false){
                $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>'); 
                $('select[id="company_name"]').css('border','1px solid red');
           } 
      });
      $('#dob').click(function(){
           if($('#company_name').val()==false){
                $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>'); 
                $('select[id="company_name"]').css('border','1px solid red');
           } 
        });

       /* get All Patient  List  By  Website id */
       $('#company_name').click(function(){
           if($(this).val()){
              
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/get_patients_by_website_id')}}",
                  data: {'website_id':$(this).val(),"_token":"{{ csrf_token() }}"},
                  beforeSend: function() {
                    $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
                  },
                  success: function(result){
                    // console.log(result);
                    $('.loader_company').html('');
                    $('.alert_company').html(''); 
                    $('select[id="company_name"]').css('border','none');
                    $('#patient_name').html(result);
                    $('#patient_name').select2(
                      ).on('change', function (e) {
                      if(this.value){
                            var ob=$(this).children('option:selected');
                            var dob=ob.attr('data-dob');
                            
                            var lastLocation=ob.attr('data-lastLocation');
                            $('#dob').val(moment(dob).format('DD/MM/YYYY'));
                         }
                      });
                  }
              });
           } 
        });
   
$("#reset").click(function(){
  $('#patient_name').val('').trigger('change');
  $("form").trigger("reset");
})
    </script>








@endsection
