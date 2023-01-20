@extends('tenant.layouts.mainlayout')
@section('title') <title>PickUps</title>


@endsection

@section('content')
<style>
  .family
    {
      font-family:inherit !Important;
    }
</style>





<div class="dash-wrap">
    <div class="dashborad-header">
    <div class="pharma-add report-add">
            <a href="#" class="active family">Add Pickups</a>
            <a href="{{url('/')}}/pickups_reports" class="family">Pickups Report</a>
            <a href="{{url('/')}}/pickups_calender" class="family">Pickups Calender</a>
            <a href="{{url('/')}}/all_compliance" class="family">Index Report</a>
           
        </div>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button style="background-color:transparent" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                    
                      <a class="dropdown-item" href="{{url('logout')}}">Logout</a>
                    </div>
                  </div>
                  
                </div>
            </div>
    </div>
</div>
 <!-- Content Wrapper. Contains page content -->


 
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

 <div class="content-wrapper">
     

         
      
            @if(Session::has('msg'))
              {!!Session::get("msg") !!}
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
                <form role="form" id="addPick" action="{{url('add_pickups')}}" method="post" enctype="multipart/form-data">

                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a class="family" href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a class="family" href="#">Pickups</a></li>
        <li class="breadcrumb-item active family" aria-current="page">Add Pickups </li>
    </ol>
</nav>
<div class="reset-patien">
                
<a class="btn reset-btn"  onclick="reset()">Reset</a>
<button type="submit" class="btn reset-btn add-pat-btn" name="submit" value="Submit">Submit <i class="ml-2 fas fa-arrow-circle-right"></i></button>
            </div>

</div>
                {{ csrf_field() }}
                <div class="report-forms">
                <div class="row">

                <input style="display:none" class="form-control" name="patientname" value="" id="patientname"/>
                <input style="display:none" class="form-control" name="numberofweekspicup" value="" id="numberofweekspicup"/>
                        <div class="col-md-6">
                        <div class="patient-information">
                        <h3  class="family">Packs Pickup
                           <a onclick="patientnotes()" class="btn btn-md text-danger  family"
                            style="font-size:17px;background-color:transparent;float:right;border:1px solid grey;color:grey !important" 
                           
                            data-toggle="modal" data-target="#exampleModal"
                            > Notes from  Patient <i class="fa fa-info-circle"></i></a>
                          </h3> 
                          <input id="txtlastpatientnotes" hidden value=""/>
                        <div class="row">
                          <div class="form-group col-md-12">
                            <label class="family" for="name">{{__('Select patient')}}<span style="color:red">*</span></label>
                            <select  required name="patient_id" id="patient" class="form-control" >
                              <option value="">{{__('Select Patient')}}</option>
                              @foreach($patients as $patient)

                              @php
                                 $row=$patient;
                                $location="";
                                $lastnotes="";

                                $rowid = $row->id;
                              
                                $checkinglocations=App\Models\Tenant\Pickups::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
                                
                                
                                if(!empty($checkinglocations))
                                {
                                  $lastnotes =$checkinglocations->notes_from_patient;
                                }
                                
                                $Patientlocations=App\Models\Tenant\Patient::where('id',$row->id)->orderBy('created_at','desc')->first();
                                $PLocations=App\Models\Tenant\PatientLocation::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
                                if(!empty($checkinglocations) && $checkinglocations->location!="" && !empty($Patientlocations) && $Patientlocations->location!=NULL){
                                        $checkinglocation=$checkinglocations->location;
                                        $patientlocation=$Patientlocations->location;
                                        $checkinglocation=explode(',',$checkinglocation);
                                        $patientlocation=explode(',',$patientlocation);
                                        $all_location=array_merge($checkinglocation,$patientlocation);
                                        $all_location=array_unique($all_location);
                                        $location=implode(',',$all_location);
                                        
                                }
                                elseif(!empty($checkinglocations) && $checkinglocations->location!=""){
                                    $location=$checkinglocations->location;
                                    
                                }
                                elseif(!empty($Patientlocations) && $Patientlocations->location!=NULL){
                                    $location=$Patientlocations->location;
                                    
                                }
                                $patientLastNoteForPatient=App\Models\Tenant\NotesForPatient::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
                                $last_noteForPatient="";
                                $last_noteForPatientDate="";

                                if(!empty($checkinglocations) && !empty($patientLastNoteForPatient)){
                                      if($patientLastNoteForPatient->created_at > $checkinglocations->created_at){
                                        $last_noteForPatient=$patientLastNoteForPatient->notes_for_patients;
                                        $last_noteForPatientDate=$patientLastNoteForPatient->created_at;
                                      }
                                      else{
                                        $last_noteForPatient=$checkinglocations->note_from_patient;
                                        $last_noteForPatientDate=$checkinglocations->created_at;
                                      }
                                      
                                    }
                                elseif(!empty($patientLastNoteForPatient)){
                                      $last_noteForPatient=$patientLastNoteForPatient->notes_for_patients;
                                      $last_noteForPatientDate=$patientLastNoteForPatient->created_at;
                                      
                                }
                                elseif(!empty($checkinglocations)){
                                    $last_noteForPatient=$checkinglocations->note_from_patient;
                                    $last_noteForPatientDate=$checkinglocations->created_at;
                                    
                                }
                          @endphp

                                <option  {{old('patient_id')==$patient->id?'selected':''}}  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"
                             data-lastpickupnotes = "{{$lastnotes}}"
                                data-patientname = "{{$patient->first_name.' '.$patient->last_name}}"
                                data-lastNoteForPatient="{{$patient->latestPickups?$patient->latestPickups->notes_from_patient:''}}"
                                data-lastLocation="{{isset($PLocations->locations)?$PLocations->locations:''}}"
             data-last_pick_up_by="{{isset($patient->latestPickups->pick_up_by)?$patient->latestPickups->pick_up_by:''}}"
             data-last_carer_name="{{isset($patient->latestPickups->carer_name)?$patient->latestPickups->carer_name:''}}"
             data-last_noteForPatient="{{isset($last_noteForPatient)?$last_noteForPatient:''}}"
             data-last_noteForPatientDate="{{!empty($last_noteForPatient)?$last_noteForPatientDate:''}}"
                                  value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                              @endforeach
                            </select>
                              @error('patient_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                          </div>

                           
                            <div class="form-group notes_for_patient_div">

                            </div>

                            

                            <div class="form-group col-md-12" >
                                <label for="dob" class="family">{{__('Date Of Birth')}}</label>
                                <input type="text" readonly value="{{old('dob')}}" class="form-control @error('dob') is-invalid @enderror"  max="{{Carbon\Carbon::now()->format('d/m/Y')}}"  name="dob" id="dob" placeholder="Date Of Birth" >
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>

                              <div class="form-group checkbox-wrp col-md-6">
                              <label class="mt-20 family" id="locationDiv">{{__('Packs Location')}} </label>  <br/>
                                @foreach($locations as $location)
                                  <label class="family">
                                      <input {{ (is_array(old('location')) and in_array($location->id, old('location'))) ? ' checked' : '' }} type="checkbox" name="location[]" class="minimal" value="{{$location->id}}"  />&nbsp;{{$location->name}}                                </label>
                                @endforeach
                              </div>

                              <div class="form-group col-md-12">
                              <label class="family" for="no_of_weeks">{{__('Number of Weeks ')}}<span style="color:red">*</span></label>
                              <input type="text" readonly   value="{{(old('no_of_weeks'))?old('no_of_weeks'):$default_cycle[0]['default_cycle']}}"  class="form-control @error('no_of_weeks') is-invalid @enderror" maxlength="3" onkeypress="return restrictAlphabets(event);" id="no_of_weeks"   name="no_of_weeks" placeholder="No Of Weeks">
                              @error('no_of_weeks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                              
                              
                             





                        </div>
                              </div>
                              </div>

                            



                              <div class="col-md-6">
                              <div class="patient-information">
                        <h3 class="family">Pickup Information</h3>
                        <div class="row">
                            
                        
                        <div class="col-md-7">
                        <label class="family" for="no_of_weeks">{{__('Last Pickup Date')}}</label>
                            </div>
                            <div class="col-md-5">
                            <label class="family" for="no_of_weeks">{{__('# of Weeks Last Picked Up')}}</label>
                            </div>
                              
                                <div class="form-group  col-md-2 col-lg-2 col-xl-2">
                                <input type="text" class="form-control" readonly="readonly" id="last_pick_up_day" name="last_pick_up_day" placeholder="15">
                                </div>
                                <div class="form-group  col-md-2 col-lg-2 col-xl-2">
                                <input type="text" class="form-control" readonly="readonly" id="last_pick_up_month" name="last_pick_up_month" placeholder="05">
                                </div>

                                <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                <input type="text" class="form-control" readonly="readonly" id="last_pick_up_year" name="last_pick_up_year" placeholder="2022">
                                </div>


                                
                                <div class="form-group col-md-5 col-lg-5 col-xl-5">
                                
                              <input style="display:none"   class="form-control @error('no_of_weeks') is-invalid @enderror" type="text" style="border: none; box-shadow: none; color:blue;" id="last_pick_up_date" name="last_pick_up_date" readonly="readonly" value="{{old('last_pick_up_date')}}">
                              <input  style="display:none" name="created_at" value="{{$created_at?$created_at:''}}" class="form-control">
                            

                              

<!-- <b><span id="last_pickup_week" class="text-primary"></span></b> -->
<input type="text" class="form-control" style="border: none; box-shadow: none; color:blue;" id="weeks_last_picked_up" readonly="readonly" name="weeks_last_picked_up" value="{{old('weeks_last_picked_up')}}">

                                </div>
                              

              

                         
                            
                        </div>
                    </div>
<br/>

<div class="patient-information">
                              <h3 class="family">Who is Picking Up?</h3>
                                    <div class="row">


                              <div class="col-md-6">

                              <div class="form-group">
                              
                                  <label class="family">
                                    <input  onclick="showhidecarrier(1)" {{old('pick_up_by')=='carer'?'checked':''}} type="radio" name="pick_up_by"   class="flat-red" value="carer"  /> {{__('Carer')}}
                                  </label>

                                  <label class="family">
                                    <input onclick="showhidecarrier(0)" {{old('pick_up_by')=='delivery'?'checked':''}} type="radio" name="pick_up_by"   class="flat-red" value="delivery"  /> {{__('Delivery')}}
                                  </label>
                                  <label class="family">
                                    <input onclick="showhidecarrier(0)" {{old('pick_up_by')=='patient'?'checked':''}} type="radio" name="pick_up_by"   class="flat-red" value="patient"  /> {{__('Patient')}}
                                  </label>
                              </div>

                              <div class="form-group div_carer_name"  id="div_carer_name" style="display:none">
                                
                                 <label for="carer_name">Carer`s Name <span class="text-danger"> *</span>
                                </label> <input type="text" class="form-control" maxlength="20"
                                 value="{{old('carer_name')}}"  id="carer_name" name="carer_name" placeholder="Carer Name..">
                                                          </div>
                       
                            </div>
                                    </div>
                              

                             
                             
                              

                            </div> 

                              </div>

                        </div>
                        <br/>




 <div class="row">
 <div class="col-md-6">
               
               <input type="hidden" id="pharmacist_sign" name="pharmacist_sign" value=""  />
               <input type="hidden" id="patient_sign"    name="patient_sign"    value=""  />
                            <div class="notes sign-note">
                                <h3 class="family">Pharmacist Signature</h3>
                                <div class="note-text text-center signature-component">
                                    <canvas id="signature-pad" style="touch-action: none;"></canvas>
                                    <a href="javascript:void(0)" id="signature-pad-clear"><i class="fas fa-times-circle"></i></a>
                                    <textarea name="pharmacist_signature" id="pharmacist_signature" style="display:none;"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                    <div class="notes sign-note">
                        <h3 class="signature_who_pickup  family" id="lblpickupsignature"> Patient Signature</h3>
                        <div class="note-text text-center signature-component">
                            <canvas id="signature-pad2" style="touch-action: none;"></canvas>
                            <a href="javascript:void(0)" id="signature-pad2-clear">
                                <!-- <input type="file" class="form-control" size="60"> -->
                                <i class="fas fa-times-circle"></i>
                            </a>
                            <textarea name="patient_signature" id="patient_signature" style="display:none;"></textarea>
                        </div>
                    </div>
                </div>
 </div>
    

    

     <!-- END OF FIRST SIGNATURE  -->

    

    <!-- <div class="form-group">
      <label for="patient_sign"  id="patientSignDiv"> <span class="signature_who_pickup">Patients</span>{{__(' Signature')}}<span style="color:red">*</span></label>
    </div>
    <div class="row">
       <div class="col-md-7">
          <section class="signature-component">
              <canvas id="signature-pad2" style="border:1px solid black"></canvas>
          </section>
       </div>

       <div class="col-md-5">
        <div class="signature-component btn btn-group">
          <button type="button" id="signature-pad2-clear">Clear</button>
          <button type="button" id="signature-pad2-showPointsToggle">Show points?</button>
        </div>
           <div >Draw Your Signature</div>
       </div>
    </div>END OS SECOND SIGNATURE  -->


      







<div class="col-md-12" style="padding-left:0px;padding-right:0px" id="notesdiv">
                    <div class="notes">
                        <h3 class="family">Patient requests:</h3>
                        <textarea class="form-control @error('notes_from_patient') is-invalid @enderror note-text"  style="resize: none;" rows="4" name="notes_from_patient" id="note"   placeholder="Note From Patient ..."> {{old('notes_from_patient')}} </textarea>
                    </div>
                </div>
                
                      

                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->


   



         <!-- Main content -->


      </div><!-- /.content-wrapper -->



@endsection


@section('customjs')


    <script type="text/javascript">


function reset()
{

  
  $("#patient").val("");
  $("#patient").trigger('change');
  $("#dob").val("");
  $("#last_pick_up_day").val("");
  $("#last_pick_up_month").val("");
  $("#last_pick_up_year").val("");

  

  
// var saveButton = document.getElementById('signature-pad-save'),
var clearButton = document.getElementById('signature-pad-clear');

clearButton.addEventListener('click', function (event) {
  signaturePad.clear();
});



var  clearButton2 = document.getElementById('signature-pad2-clear');

clearButton2.addEventListener('click', function(event) {
    signaturePad2.clear();
    //document.getElementById('patient_sign').value = "";
});


$("#note").val("");
  
}
function patientnotes()
{
  var patientid = $("#patient").val();

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

function showhidecarrier(type)
{
  if(type == 1)
  {

    $("#lblpickupsignature").html("Carer's signature");
    $("#div_carer_name").fadeIn();
  }
 else 
  {
    $("#lblpickupsignature").html("Patient Signature");
    $("#div_carer_name").fadeOut();
  }
}


        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.


      $(function () {
        //Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});

        // $('#dob').datepicker({
        //     format: "dd/mm/yyyy",
        //     endDate: new Date(),
        //     autoclose: true
        //   });
          // $('#dob').on('keyup keypress keydown', function(e){
          //     e.preventDefault();
          // });
        //iCheck for checkbox and radio inputs
        // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        //   checkboxClass: 'icheckbox_minimal-blue',
        //   radioClass: 'iradio_minimal-blue'
        // });
        // //Red color scheme for iCheck
        // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        //   checkboxClass: 'icheckbox_minimal-red',
        //   radioClass: 'iradio_minimal-red'
        // });
        // //Flat red color scheme for iCheck
        // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        //   checkboxClass: 'icheckbox_flat-green',
        //   radioClass: 'iradio_flat-green'
        // });

        $("input[type=radio][name='pick_up_by']").on('ifToggled', function(event){


          alert("Hi");
            var checked = $(this).closest('div.iradio_flat-green').hasClass("checked");

            if($(this).val()=='carer'){
              //$('#div_carer_name').css('display','block');
              $('.div_carer_name').html('<label for="carer_name">Carer`s Name <span style="color:red">*</span></label> <input type="text" class="form-control" maxlength="20"   id="carer_name" name="carer_name" placeholder="Carer Name..">');
              //$('#carer_name').attr('',true);
              $('.signature_who_pickup').html('Carer');
            }else{
              // $('#div_carer_name').css('display','none');
              $('.div_carer_name').html('');
              //$('#carer_name').removeAttr('');
              $('.signature_who_pickup').html('Patients');
            }
        });

        $('#patient').change(function(){
        var ob=$(this).children('option:selected');
        var dob=ob.attr('data-dob');
        var lastPickupDate=ob.attr('data-lastPickupDate');
        var lastPickupWeek=ob.attr('data-lastPickupWeek');
        var lastNoteForPatient=ob.attr('data-lastNoteForPatient');
        $('#dob').val(dob);
        $('#last_pickup_date').text(lastPickupDate);
        $('#last_pickup_week').text(lastPickupWeek);

        $('#weeks_last_picked_up').val(lastPickupWeek);
        $('#last_pick_up_date').val(lastPickupDate);
        $('#note').html(lastNoteForPatient);
      });

      function getWeeksDiff(startDate, endDate) {
  const msInWeek = 1000 * 60 * 60 * 24 * 7;

  return Math.round(Math.abs(endDate - startDate) / msInWeek);
}


      $('#patient').on('change', function (e) {
          if(this.value){
                var ob=$(this).children('option:selected');
                var dob=ob.attr('data-dob');
                var lastpickupnotes = ob.attr('data-lastpickupnotes');
                var lastPickupDate=ob.attr('data-lastPickupDate');
                var lastPickupWeek=ob.attr('data-lastPickupWeek');
                var lastNoteForPatient=ob.attr('data-lastNoteForPatient');
                var lastLocation=ob.attr('data-lastLocation');
                var last_pick_up_by=ob.attr('data-last_pick_up_by');
                var last_carer_name=ob.attr('data-last_carer_name');
                var last_noteForPatient=ob.attr('data-last_noteForPatient');
                var last_noteForPatientDate=ob.attr('data-last_noteForPatientDate');
            var patientname = ob.attr('data-patientname');
          $("#txtlastpatientnotes").val(lastpickupnotes);



            $("#patientname").val(patientname);
                var pharmacycyle = $("#no_of_weeks").val();
                var weekstobepikcuped = moment(lastPickupDate).format('MM/DD/YYYY');
                
                if(lastPickupDate !="")
                {
   // var weekstobepikcuped = moment(lastPickupDate).format('DD/MM/YYYY');
   console.log("Last pickup date: "+  moment(weekstobepikcuped).format('DD/MM/YYYY'));
                var theDate = new Date(weekstobepikcuped);
                pharmacycyledays = parseInt(pharmacycyle) *  parseInt(7);
                theDate.setDate(theDate.getDate()+ parseInt(pharmacycyledays ) );
                console.log("pharmcacy cycle weeks:" +  pharmacycyle);
                console.log("pharmcacy cycle days:" +  pharmacycyledays);
                console.log("Next Pickup Date: " + moment(theDate).format('DD/MM/YYYY'));
                var currentDate = new Date();
                currentDate.setDate(currentDate.getDate());
                console.log("current date: " + moment(currentDate).format('DD/MM/YYYY'));

                
                // var weekstonextpickup =  Math.round(Math.abs(moment(theDate).format('DD/MM/YYYY') - moment(currentDate).format('DD/MM/YYYY')) / msInWeek);
                var diffInMs = Math.abs(new Date(moment(currentDate).format('MM/DD/YYYY')) - new Date(moment(theDate).format('MM/DD/YYYY')));
                diffInMs = diffInMs / (1000 * 60 * 60 * 24);
                diffInMs = diffInMs / 7;
                diffInMs = Math.round(diffInMs);
                var weekstonextpickup =  getWeeksDiff(new Date(moment(theDate).format('DD/MM/YYYY')), new Date(moment(currentDate).format('DD/MM/YYYY')));
                console.log("Weeks to  be next pickup : " + diffInMs);
                
                $("#numberofweekspicup").val(diffInMs);
                // var theDate = new Date("10/28/2011");
                // theDate.setDate(theDate.getDate()+2);
                // alert(moment(theDate).format('DD/MM/YYYY'));


                // alert(weekstobepikcuped + " cycle: " + pharmacycyle);
                }
             


                if(dob){
                  $('#dob').val(moment(dob).format('DD/MM/YYYY'));
                }
                else{
                  $('#dob').val("");
                }
                if(lastPickupDate){
                  $('#last_pick_up_date').val(moment(lastPickupDate).format('DD/MM/YYYY'));
                  
                  var date  = moment(lastPickupDate).format('DD/MM/YYYY');
                 
                    $("#last_pick_up_day").val(date.substring(0, 2));
                    $("#last_pick_up_month").val(date.substring(3, 5));
                    $("#last_pick_up_year").val(date.substring(6, 10));
                }
                else{
                  $('#last_pick_up_date').val("");
                }
                if(lastPickupWeek){
                  $('#weeks_last_picked_up').val(lastPickupWeek);
                }
                else{
                  $('#weeks_last_picked_up').val('');
                }

                // $('#note').html(lastNoteForPatient);
                if(lastNoteForPatient){
                    lastNoteForPatient=' ( '+lastNoteForPatient+' )  ';
                  }

                  // $('.lastnote').html(lastNoteForPatient);

                  var notespatient = "";
                // console.log(lastLocation)
                // con"sole.log(typeof lastLocation)
                // if(last_noteForPatient || last_noteForPatientDate ){
                if(last_noteForPatient ){
                //     $('.notes_for_patient_div').html('<label for="no_of_weeks">{{__('Note For Patient')}}</label><br/>\
                // <textarea name="notes_for_patient" style="resize:none; height:20px !important;" readonly id="notes_for_patient" class="form-control" >'+last_noteForPatient+' ( note added '+moment(last_noteForPatientDate).format('DD/MM/YYYY hh:mm:ss A')+' )</textarea>');
                
                // +' ( note added '+moment(last_noteForPatientDate).format('DD/MM/YYYY hh:mm:ss A')+' )
                notespatient = last_noteForPatient +  " ( note added" + " " + moment(last_noteForPatientDate).format('DD/MM/YYYY hh:mm:ss A') + " )"  ;
              $("#note").val(notespatient);
              }
                  else{
                  // $('.notes_for_patient_div').html('');
                  $("#note").val(" ");
                  }
                if(lastLocation){
                        let arr = lastLocation.split(',');
                        // console.log(arr.length)
                        if(arr.length){
                          $('input[type=checkbox]').parent().removeClass("checked");
                          // $('input[type=checkbox]').removeAttr("checked");
                          for(var i=0; i < arr.length;  i++){
                            // console.log(arr[i])
                            // console.log(typeof arr[i])
                            $('input[type=checkbox][value='+arr[i]+']').parent().addClass("checked");
                            $('input[type=checkbox][value='+arr[i]+']').attr("checked",'checked');

                           }
                        }
                      }
                      else{
                        $('input[type=checkbox]').parent().removeClass("checked");
                        // $('input[type=checkbox]').removeAttr("checked");
                      }
                      if(last_pick_up_by){
                        // console.log(last_pick_up_by)
                        $('input[type=radio]').parent().removeClass("checked");
                        // $('input[type=radio]').removeAttr("checked");
                        $('input[type=radio][value='+last_pick_up_by+']').parent().addClass("checked");
                        $('input[type=radio][value='+last_pick_up_by+']').attr("checked","checked");

                      }
                      else{
                        $('input[type=radio]').parent().removeClass("checked");
                        // $('input[type=radio]').removeAttr("checked");
                      }
                      // if(last_carer_name){
                      //   $('.div_carer_name').html('<label for="carer_name">Carer`s Name <span style="color:red">*</span></label> <input type="text" class="form-control" maxlength="20" value="'+last_carer_name+'"  id="carer_name" name="carer_name" placeholder="Carer Name..">');
                      // }
                      // else{
                      //   $('.div_carer_name').html("");
                      // }
                      // console.log(last_carer_name)
                      if(lastNoteForPatient){
                        
                        // $('.lastnotedate').html(moment(lastPickupDate).format('DD/MM/YYYY'));
                      }
                      else{
                        $('.lastnotedate').html("");
                      }
             }
          });





      });



    //  for chnage the Driver Type  And Set  Automaticaly  Rate of the Driver
    $(document).ready(function(){

      $("#main-wrap").css("display", "none");
      $("#patient").select2();
      
  $('#addPick').submit(function(){
        var err=0;
        let pat_sign=$('#patient_sign').val();
        let phar_sign=$('#pharmacist_sign').val();
        let pick_up_by=$('input[name="pick_up_by"]:checked');

        if(pick_up_by.length==0 ){
            ++err;


            if(pick_up_by.length==0){
              $('#pickupDiv').css('color','red');

            }else {
              $('#pickupDiv').css('color','');
            }


            return false;
        }

      });


      });
       /* End   -- Automatically  set  Driver  And rate */


      //     restrict Alphabets
      function restrictAlphabets(e){
        var x=e.which||e.keycode;
        if((x>=48 && x<=57) )
        return true;
        else
        return false;
     }





    </script>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="{{ URL::asset('admin/dist/js/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js')}}"></script>

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
var SignaturePad = function (document) {
  "use strict";

  var log = console.log.bind(console);

  var SignaturePad = function (canvas, options) {
    var self = this,
    opts = options || {};

    this.velocityFilterWeight = opts.velocityFilterWeight || 0.7;
    this.minWidth = opts.minWidth || 0.5;
    this.maxWidth = opts.maxWidth || 2.5;
    this.dotSize = opts.dotSize || function () {
      return (self.minWidth + self.maxWidth) / 2;
    };
    this.penColor = opts.penColor || "black";
    this.backgroundColor = opts.backgroundColor || "rgba(0,0,0,0)";
    this.throttle = opts.throttle || 0;
    this.throttleOptions = {
      leading: true,
      trailing: true };

    this.minPointDistance = opts.minPointDistance || 0;
    this.onEnd = opts.onEnd;
    this.onBegin = opts.onBegin;

    this._canvas = canvas;
    this._ctx = canvas.getContext("2d");
    this._ctx.lineCap = 'round';
    this.clear();

    // we need add these inline so they are available to unbind while still having
    //  access to 'self' we could use _.bind but it's not worth adding a dependency
    this._handleMouseDown = function (event) {
      if (event.which === 1) {
        self._mouseButtonDown = true;
        self._strokeBegin(event);
      }
    };

    var _handleMouseMove = function (event) {
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

    this._handleMouseUp = function (event) {
      if (event.which === 1 && self._mouseButtonDown) {
        self._mouseButtonDown = false;
        self._strokeEnd(event);
        // console.log($(self._canvas).attr('id'))
        if($(self._canvas).attr('id')=='signature-pad') {
        document.getElementById('pharmacist_sign').value = signaturePad.toDataURL();
         console.log(signaturePad.toDataURL())
        }
        if($(self._canvas).attr('id')=='signature-pad2'){
          document.getElementById('patient_sign').value = signaturePad2.toDataURL();
          console.log(signaturePad2.toDataURL())
        }
      }
    };

    this._handleTouchStart = function (event) {
      if (event.targetTouches.length == 1) {
        var touch = event.changedTouches[0];
        self._strokeBegin(touch);
      }
    };

    var _handleTouchMove = function (event) {
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

    this._handleTouchEnd = function (event) {
      var wasCanvasTouched = event.target === self._canvas;
      if (wasCanvasTouched) {
        event.preventDefault();
        self._strokeEnd(event);
      }
    };

    this._handleMouseEvents();
    this._handleTouchEvents();
  };

  SignaturePad.prototype.clear = function () {
    var ctx = this._ctx,
    canvas = this._canvas;

    ctx.fillStyle = this.backgroundColor;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    this._reset();
  };

  SignaturePad.prototype.showPointsToggle = function () {
    this.arePointsDisplayed = !this.arePointsDisplayed;
  };

  SignaturePad.prototype.toDataURL = function (imageType, quality) {
    var canvas = this._canvas;
    return canvas.toDataURL.apply(canvas, arguments);
  };

  SignaturePad.prototype.fromDataURL = function (dataUrl) {
    var self = this,
    image = new Image(),
    ratio = window.devicePixelRatio || 1,
    width = this._canvas.width / ratio,
    height = this._canvas.height / ratio;

    this._reset();
    image.src = dataUrl;
    image.onload = function () {
      self._ctx.drawImage(image, 0, 0, width, height);
    };
    this._isEmpty = false;
  };

  SignaturePad.prototype._strokeUpdate = function (event) {
    var point = this._createPoint(event);
    if (this._isPointToBeUsed(point)) {
      this._addPoint(point);
    }
  };

  var pointsSkippedFromBeingAdded = 0;
  SignaturePad.prototype._isPointToBeUsed = function (point) {
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

  SignaturePad.prototype._strokeBegin = function (event) {
    this._reset();
    this._strokeUpdate(event);
    if (typeof this.onBegin === 'function') {
      this.onBegin(event);
    }
  };

  SignaturePad.prototype._strokeDraw = function (point) {
    var ctx = this._ctx,
    dotSize = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;

    ctx.beginPath();
    this._drawPoint(point.x, point.y, dotSize);
    ctx.closePath();
    ctx.fill();
  };

  SignaturePad.prototype._strokeEnd = function (event) {
    var canDrawCurve = this.points.length > 2,
    point = this.points[0];

    if (!canDrawCurve && point) {
      this._strokeDraw(point);
    }
    if (typeof this.onEnd === 'function') {
      this.onEnd(event);
    }
  };

  SignaturePad.prototype._handleMouseEvents = function () {
    this._mouseButtonDown = false;

    this._canvas.addEventListener("mousedown", this._handleMouseDown);
    this._canvas.addEventListener("mousemove", this._handleMouseMove);
    document.addEventListener("mouseup", this._handleMouseUp);
  };

  SignaturePad.prototype._handleTouchEvents = function () {
    // Pass touch events to canvas element on mobile IE11 and Edge.
    this._canvas.style.msTouchAction = 'none';
    this._canvas.style.touchAction = 'none';

    this._canvas.addEventListener("touchstart", this._handleTouchStart);
    this._canvas.addEventListener("touchmove", this._handleTouchMove);
    this._canvas.addEventListener("touchend", this._handleTouchEnd);
  };

  SignaturePad.prototype.on = function () {
    this._handleMouseEvents();
    this._handleTouchEvents();
  };

  SignaturePad.prototype.off = function () {
    this._canvas.removeEventListener("mousedown", this._handleMouseDown);
    this._canvas.removeEventListener("mousemove", this._handleMouseMove);
    document.removeEventListener("mouseup", this._handleMouseUp);

    this._canvas.removeEventListener("touchstart", this._handleTouchStart);
    this._canvas.removeEventListener("touchmove", this._handleTouchMove);
    this._canvas.removeEventListener("touchend", this._handleTouchEnd);
  };

  SignaturePad.prototype.isEmpty = function () {
    return this._isEmpty;
  };

  SignaturePad.prototype._reset = function () {
    this.points = [];
    this._lastVelocity = 0;
    this._lastWidth = (this.minWidth + this.maxWidth) / 2;
    this._isEmpty = true;
    this._ctx.fillStyle = this.penColor;
  };

  SignaturePad.prototype._createPoint = function (event) {
    var rect = this._canvas.getBoundingClientRect();
    return new Point(
    event.clientX - rect.left,
    event.clientY - rect.top);

  };

  SignaturePad.prototype._addPoint = function (point) {
    var points = this.points,
    c2,c3,
    curve,tmp;

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

  SignaturePad.prototype._calculateCurveControlPoints = function (s1, s2, s3) {
    var dx1 = s1.x - s2.x,
    dy1 = s1.y - s2.y,
    dx2 = s2.x - s3.x,
    dy2 = s2.y - s3.y,

    m1 = {
      x: (s1.x + s2.x) / 2.0,
      y: (s1.y + s2.y) / 2.0 },

    m2 = {
      x: (s2.x + s3.x) / 2.0,
      y: (s2.y + s3.y) / 2.0 },


    l1 = Math.sqrt(1.0 * dx1 * dx1 + dy1 * dy1),
    l2 = Math.sqrt(1.0 * dx2 * dx2 + dy2 * dy2),

    dxm = m1.x - m2.x,
    dym = m1.y - m2.y,

    k = l2 / (l1 + l2),
    cm = {
      x: m2.x + dxm * k,
      y: m2.y + dym * k },


    tx = s2.x - cm.x,
    ty = s2.y - cm.y;

    return {
      c1: new Point(m1.x + tx, m1.y + ty),
      c2: new Point(m2.x + tx, m2.y + ty) };

  };

  SignaturePad.prototype._addCurve = function (curve) {
    var startPoint = curve.startPoint,
    endPoint = curve.endPoint,
    velocity,newWidth;

    velocity = endPoint.velocityFrom(startPoint);
    velocity = this.velocityFilterWeight * velocity +
    (1 - this.velocityFilterWeight) * this._lastVelocity;

    newWidth = this._strokeWidth(velocity);
    this._drawCurve(curve, this._lastWidth, newWidth);

    this._lastVelocity = velocity;
    this._lastWidth = newWidth;
  };

  SignaturePad.prototype._drawPoint = function (x, y, size) {
    var ctx = this._ctx;

    ctx.moveTo(x, y);
    ctx.arc(x, y, size, 0, 2 * Math.PI, false);
    this._isEmpty = false;
  };

  SignaturePad.prototype._drawMark = function (x, y, size) {
    var ctx = this._ctx;

    ctx.save();
    ctx.moveTo(x, y);
    ctx.arc(x, y, size, 0, 2 * Math.PI, false);
    ctx.fillStyle = 'rgba(255, 0, 0, 0.2)';
    ctx.fill();
    ctx.restore();
  };

  SignaturePad.prototype._drawCurve = function (curve, startWidth, endWidth) {
    var ctx = this._ctx,
    widthDelta = endWidth - startWidth,
    drawSteps,width,i,t,tt,ttt,u,uu,uuu,x,y;

    drawSteps = Math.floor(curve.length());
    ctx.beginPath();
    for (i = 0; i < drawSteps; i++) {if (window.CP.shouldStopExecution(0)) break;
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
    }window.CP.exitedLoop(0);
    ctx.closePath();
    ctx.fill();
  };

  SignaturePad.prototype._strokeWidth = function (velocity) {
    return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
  };

  var Point = function (x, y, time) {
    this.x = x;
    this.y = y;
    this.time = time || new Date().getTime();
  };

  Point.prototype.velocityFrom = function (start) {
    return this.time !== start.time ? this.distanceTo(start) / (this.time - start.time) : 1;
  };

  Point.prototype.distanceTo = function (start) {
    return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
  };

  var Bezier = function (startPoint, control1, control2, endPoint) {
    this.startPoint = startPoint;
    this.control1 = control1;
    this.control2 = control2;
    this.endPoint = endPoint;
  };

  // Returns approximated length.
  Bezier.prototype.length = function () {
    var steps = 10,
    length = 0,
    i,t,cx,cy,px,py,xdiff,ydiff;

    for (i = 0; i <= steps; i++) {if (window.CP.shouldStopExecution(1)) break;
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
    }window.CP.exitedLoop(1);
    return length;
  };

  Bezier.prototype._point = function (t, start, c1, c2, end) {
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
  minPointDistance: 3 });



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

clearButton.addEventListener('click', function (event) {
  signaturePad.clear();
});



var  clearButton2 = document.getElementById('signature-pad2-clear');

clearButton2.addEventListener('click', function(event) {
    signaturePad2.clear();
    //document.getElementById('patient_sign').value = "";
});


 /* END OF SECOND SIGNATURE PAD  */
  </script>







@endsection
