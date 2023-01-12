@extends('admin.layouts.mainlayout')
@section('title') <title>Edit Audits</title>

@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
   <div class="dashborad-header">
   <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
   <h2> Update Audits<small> Preview</small></h2>
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
              
                <form role="form" action="{{url('admin/update_audit/'.$audit[0]->website_id.'/'.$audit[0]->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="reports-breadcrum m-0">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Forms</li>
                    <li class="breadcrumb-item active" aria-current="page">General Elements</li>
                </ol>
            </nav>

        </div>
        <!-- Main content -->
                  <div class="row">
                    <div class="col-md-6 m-auto">
                      <div class="update-information">
                        <div class="notes">
                            <div class="form-group">
                              <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
                                 @if(count($all_pharmacies)  && isset($all_pharmacies))
                                    @foreach($all_pharmacies as $row)
                                      @if($row->website_id==$audit[0]->website_id)
                                      <input type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
                                      @endif
                                    @endforeach
                                  @endif
                              <span class="alert_company"></span>
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                              <label for="patient_name">Patient Name <span class="text-danger"> *</span></label>
                              <select name="patient_name" id="patient_name" class="form-control">
                                 <option value="">-- Select Patient--</option>
                                 @foreach($all_patients as $row)
                                   <option value="{{$row->id}}" @if($row->id==$audit[0]->patient_id) selected @endif
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
                                  <label for="no_of_weeks">Number of weeks <span class="text-danger"> *</span></label>
                                  <input type="text" class="form-control" value="{{$audit[0]->no_of_weeks}}" maxlength="3" onkeypress="return restrictAlphabets(event);" id="no_of_weeks"   name="no_of_weeks" placeholder="no of weeks">
                                </div>
                  </div>
                  </div>

                  <div class="row">
            <div class="col-md-6">
            <div class="form-group">
                                <label for="store">Store </label>
                                <select name="store" id="store" class="form-control">
                                @if(isset($all_facilities))
                                @foreach($all_facilities as $row)
                                <option value="{{$row->id}}" @if($audit[0]->store==$row->id) selected @endif >{{$row->name}}</option>
                                @endforeach @endif
                                </select>
                            </div>
</div>
<div class="col-md-6">
  
<div class="form-group">
                                  <label for="staff_initials">Staff initials </label>
                                  <input type="text" name="staff_initials" value="{{$audit[0]->staff_initials}}" id="staff_initials" class="form-control" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  placeholder="Staff initials">
                              </div>
</div>
                </div>

                           
                            <div class="form-group otherinput">
                              @if($audit[0]->store=='5')
                              <input type="text" name="other_store" value="{{$audit[0]->store_others_desc}}" required id="other_store" class="form-control"  placeholder="other store">
                              @endif
                            </div>
                         




                                <div class="row">
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
               


</div><!-- /.content -->
</div><!-- /.content-wrapper -->

@endsection


@section('customjs')


    <script type="text/javascript">


      $(function () {
         $("#staff_initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });
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

        $('#patient_name').select2(

        ).on('change', function (e) {
          if(this.value){
                var ob=$(this).children('option:selected');
                var last_last_AuditStore=ob.attr('data-last_AuditStore');
                  var last_AuditStoreOther=ob.attr('data-last_AuditStoreOther');
                  if(last_last_AuditStore!=""){
                    $('#store').val(last_last_AuditStore);
                  }else{
                    $('#store').val(1);
                  }
                  if(last_AuditStoreOther!=""){
                    $('.otherinput').html('<input type="text" required name="other_store" id="other_store" value="'+last_AuditStoreOther+'" class="form-control"  placeholder="other store">');
                  }
                  else{
                    $('.otherinput').html('');
                  }

            }
          });





      });

    $(document).ready(function(){});

    $('#patient_name').click(function(){
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
              $('.otherinput').html('<input type="text" name="other_store" required id="other_store" class="form-control"  placeholder="other store">');
            }
           else
           {
            $('.otherinput').html('');
           }
        });

      //     restrict Alphabets
      function restrictAlphabets(e){
      var x=e.which||e.keycode;
      if((x>=48 && x<=57) )
      return true;
      else
      return false;
     }

</script>
@endsection
