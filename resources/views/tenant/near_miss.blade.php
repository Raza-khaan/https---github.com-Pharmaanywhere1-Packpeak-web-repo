@extends('tenant.layouts.mainlayout')
@section('title') <title>Near Miss</title>
 <style>
  .dropzone {
    min-height: 150px;
    border: 2px dotted rgba(0, 0, 0, 0.3);
    background: white;
    padding: 20px 59px;
}


 .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}


</style>
@endsection

@section('content')
 <!-- Content Wrapper. Contains page content -->



 <div class="content-wrapper">
 <div class="dashborad-header">
 	<div class="pharma-add report-add">
            <a href="{{url('/')}}/near_miss" class="active family">Add Near Miss</a>
            <a href="{{url('/')}}/all_near_miss" class="family">Near Miss Report</a>
         
           
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
                <form role="form" id="add_nearMiss" action="{{url('save_near_miss')}}" method="post" enctype="multipart/form-data">

                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Near Miss </li>
    </ol>
</nav>


</div>
                {{ csrf_field() }}
                <div class="report-forms">
                <div class="col-md-10 m-auto"  >
                    
                <div class="patient-information" style="margin-bottom:30px">
                        <h3>Near Miss</h3> 
                        <div class="row">
                            
                            <div class="form-group checkbox-wrp col-md-8">
                            <label> Error Type </label> </br>
                            <label><input   {{old('missed_tablet')?'checked':''}} type="checkbox" name="missed_tablet" class="minimal @error('no_of_weeks') is-invalid @enderror" value="missed_tablet"  />&nbsp;{{__('Missed tablet')}}</label>&nbsp;
                                <label><input   {{old('extra_tablet')?'checked':''}}  type="checkbox" name="extra_tablet"  class="minimal @error('no_of_weeks') is-invalid @enderror" value="extra_tablet"  />&nbsp;{{__('Extra tablet')}}</label>&nbsp;
                                <label><input   {{old('wrong_tablet')?'checked':''}}  type="checkbox" name="wrong_tablet"  class="minimal @error('no_of_weeks') is-invalid @enderror" value="wrong_tablet"  />&nbsp;{{__('Wrong tablet')}}</label>&nbsp;
                                <label><input   {{old('wrong_day')?'checked':''}}     type="checkbox" name="wrong_day"     class="minimal @error('no_of_weeks') is-invalid @enderror" value="wrong_day"  />&nbsp;{{__('Wrong day')}}</label>&nbsp;
                                 <label>
                                    <input type="checkbox" onchange="getstatus(this)" id="other_checkbox" name="other_checkbox" class="minimal"   />&nbsp;{{__('other')}}
                                </label>
      
                              </div>

                              <div class="col-md-3 form-group other_field">

</div>
                            <div class="form-group col-md-6">
                              <label for="person_involved">{{__('Person involved')}}<span style="color:red">*</span></label>
                              <input type="text" value="{{old('person_involved')}}"  class="form-control @error('person_involved') is-invalid @enderror" maxlength="20"   id="person_involved"  name="person_involved" placeholder="Person Involved." style="text-transform: capitalize;">
                                @error('person_involved')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                            </div>


                               <!-- textarea -->
                              <div class="form-group col-md-6">
                                <label for="initials">{{__('Initials')}}<span style="color:red">*</span></label>

                                <input type="txet" value="{{old('initials')}}" class="form-control @error('initials') is-invalid @enderror" name="initials" id="initials" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  placeholder="Initials." />
                                  @error('initials')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                  @enderror
                              </div>

                              <div class="col-md-3">
                              <button type="submit" class="btn btn-primary">{{__('Add Near Miss')}}</button>
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

function getstatus(status)
{
  
  if(status.checked)
  {
    $('.other_field').html('<label for="other">Other? <span class="text-danger"> *</span></label>\
                                <input type="text" class="form-control" minlength="3" required name="other" id="other"\ placeholder="other" >');
  }
  else
  {
    $('.other_field').html('');
  }

    
            
    
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

      // $('#other_checkbox').on('Changed', function(event){
      //   alert($(this).val());
      //   $(this).on('Checked', function(event){
      //       alert("checked");
      //       $('.other_field').html('<label for="other">Other? <span class="text-danger"> *</span></label>\
      //                           <input type="text" class="form-control" minlength="3" required name="other" id="other"\ placeholder="other" >');
      //   });
      //   $(this).on('Unchecked', function(event){
      //     // alert("Unchecked");
      //     $('.other_field').html('');
      //   });

      // });


    });

    $(document).ready(function(){
      $("#lblmainheading").html("Near Miss");
      $("#main-wrap").css("display", "none");
 $("#initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });

      // $('#add_nearMiss').submit(function(){
      //   var err=0;
      //   let missed_tablet=$('input[type=checkbox][name="missed_tablet"]');
      //   let extra_tablet=$('input[type=checkbox][name="extra_tablet"]');
      //   let wrong_tablet=$('input[type=checkbox][name="wrong_tablet"]');
      //   let wrong_day=$('input[type=checkbox][name="wrong_day"]');

      //   if(missed_tablet.is(':checked')){
      //     missed_tablet.parent('div').removeClass('icheckbox_minimal-red hover');
      //     missed_tablet.parent('div').addClass('icheckbox_minimal-blue');

      //   }else{
      //     ++err;
      //     missed_tablet.parent('div').removeClass('icheckbox_minimal-blue');
      //     missed_tablet.parent('div').addClass('icheckbox_minimal-red hover');
      //   }

      //   if(extra_tablet.is(':checked')>0){
      //     extra_tablet.parent('div').removeClass('icheckbox_minimal-red hover');
      //     extra_tablet.parent('div').addClass('icheckbox_minimal-blue');

      //   }else{
      //     ++err;
      //     extra_tablet.parent('div').removeClass('icheckbox_minimal-blue');
      //     extra_tablet.parent('div').addClass('icheckbox_minimal-red hover');
      //   }

      //   if(wrong_tablet.is(':checked')>0){
      //     wrong_tablet.parent('div').removeClass('icheckbox_minimal-red hover');
      //     wrong_tablet.parent('div').addClass('icheckbox_minimal-blue');

      //   }else{
      //     ++err;
      //     wrong_tablet.parent('div').removeClass('icheckbox_minimal-blue');
      //     wrong_tablet.parent('div').addClass('icheckbox_minimal-red hover');
      //   }

      //   if(wrong_day.is(':checked')>0){
      //     wrong_day.parent('div').removeClass('icheckbox_minimal-red hover');
      //     wrong_day.parent('div').addClass('icheckbox_minimal-blue');

      //   }else{
      //     ++err;
      //     wrong_day.parent('div').removeClass('icheckbox_minimal-blue');
      //     wrong_day.parent('div').addClass('icheckbox_minimal-red hover');
      //   }

      //   if(err>0){
      //     alert(err);
      //     return false;
      //   }else{
      //     return true;
      //   }

      // });

    });

    //restrict Alphabets

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
