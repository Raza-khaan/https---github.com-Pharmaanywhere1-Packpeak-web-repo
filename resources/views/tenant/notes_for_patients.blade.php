@extends('tenant.layouts.mainlayout')
@section('title') <title>Notes_For_Patient</title>

@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->



 <div class="content-wrapper">
        

    
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
                <form id="add_notes" role="form" action="{{url('save_notes_for_patients')}}" method="post" enctype="multipart/form-data">
                  <input id="txtphonenumber" style="display:none" name="phonenumber" />
                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Notes for patient </li>
    </ol>
</nav>


</div>

                {{ csrf_field() }}
                <div class="report-forms">
                <div class="col-md-6 m-auto ">

                <div class="patient-information" style="margin-bottom:30px">
                        <h3>Notes for patient</h3> 
                        <div class="row">
                            <div class="form-group col-md-6">
                              <label for="patient_name">{{__('Patient Name')}} <span style="color:red">*</span></label>
                                <select required name="patient_id" id="patient" class="form-control @error('patient_id') is-invalid @enderror" >
                                  <option value="">{{__('Select Patient')}}</option>
                                  @foreach($patients as $patient)
                                    <option  {{old('patient_id')==$patient->id?'selected':''}}  data-phonenumber = "{{$patient->phone_number}}"  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}" value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                  @endforeach
                                </select>
                                @error('patient_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="dob">{{__('Date Of Birth')}} </label>
                                <input value="{{old('dob')}}" readonly  type="text" class="form-control @error('dob') is-invalid @enderror" max="{{Carbon\Carbon::now()->format('d/m/Y')}}"  name="dob" id="dob" placeholder="Date Of Birth" >
                                @error('dob')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>


                              <div class="form-group col-md-12">
                                <label for="notes_for_patients">{{__('Note For Patient')}} <span style="color:red">*</span></label>
                                <textarea required class="form-control @error('notes_for_patients') is-invalid @enderror"  style="resize: none;" rows="4" name="notes_for_patients" id="notes_for_patients"   placeholder="Note For Patient.">{{old('notes_for_patients')}}</textarea>
                                @error('notes_for_patients')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>


                               
                            <div class="form-group col-md-12">
                            @if ($Smssendlimit == 0)
                               <p style="color:red">SMS Limit Reached<p>
                               <label>
                                    <input disabled="disabled" type="checkbox" 
                                     {{old('notes_as_text')?'checked':''}} name="notes_as_text"
                                      class="minimal @error('notes_as_text') is-invalid @enderror" value="1"  />
                                  {{__('Send the note as a text message')}}
                                      <strong>
                                       ({{$usedsms}}/{{$Allowedsms}}  Sms Used) <strong>
                                </label>
                                @error('notes_as_text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                               @else

                               <label>
                                    <input  type="checkbox" 
                                     {{old('notes_as_text')?'checked':''}} name="notes_as_text"
                                      class="minimal @error('notes_as_text') is-invalid @enderror" value="1"  />
                                  {{__('Send the note as a text message')}}
                                      <strong>
                                       ({{$usedsms}}/{{$Allowedsms}}  Sms Used) <strong>
                                </label>
                                @error('notes_as_text')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                               @endif
                                
                            </div>
    
    <button type="submit" class="btn btn-primary btn-block">{{__('Submit')}}</button>
  

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

    $("input[type=radio][name='who_pickup']").on('ifToggled', function(event){
        var checked = $(this).closest('div.iradio_flat-green').hasClass("checked");
        if($(this).val()=='carer')
        $('.div_carer_name').css('display','block');
        else
        $('.div_carer_name').css('display','none');
    });  
      
  });
  
  //  for chnage the Driver Type  And Set  Automaticaly  Rate of the Driver
  $(document).ready(function(){
    $("#lblmainheading").html("Notes for patient");
    $("#patient").select2();
    $('#patient').on('change', function (e) {
      if(this.value)
      {
            var ob=$(this).children('option:selected');
            var dob=ob.attr('data-dob');
            $('#dob').val(moment(dob).format('DD/MM/YYYY'));
            var phonenumber=ob.attr('data-phonenumber');
            
            $("#txtphonenumber").val(phonenumber);

         }
         else
         {
          $('#dob').val("");
          $("#txtphonenumber").val("");
         }
      });

    // $('#add_notes').submit(function(){
    //       let obj1=$('input[type=checkbox][name="notes_as_text"]');
    //       let obj=$('input[type=checkbox][name="notes_as_text"]:checkbox:checked');

    //       if(obj.length>0){
    //         obj1.parent('div').removeClass('icheckbox_minimal-red hover');
    //         obj1.parent('div').addClass('icheckbox_minimal-blue');
    //         return true;
    //       }else{
    //         obj1.parent('div').removeClass('icheckbox_minimal-blue');
    //         obj1.parent('div').addClass('icheckbox_minimal-red hover');

    //         return  false;
    //       }

    //   });
  });

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
