        @extends('admin.layouts.mainlayout')
        @section('title') <title>Notes_For_Patient</title>

        @endsection
        @section('content')
        <!-- Content Wrapper. Contains page content -->
        <div class="dash-wrap">
        <!-- Content Header (Page header) -->
        <div class="dashborad-header">
			<a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
			<h2>Update Notes For Patients</h2>
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
  
        <form role="form" action="{{url('admin/update_note_for_patient/'.$note_for_patient[0]->website_id.'/'.$note_for_patient[0]->id)}}" method="post" enctype="multipart/form-data">
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

        <div class="row">
        <div class="col-md-6 m-auto">
        <div class="update-information">
			<div class="notes">
			<h3 class="text-center">Update Notes for patient</h3>
			<div class="row">
				<div class="col-md-12">
			<div class="form-group col-md-12">
				<label for="company_name">Company Name  <span class="text-danger"> *</span> &nbsp;&nbsp;&nbsp; </label> <span class="loader_company"></span>
				@if(count($all_pharmacies)  && isset($all_pharmacies))
				
				@foreach($all_pharmacies as $row)
					@if($row->website_id==$note_for_patient[0]->website_id)
					<input type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
					@endif
				@endforeach
				@endif
				<span class="alert_company"></span>
			</div>
			
			<div class="col-md-12">
				<div class="form-group">
					<label for="patient_name">Patient Name <span class="text-danger"> *</span></label>
					<select name="patient_name" id="patient_name" class="form-control">
					<option value="">-- Select Patient--</option>
					@foreach($all_patients as $row)
						<option value="{{$row->id}}" @if($row->id==$note_for_patient[0]->patient_id) selected @endif 
							data-dob="{{$row->dob}}" data-lastPickupDate="{{isset($row->latestPickups->created_at)?$row->latestPickups->created_at:''}}"  data-lastPickupWeek="{{isset($row->latestPickups->created_at)?$row->latestPickups->no_of_weeks:''}}"
						data-lastNoteForPatient="{{isset($row->latestPickups->notes_from_patient)?$row->latestPickups->notes_from_patient:''}}"
						data-lastLocation="{{isset($row->latestPickups->location)?$row->latestPickups->location:''}}"
						>{{$row->first_name.' '.$row->last_name}} ( {{$row->dob?date("j/n",strtotime($row->dob)):""}} ) </option>
					@endforeach
					</select>
				</div>

			</div>

			<div class="col-md-12">
					<div class="form-group">
					<label for="dob">Date Of Birth </label>
					<input type="text" readonly class="form-control" value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $note_for_patient[0]->dob)->format('d/m/Y')}}"  name="dob" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('Y-m-d')}}" >
					</div>
			</div>
			<!-- New  row col-12 for new section  -->
				<div class="col-md-12">
					<!-- textarea -->
					<div class="form-group">
						<label for="note_for_patient">Note For Patient <span class="text-danger"> *</span></label>
						<textarea class="form-control"  style="resize: none;" rows="4" name="note_for_patient" id="note_for_patient"   placeholder="note for patient.">{{$note_for_patient[0]->notes_for_patients}}</textarea>
						</div>
					
					
				</div>
				
					<div class="col-md-12">
						
							<input type="checkbox"   @if($note_for_patient[0]->notes_as_text=='1') checked  @endif name="send_note" class="minimal" value="1"  />&nbsp; Send the note as a text message
					
					</div>	
					<div class="row mt-4">
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn update-btn">Submit</button>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="reset" class="btn reset-btn">Reset</button>
                                    </div>
                                </div>
					
				</div>
				</div>
			</div>  
        </div>
        </div>
        </div>



        </form>
       
       





        </div><!-- /.content-wrapper -->





        @endsection


        @section('customjs')


        <script type="text/javascript">
        var website_id={{$note_for_patient[0]->website_id}}; 

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

        $('#patient_name').select2(
        ).on('change', function (e) {
        if(this.value){
        var ob=$(this).children('option:selected');
        var dob=ob.attr('data-dob');

        var lastLocation=ob.attr('data-lastLocation');
        $('#dob').val(moment(dob).format('DD/MM/YYYY'));
        }
        });




        });





        /* get  Patienst  Dote of  birth by Patient  id and Website id  */
        $('#patient_name').click(function(){
        if($(this).val()){

        $.ajax({
        type: "POST",
        url: "{{url('admin/get_patient_dob')}}",
        data: {website_id:website_id,patient_id:$(this).val(),"_token":"{{ csrf_token() }}"},
        beforeSend: function() {
        $('.loader_patient').html('<i class="fa fa-spinner fa-spin"></i>');
        },
        success: function(result){
        $('.loader_patient').html('');
        if(result.dob)
        {
          $('#dob').val(moment(result.dob).format('DD/MM/YYYY')); 
        }
        }
        });
        } 	
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



        </script>
        @endsection
