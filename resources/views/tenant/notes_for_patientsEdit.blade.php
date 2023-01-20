@extends('tenant.layouts.mainlayout')
@section('title') <title>Notes_For_Patient</title>


@endsection


@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Edit Notes for patient</h2>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">

           
        </div>
    </div>
</div>
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
                <form id="add_notes" role="form" action="{{url('notes_for_patients/edit/'.$notes_for_patients->id)}}" method="post" enctype="multipart/form-data">

                <input id="txtphonenumber" style="display:none" name="phonenumber" />
                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Notes for patient </li>
    </ol>
</nav>


</div>


                {{ csrf_field() }}
                <div class="report-forms">
                <div class="col-md-6  m-auto" >
                <div class="patient-information" style="margin-bottom:30px"> 
                        <h3>Notes for patient</h3> 
                        <div class="row">
                            <div class="form-group col-md-6">
                              <label for="patient_name">{{__('Patient Name')}} <span style="color:red">*</span></label>
                                <select name="patient_id" id="patient" class="form-control" >
                                  <option value="">{{__('Select Patient')}}</option>
                                  @foreach($patients as $patient)
                                    <option {{$patient->id==$notes_for_patients->patients->id?'selected=selected':''}}  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}" value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                  @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="dob">{{__('Date Of Birth')}}</label>
                                <input type="text" readonly value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $notes_for_patients->dob)->format('d/m/Y')}}" class="form-control"   name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('Y-m-d')}}" >
                              </div>
                              <div class="form-group col-md-12">
                                <label for="note_for_patient">{{__('Note For Patient')}} <span style="color:red">*</span></label>
                                <textarea class="form-control"  style="resize: none;" rows="4" name="notes_for_patients" id="notes_for_patients"   placeholder="note for patient.">{{$notes_for_patients->notes_for_patients}}</textarea>
                              </div>

                              <div class="form-group col-md-12">
                                <label>
                                    <input {{$notes_for_patients->notes_as_text==1?'checked=checked':''}} type="checkbox" name="notes_as_text" class="minimal" value="1"  />&nbsp;{{__('Send the note as a text message')}} 
                                </label>
                            </div>

                            <div class="col-md-3">
                            <button type="submit" class="btn btn-primary btn-block">{{__('Submit')}}</button>
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

    $('#patient').select2();
    $('#patient').on('change', function (e) {
      if(this.value){
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

    $('#add_notes').submit(function(){

          

      });
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
