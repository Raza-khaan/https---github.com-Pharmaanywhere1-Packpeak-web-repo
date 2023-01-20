
@extends('tenant.layouts.mainlayout')
@section('title') <title>Returns</title>

@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->

 
 <div class="content-wrapper">
   
 <div class="dashborad-header">
 	<div class="pharma-add report-add">
            <a href="{{url('/')}}/returns" class="active family">Add Return</a>
            <a href="{{url('/')}}/all_returns" class="family">Return Report</a>
         
           
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
                <form id="addreturn" role="form" action="{{url('save_return')}}" method="post" enctype="multipart/form-data">

                  
                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Return</li>
    </ol>
</nav>


</div>
                {{ csrf_field() }}
                <div class="report-forms">
                        <div class="col-md-6 m-auto " style="margin-bottom:30px">

                        <div class="patient-information" style="margin-bottom:30px">
                        <h3>Return Information</h3> 
                        <div class="row">
                            <div class="form-group col-md-6">
                              <label style="font-size:14px" for="no_of_returned_day_weeks">{{__('Number of Days or Weeks returned')}} <span style="color:red"> *</span></label>
                              <input type="text" value="{{old('no_of_returned_day_weeks')}}" class="form-control"  onkeypress="return restrictAlphabets(event);" id="no_of_returned_day_weeks"   name="no_of_returned_day_weeks" placeholder="no of returned day weeks">
                            <small id="lblsdayserror" style="color:red;display:none"></small>
                            </div>

                              <div class="form-group col-md-6">
                              <label for="select_days_weeks">{{__('Select Days or Weeks')}} <span style="color:red"> *</span> </label>
                              <select name="select_days_weeks" id="select_days_weeks" class="form-control">
                                 <option @if(old('select_days_weeks')=='days') selected @endif value="days">{{__('Days')}}</option>
                                 <option @if(old('select_days_weeks')=='weeks') selected @endif value="weeks">{{__('Weeks')}}</option>
                              </select>
                            </div>
                            <div class="form-group col-md-6">
                               <label for="initials">{{__('Staff initials')}} </label>
                               <input type="text" value="{{old('initials')}}" name="initials" id="initials" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"    placeholder="initials">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="name">{{__('Patient Name')}}<span style="color:red">*</span></label>
                              <select name="patient_id" id="patient"  class="form-control" >
                                <option value="">{{__('Select Patient')}}</option>
                                @foreach($patients as $patient)
                                  <option value="{{$patient->id}}"  data-dob="{{$patient->dob}}" @if(old('patient_id')==$patient->id) selected @endif
                                  data-dob="{{$patient->dob}}"
                                  data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"
                                  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"

                                    data-last_returnStore="{{isset($patient->latestReturn->store)?$patient->latestReturn->store:''}}"
                                    data-last_returnStoreOther="{{isset($patient->latestReturn->other_store)?$patient->latestReturn->other_store:''}}"

                                    data-last_AuditStore="{{isset($patient->latestAudit->store)?$patient->latestAudit->store:''}}"
                                    data-last_AuditStoreOther="{{isset($patient->latestAudit->store_others_desc)?$patient->latestAudit->store_others_desc:''}}"
                                  value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}}( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} )</option>
                                @endforeach
                              </select>
                            </div>


                            
                            <div class="form-group col-md-6">
                              <label for="dob">{{__('Date Of Birth')}} </label>
                              <input type="text" readonly class="form-control" value="{{old('dob')}}"  name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('d/m/Y')}}" >
                            </div>
                            <div class="form-group col-md-6">
                            <label for="store">{{__('Store')}} <span style="color:red"> *</span></label>
                            <select onchange="getstatus(this)" name="store" id="store" class="form-control">
                              <!-- <option value="">{{__('Select')}}</option> -->
                              @forelse($facilities as $facility)
                                <option value="{{$facility->id}}" @if(old('store')==$facility->id) selected @endif >{{$facility->name}}</option>
                                @empty
                                <option value="">{{__('No Records')}}</option>
                              @endforelse

                            </select>
                            <div class="otherinput" style="margin-top:5px">
                            </div>
                            </div>
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
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

$('#addreturn').submit(function(){
  var select_days_weeks = $("#select_days_weeks").val();
    
    var no_of_returned_day_weeks = $("#no_of_returned_day_weeks").val();
    
    if(select_days_weeks=="days")
    {
        if (no_of_returned_day_weeks  < 1 || no_of_returned_day_weeks > 365) 
            {
                $("#lblsdayserror").fadeIn();
                $("#lblsdayserror").html("please enter (1-365) in the number of days or weeks returned field");
                $("#no_of_returned_day_weeks").focus();
                return false;
            }
    }
    else if(select_days_weeks=="weeks")
    {
        if (no_of_returned_day_weeks  < 1 || no_of_returned_day_weeks > 52) 
            {
                $("#lblsdayserror").fadeIn();
                $("#lblsdayserror").html("please enter (1-52) in the number of days or weeks returned field");
                $("#no_of_returned_day_weeks").focus();
                return false;
            }
    }
  

});



$('#patient').on('change', function (e) {

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

      $(function () {
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



      });


    $(document).ready(function(){

      $("#main-wrap").css("display", "none");
      $("#lblmainheading").html("Return");
      $("#patient").select2();
      $("#initials").on("keyup", function(e){
        $(this).val(($(this).val()).toUpperCase());

      })
    

    });

    function getstatus(status)
    {

      
      if(status.value == 5)
    
    {
      
        $('.otherinput').html('<input type="text" name="other_store" id="other_store" class="form-control"  placeholder="other store">');
       
      }
        else
        {
        $('.otherinput').html('');
        }
      }


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


  </script>
@endsection


