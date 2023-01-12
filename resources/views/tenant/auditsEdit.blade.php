@extends('tenant.layouts.mainlayout')
@section('title') <title>Audits</title>

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
                <form role="form" action="{{url('audits/edit/'.$audits->id)}}" method="post" enctype="multipart/form-data">
                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"
><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Audit</li>
    </ol>
</nav>


</div>
                {{ csrf_field() }}
                <div class="report-forms">
                <div class="col-md-6 m-auto" >
                <div class="patient-information" style="margin-bottom:30px">
                        <h3>Audit Information</h3> 
                        <div class="row">
                            <div class="form-group col-md-6">
                              <label for="name">{{__('Patient Name')}}<span style="color:red">*</span></label>
                              <select name="patient_id" id="patient" class="form-control" >
                                <option value="">{{__('Select Patient')}}</option>
                                @foreach($patients as $patient)
                                  <option {{$patient->id==$audits->patients->id?'selected=selected':''}}

                                  data-dob="{{$patient->dob}}"
                                  data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"
                                  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"

                                  data-last_returnStore="{{isset($patient->latestReturn->store)?$patient->latestReturn->store:''}}"
                                    data-last_returnStoreOther="{{isset($patient->latestReturn->other_store)?$patient->latestReturn->other_store:''}}"

                                    data-last_AuditStore="{{isset($patient->latestAudit->store)?$patient->latestAudit->store:''}}"
                                    data-last_AuditStoreOther="{{isset($patient->latestAudit->store_others_desc)?$patient->latestAudit->store_others_desc:''}}"

                                  value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="store"> {{__('Store')}} </label>
                                <select name="store" id="store" class="form-control" >
                                    @forelse($facilities as $facility)
                                      <option value="{{$facility->id}}" {{$audits->store==$facility->id?'selected=selected':''}}>{{$facility->name}}</option>
                                      @empty
                                      <option value="">{{__('No Records')}}</option>
                                    @endforelse
                                </select>

                                <div class="form-group otherinput" style="margin-top:8px">
                               @if(isset($audits->store) && $audits->store=="5" )
                               <input type="text" value="{{$audits->store_others_desc}}" name="store_others_desc" id="store_others_desc" class="form-control"  placeholder="other store">
                               @endif
                             </div>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="number_of_weeks"> {{__('Number of weeks')}} <span style="color:red">*</span> </label>
                                <input type="text" class="form-control" value="{{$audits->no_of_weeks}}" maxlength="3"onkeypress="return restrictAlphabets(event);" id="number_of_weeks"   name="no_of_weeks" placeholder="no of weeks">
                              </div>

                              <div class="form-group form-group col-md-6">
                                <label for="staff_initials"> {{__('Staff initials')}}  </label>
                                <input type="text" name="staff_initials" value="{{$audits->staff_initials}}" id="staff_initials" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  placeholder="staff_initials">
                             </div>

                             
                             <button type="submit" class="btn btn-primary"> {{__('Submit')}}</button>
                             

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
      $("#lblmainheading").html("Edit Audit");
      $("#patient").select2();
        $("#staff_initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });

    });

    $('#store').change(function(){

        if($(this).find('option:selected').text()=='other'){
          $('.otherinput').html('<input type="text" value="{{$audits->store_others_desc}}" name="store_others_desc" id="store_others_desc" class="form-control"  placeholder="other store">');
        }
        else
        {
          $('.otherinput').html('');
        }
    });
    // if($('#store').find('option:selected').text()=='Others'){
    //     $('.otherinput').html('<input type="text" value="{{$audits->store_others_desc}}" name="store_others_desc" id="store_others_desc" class="form-control"  placeholder="other store">');
    // }
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
