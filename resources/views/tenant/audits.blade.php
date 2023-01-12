@extends('tenant.layouts.mainlayout')
@section('title') <title>Audits</title>

@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->

 

 <div class="content-wrapper">
      


 <div class="dashborad-header">
 	<div class="pharma-add report-add">
            <a href="{{url('/')}}/audits" class="active family">Add New Audit</a>
            <a href="{{url('/')}}/all_audits" class="family">New Audit Report</a>
         
           
        </div>
</div>
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
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header pre-wrp">
                <form role="form" action="{{url('save_audits')}}" method="post" enctype="multipart/form-data">


                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"
><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Audit</li>
    </ol>
</nav>


</div>
                {{ csrf_field() }}
                <div class="report-forms">

                <div class="col-md-6 m-auto" >

                <div class="patient-information" style="margin-bottom:30px">
                        <h3>Return Information</h3> 
                        <div class="row">
                <div class="form-group col-md-6">
                              <label for="name">{{__('Patient Name')}}<span style="color:red">*</span></label>
                              <select  name="patient_id" id="patient"  class="form-control js-example-basic-multiple"  multiple="multiple">
                                <option value="">{{__('Select Patient')}}</option>
                                @foreach($patients as $patient)
                                  <option {{old('patient_id')==$patient->id?'selected':''}}
                                  data-dob="{{$patient->dob}}"
                                  data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"
                                  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"

                                  data-last_returnStore="{{isset($patient->latestReturn->store)?$patient->latestReturn->store:''}}"
                                    data-last_returnStoreOther="{{isset($patient->latestReturn->other_store)?$patient->latestReturn->other_store:''}}"

                                    data-last_AuditStore="{{isset($patient->latestAudit->store)?$patient->latestAudit->store:''}}"
                                    data-last_AuditStoreOther="{{isset($patient->latestAudit->store_others_desc)?$patient->latestAudit->store_others_desc:''}}"

                                  value="{{$patient->id}}" >{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                @endforeach
                              </select>
                              @error('patient_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6" >
                                <label for="store"> {{__('Store')}}</label>
                                <select name="store" id="store" class="form-control @error('store') is-invalid @enderror">

                                    @forelse($facilities as $facility)
                                      <option   {{old('store')==$facility->id?'selected':''}} value="{{$facility->id}}">{{$facility->name}}</option>
                                      @empty
                                      <option value="">{{__('No Records')}}</option>
                                    @endforelse
                                </select>
                                @error('store')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                                <div class="form-group otherinput" style="margin-top:5px"></div>

                            </div>

                            

                            <div class="form-group col-md-6">
                                <label for="number_of_weeks"> {{__('Number of weeks')}}<span style="color:red">*</span></label>
                                <input type="text"  value="{{old('no_of_weeks')}}" class="form-control @error('no_of_weeks') is-invalid @enderror" maxlength="3" onkeypress="return restrictAlphabets(event);" id="number_of_weeks" name="no_of_weeks" placeholder="no of weeks">
                                @error('no_of_weeks')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>

                              <div class="form-group col-md-6">
                                <label for="staff_initials"> {{__('Staff initials')}}</label>
                                <input type="text"  value="{{old('staff_initials')}}" name="staff_initials" id="staff_initials" class="form-control @error('staff_initials') is-invalid @enderror" placeholder="staff_initials" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)">
                                @error('staff_initials')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>

                              <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"> {{__('Submit')}}</button>
                                <button type="button" onclick="Resetvalues()" class="btn btn-default" id="btn_reset">Reset</button>
                              </div>

      
                            </div>
                            </div>

                             

                        </div>

                      

                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->



      </div><!-- /.content-wrapper -->

@endsection


@section('customjs')


    <script type="text/javascript">

function Resetvalues()
{
    $("#patient").val("");
    $("#patient").trigger("change");
    $("#number_of_weeks").val("");
    $("#store").val("");
    $("#staff_initials").val("");

    $("#pharmacist_signature").val("");
}
      $(function () {
        //Datemask yyyy-mm-dd
        $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});


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
            $('.div_carer_name').css('display','block');
            else
            $('.div_carer_name').css('display','none');
        });

        $('#patient').select2(

        ).on('change', function (e) {
          if(this.value){
                var ob=$(this).children('option:selected');
                var dob=ob.attr('data-dob');
                var lastLocation=ob.attr('data-lastLocation');
                var last_last_AuditStore=ob.attr('data-last_AuditStore');
                var last_AuditStoreOther=ob.attr('data-last_AuditStoreOther');
                $('#dob').val(moment(dob).format('DD/MM/YYYY'));



                  if(last_last_AuditStore!=""){
                    $('#store').val(last_last_AuditStore);
                  }else{
                    $('#store').val(1);
                  }
                  // alert(last_AuditStoreOther);
                  if(last_last_AuditStore=='5'){
                    $('.otherinput').html('<input type="text" required name="store_others_desc" id="other_store" value="'+last_AuditStoreOther+'" class="form-control"  placeholder="other store">');
                  }
                  else{
                    $('.otherinput').html('');
                  }

             }
          });



      });



    //  for chnage the Driver Type  And Set  Automaticaly  Rate of the Driver
    $(document).ready(function(){
      
$("#main-wrap").css("display", "none");
      $("#lblmainheading").html("Audit");
      $("#patient").select2();
        $("#staff_initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });

    });

    $('#store').change(function(){
        if($(this).find('option:selected').text()=='other'){
          $('.otherinput').html('<input type="text" name="store_others_desc" id="store_others_desc" class="form-control"  placeholder="other store">');
        }
        else
        {
          $('.otherinput').html('');
        }
    });
    if($('#store').find('option:selected').text()=='other'){
        $('.otherinput').html('<input type="text" name="store_others_desc" id="store_others_desc" class="form-control"  placeholder="other store">');
    }
       /* End   -- Automatically  set  Driver  And rate */


      //     restrict Alphabets
      function restrictAlphabets(e){
          var x=e.which||e.keycode;
          if((x>=48 && x<=57) || x==8 ||
            (x>=35 && x<=40)|| x==46)
            return true;
          else
            return false;
      }

      //  For   Bootstrap  datatable
      $(function () {

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

    </script>
@endsection
