    <!-- jQuery 2.1.3 -->
    <!-- <script src="{{ URL::asset('admin/plugins/jQuery/jquery-2.1.3.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ URL::asset('admin/plugins/moment/moment.min.js') }}"></script>
    <!-- <script type="text/javascript" src="{{ URL::asset('admin/plugins/jQuery/jquery.min.js') }}"></script> -->

    <script src="{{ URL::asset('admin/plugins/jQuery/jquery-2.1.3.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/plugins/fullcalendar/new/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('admin/plugins/fullcalendar/scheduler.min.js') }}"></script>

    <!-- jQuery UI 1.11.2 -->
    <script src="{{ URL::asset('admin/plugins/jQueryUI/jquery-ui.js') }}" type="text/javascript"></script>  
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      //$.widget.bridge('uibutton', $.ui.button);
    </script>
   
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>  
    
    <!--  This Use  For Dashboard Graph Only -->

    <!-- Morris.js charts -->
<!--     <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ URL::asset('admin/plugins/morris/morris.min.js') }}" type="text/javascript"></script> -->
    <!-- Sparkline -->
<!--     <script src="{{ URL::asset('admin/plugins/sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script> -->
    <!-- jvectormap -->
<!--     <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript"></script> -->
    <!-- jQuery Knob Chart -->
    <script src="{{ URL::asset('admin/plugins/knob/jquery.knob.js') }}" type="text/javascript"></script>

    <!--  This Use  For Dashboard Graph Only -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI&libraries=places"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<!--AIzaSyCEPuXYPRVak6p0IwdP08Q_8CrfYW-L9SI    AIzaSyD7OIFvK1-udIFDgZwvY7FVTFHMHipNy6Y -->
    <!--   Dropzone Js -->
<!--     <script type="text/javascript" src="{{ URL::asset('admin/plugins/dropzone/js/dropzone.js')}}"></script>
 -->
   <!-- InputMask -->
<!--    <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/input-mask/jquery.inputmask.extensions.js')}}" type="text/javascript"></script> -->

    <script src="{{ URL::asset('admin/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ URL::asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="{{ URL::asset('admin/plugins/slimScroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="{{ URL::asset('admin/plugins/fastclick/fastclick.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('admin/dist/js/app.min.js') }}" type="text/javascript"></script>

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="{{ URL::asset('admin/dist/js/pages/dashboard.js') }}" type="text/javascript"></script> -->
     <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script> -->
<!--      <script src='https://unpkg.com/moment@2.24.0/min/moment.min.js'></script> -->
    <!-- AdminLTE for demo purposes -->
        <script src="{{ URL::asset('admin/plugins/daterangepicker/jquery.comiseo.daterangepicker.js') }}" type="text/javascript"></script>
<!--     <script src="{{ URL::asset('admin/dist/js/demo.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/daterangepicker/jquery.comiseo.daterangepicker.js') }}" type="text/javascript"></script>
    
   
    <script src="{{ URL::asset('admin/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
 -->
    <script src="{{ URL::asset('admin/dist/js/bootstrap-tagsinput.min.js')}}" type="text/javascript"></script>
    

    <!-- DATA TABES SCRIPT -->
<!--     <script src="{{ URL::asset('admin/plugins/datatables/jquery.dataTables.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('admin/plugins/datatables/dataTables.bootstrap.js')}}" type="text/javascript"></script> -->
   
    <!-- daterangepicker -->
    <script src="{{ URL::asset('admin/plugins/timepicker/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <script  type="text/javascript" src="{{ URL::asset('js/select2.min.js')}}"></script>
   
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"> -->



<script>
    // New_password
    const toggleNewPassword = document.querySelector('#toggleNewPassword');
     const new_password = document.querySelector('#new_password');
     if(toggleNewPassword && new_password){
      toggleNewPassword.addEventListener('click', function (e) {
          // toggle the type attribute
          const type = new_password.getAttribute('type') === 'password' ? 'text' : 'password';
          new_password.setAttribute('type', type);
          // toggle the eye slash icon
          this.classList.toggle('fa-eye-slash');
      });
     }

     // Confirm_password
     const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
     const confirm_password = document.querySelector('#confirm_password');
     if(toggleConfirmPassword && confirm_password){
        toggleConfirmPassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
            confirm_password.setAttribute('type', type);
            // toggle the eye slash icon
            this.classList.toggle('fa-eye-slash');
        });
     }
    </script>