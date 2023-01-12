@extends('tenant.layouts.mainlayout')
@section('title') <title>Notification</title>
@endsection

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Notification 
            <small>New</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('dashboard')}}">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
            @endif
            @if ($errors->any())
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
            
                    @if(!empty($notification) )
                       
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-offset-3 col-sm-6 table-responsive">
                                    <table  class="table table-bordered table-striped">
                                      <tbody>
                                       <tr>
                                          <th class="text-primary text-center">Pharmacy</th>
                                          <th class="text-center">Registration</th>
                                        </tr>
                                        <tr>
                                          <td class="text-center">{{ucfirst(session('phrmacy')['company_name'])}}</td>
                                          <td class="text-center">{{ucfirst(session('phrmacy')['registration_no'])}}</td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" class="text-danger">
                                            !! {{$notification->notification_msg}}
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        
                        </div><!-- /.box-body -->
                   @else
                      <div class="box-header text-center">
                        <h3 class="box-title text-center text-danger"> There  are no Notification  !!!.</h3>   
                      </div>
                   @endif
               
              </div><!-- /.box -->
          </div>   <!-- /.row -->
        </section><!-- /.content -->
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

    });
      
    $(document).ready(function(){
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
