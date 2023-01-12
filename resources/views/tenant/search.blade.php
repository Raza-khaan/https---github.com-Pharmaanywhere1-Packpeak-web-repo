@extends('tenant.layouts.mainlayout')
@section('title') <title>Search</title>
@endsection





@section('content')



 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">


        <!-- Main content -->
        <section class="content">
          <div class="row">
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
                <form role="form" action="{{url('search_patient')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="box-body">
                       
<div class="row">
<div class="col-md-4">
                              
                              <div class="form-group">
                                 <label for="name">{{__('Patient Name')}}<span style="color:red">*</span></label>
                                 <select required name="patient_id" id="patient" class="form-control @error('patient_id') is-invalid @enderror" >
                                   <option value="">{{__('Select')}}</option>
                                   @foreach($patients as $patient)
                                     <option  {{old('patient_id')==$patient->id?'selected':''}}  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}" value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}}</option>
                                   @endforeach
                                 </select>
                                   @error('patient_id')
                                     <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                   @enderror
                               </div>
                             </div>
     
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="dob">{{__('Date Of Birth')}}<span style="color:red">*</span></label>
                                     <input required readonly type="date"  value="{{old('dob')}}" class="form-control @error('dob') is-invalid @enderror"   name="dob" id="dob" placeholder="Date Of Birth" >
                                     @error('dob')
                                     <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                     @enderror
                                   </div>
                             </div>
     
                             <div class="col-md-4" style="padding-top: 27px;">
                               <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                               <button onclick="resetvalue()" type="reset" class="btn btn-default">Reset</button>
                             </div>
</div>
                       
                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->


          </div>   <!-- /.row -->
        </section><!-- /.content -->



      </div><!-- /.content-wrapper -->



 

@endsection


@section('customjs')


    <script type="text/javascript">
function resetvalue()
{
  $("#patient").val("");
  $("#patient").trigger("change");
}

$(document).ready(function(){
  $("#patient").select2();
  $("#lblmainheading").html("Search Preview");
});

      $(function () {
        //Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){}); 


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

      
        

      
      

       
    /* get  Patienst  Dote of  birth by Patient  id and Website id  */
    $('#patient').change(function(){
        var ob=$(this).children('option:selected');
        var dob=ob.attr('data-dob');
        var lastPickupDate=ob.attr('data-lastPickupDate');
        var lastPickupWeek=ob.attr('data-lastPickupWeek');
        $('#dob').val(dob);
        $('#last_pickup_date').text(lastPickupDate);
        $('#last_pickup_week').text(lastPickupWeek);

        $('#weeks_last_picked_up').val(lastPickupWeek);
        $('#last_pick_up_date').val(lastPickupDate);
        

      });

    </script>








@endsection
