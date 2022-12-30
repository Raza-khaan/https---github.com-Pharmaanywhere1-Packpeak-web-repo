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
                      <img src="assets/images/user.png" alt=""> <span>Amir Eid</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <!-- <a class="dropdown-item" href="#">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a> -->
                      <a class="dropdown-item" href="#">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>

        <!-- Main content -->
        <div class="report-forms">
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
              
                <form role="form" action="{{url('admin/search_patient')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                     
                <div class="row">
                <div class="col-md-3">
              <div class="form-group">
            
              <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
              @if(count($all_pharmacies)  && isset($all_pharmacies))
              <select class="form-control" name="company_name" required id="company_name">
              <option value="">--Select Company --</option>
              @foreach($all_pharmacies as $row)
              <option value="{{$row->website_id}}">{{$row->company_name}} - {{$row->name}}</option>
              @endforeach
              </select>
              @endif
              <span class="alert_company"></span>
              </div>

              </div>
             


                              <div class="col-md-3">
                         <div class="form-group">
                                <label for="patient_name">Patient Name <span class="text-danger"> *</span></label>
                                <select name="patient_name" id="patient_name" class="form-control">
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
