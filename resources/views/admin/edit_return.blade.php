@extends('admin.layouts.mainlayout')
@section('title') <title>Edit Returns</title>

@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
 <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <div class="pharma-add report-add">
            <a href="#" class="active">Update Returns</a>
            <a href="#">All Returns</a>
    
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
        <a href="#">Add Returns</a>
        <a href="#">All Return</a>
    </div>

     
          <div >
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
         
                <form id="form" role="form" action="{{url('admin/update_return/'.$patient_return[0]->website_id.'/'.$patient_return[0]->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                  

                <nav class="dash-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Returns</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Returns</li>
            </ol>
        </nav>
                  <div class="col-md-7 m-auto">
                  <div class="row">
                  <div class="update-information">
                  <div class="notes">
                  <h3 class="text-center">Update Returns</h3>
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                              <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
                                  @if(count($all_pharmacies)  && isset($all_pharmacies))
                                    @foreach($all_pharmacies as $row)
                                      @if($row->website_id==$patient_return[0]->website_id)
                                      <input type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
                                      @endif
                                    @endforeach
                                  @endif
                              <span class="alert_company"></span>
                            </div>
                  </div>
                            <div class="col-md-6">
                            <div class="form-group">
                              <label for="patient_name">Patient Name <span class="text-danger"> *</span></label> <span class="loader_patient"></span>
                              <select name="patient_name" id="patient_name" class="form-control">
                                 <option value="">-- Select Patient--</option>
                                 @foreach($all_patients as $row)
                                   <option value="{{$row->id}}" @if($row->id==$patient_return[0]->patient_id) selected @endif

                                    data-dob="{{$row->dob}}" data-lastPickupDate="{{isset($row->latestPickups->created_at)?$row->latestPickups->created_at:''}}"  data-lastPickupWeek="{{isset($row->latestPickups->created_at)?$row->latestPickups->no_of_weeks:''}}"
                                     data-lastNoteForPatient="{{isset($row->latestPickups->notes_from_patient)?$row->latestPickups->notes_from_patient:''}}"
                                     data-lastLocation="{{isset($row->latestPickups->location)?$row->latestPickups->location:''}}"
                                     data-last_returnStore="{{isset($row->latestReturn->store)?$row->latestReturn->store:''}}"
                                    data-last_returnStoreOther="{{isset($row->latestReturn->other_store)?$row->latestReturn->other_store:''}}"

                                    data-last_AuditStore="{{isset($row->latestAudit->store)?$row->latestAudit->store:''}}"
                                    data-last_AuditStoreOther="{{isset($row->latestAudit->store_others_desc)?$row->latestAudit->store_others_desc:''}}"
                                    >{{$row->first_name.' '.$row->last_name}} ( {{$row->dob?date("j/n",strtotime($row->dob)):""}} ) </option>
                                 @endforeach
                              </select>
                            </div>
                            </div>

                            <div class="col-md-6">
                            <div class="form-group">
                              <label for="select_days_weeks">Select Days or Weeks <span class="text-danger"> *</span></label>
                              
                              <select name="select_days_weeks" id="select_days_weeks" class="form-control">

                              @if($patient_return[0]->day_weeks=="days")
                              <option value="days"   selected >Day</option>
                                 <option value="weeks"  >Week</option>
                              @elseif($patient_return[0]->day_weeks=="weeks")
                              <option value="days"   >Days</option>
                                 <option value="weeks"  selected >Weeks</option>
                              @endif  

                                 
                              </select>
                            </div>
                          </div>

                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="no_of_returned_day_weeks">Number of Days or Weeks returned <span style="color:red"> *</span></label>
                              <input type="text" class="form-control" value="{{$patient_return[0]->returned_in_days_weeks}}" maxlength="2" onkeypress="return restrictAlphabets(event);" id="no_of_returned_day_weeks"   name="no_of_returned_day_weeks" placeholder="no of returned day weeks">
                            
                              <label id="dayserror"
                                    style="color:red;display:none"
                                    >please enter 
(1-365) in the number of days or
weeks returned field</label>
                            </div>
                            </div>

                        

                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="dob">Date Of Birth </label>
                              <input type="text" readonly class="form-control" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $patient_return[0]->dob)->format('d/m/Y')}}"  name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('Y-m-d')}}" >
                            </div>
                            </div>

                            <div class="col-md-6">
                            <div class="form-group">
                            <label for="store">Store <span style="color:red"> *</span></label>
                            <select name="store" id="store" class="form-control">
                                @if(isset($all_facilities))
                                @foreach($all_facilities as $row)
                                <option value="{{$row->id}}" @if($patient_return[0]->store==$row->id) selected @endif >{{$row->name}}</option>
                                @endforeach @endif
                            </select>
                            </div>
                            </div>
                            <div class="form-group otherinput">
                                @if($patient_return[0]->store=='5')
                                <input type="text" name="other_store" value="{{$patient_return[0]->other_store}}" required id="other_store" class="form-control"  placeholder="other store">
                                @endif
                            </div>
                            <div class="col-md-12">
                            <div class="form-group">
                               <label for="initials">Staff initials </label>
                               <input type="text" name="initials" value="{{$patient_return[0]->staff_initials}}" id="staff_initials" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)" class="form-control"  placeholder="initials">
                            </div>
</div>
                         
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <button style="width:100%" type="submit" class="btn btn-primary">Update</button>
                                  </div>
                                </div>
                               
                           



                        
                </div>
</div>
</div>
</div>
</div>  

                

                </form>
                


          </div>   <!-- /.row -->
        





      </div><!-- /.content-wrapper -->






@endsection


@section('customjs')


    <script type="text/javascript">

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


$('#form').submit(function() {
    var no_of_returned_day_weeks = $("#no_of_returned_day_weeks").val();
    if (no_of_returned_day_weeks  < 1 || no_of_returned_day_weeks > 365) 
    {
        // alert('you did not fill out one of the fields');
        $("#dayserror").fadeIn();
        $("#no_of_returned_day_weeks").focus();
        return false;
    }
});




      $(function () {
        //Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});
        $('#dob').datepicker({
            format: "dd/mm/yyyy",
            endDate: new Date(),
            autoclose: true
          });
          $('#dob').on('keyup keypress keydown', function(e){
              e.preventDefault();
          });

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

        /* $("input[type=radio][name='who_pickup']").on('ifToggled', function(event){
            var checked = $(this).closest('div.iradio_flat-green').hasClass("checked");
            if($(this).val()=='carer')
            $('.div_carer_name').css('display','block');
            else
            $('.div_carer_name').css('display','none');
        }); */

        $('#patient_name').select2(
        ).on('change', function (e) {
        if(this.value){
              var ob=$(this).children('option:selected');
              var dob=ob.attr('data-dob');

              var lastLocation=ob.attr('data-lastLocation');
              var last_returnStore=ob.attr('data-last_returnStore');
              var last_returnStoreOther=ob.attr('data-last_returnStoreOther');
              $('#dob').val(moment(dob).format('DD/MM/YYYY'));
              if(last_returnStore!=""){
                $('#store').val(last_returnStore);
              }else{
                $('#store').val(1);
              }
              if(last_returnStoreOther!=""){
                $('.otherinput').html('<input type="text" name="other_store" id="other_store" value="'+last_returnStoreOther+'" class="form-control"  placeholder="other store">');
              }
              else{
                $('.otherinput').html('');
              }


           }
        });



      });




    $(document).ready(function(){
        $("#staff_initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });

      });

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
                  }
              });
           }
        });



        $('#store').change(function(){
           if($(this).val()=='5'){
              $('.otherinput').html('<input type="text" name="other_store" id="other_store" required class="form-control"  placeholder="other store">');
            }
           else
           {
            $('.otherinput').html('');
           }
        });





     function restrictAlphabets(e){
      var x=e.which||e.keycode;
      if((x>=48 && x<=57) )
      return true;
      else
      return false;
     }

      //  For   Bootstrap  datatable





    </script>
@endsection
