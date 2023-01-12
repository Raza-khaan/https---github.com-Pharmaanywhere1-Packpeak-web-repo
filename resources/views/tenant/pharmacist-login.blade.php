
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title> PeakPack || Forward to  Pharmacy</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- favicon -->
    <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ URL::asset('admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Theme style -->
    <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" />
    <style>
     .first {
        /* background-image: url("{{ URL::asset('admin/tourpdf/images/backbeauty.jpg') }}"); */
        background:lightgray;
        background-repeat: no-repeat, repeat;
        background-position: center;
        background-size: cover;
      }
      /* .login-box-body{
          padding-top:200px;
      }  */

      .padding-left-10{
        padding-left:70px;
      }

    </style>

  </head>
  <body class="login-page" >
  <div class="login-box">
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
      <div class="login-logo">
      <a href="{{url('/')}}"><b>Pharmacy </b></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">

        <!-- <p class="login-box-msg">PeakPack</p> -->
        <p class="login-box-msg">PeakPack</p>

        <form action="{{url('host-forward')}}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
        <!-- <label>We will setup packnpeaks for you at:</label><br> -->
          www.<input type="text"  name="host_name" id="host_name" autocomplete="host-name" placeholder="company or hostname"/>.{{env('PROJECT_HOST', 'packpeak.com.au') }}
        </div>
          <div class="row">
            <div class="col-xs-8">

            </div><!-- /.col -->
            <div class="col-xs-4">
              
            </div><!-- /.col -->
          </div>
        </form>


        <!-- <a href="#">I forgot my password</a><br> -->
        <!-- <a href="{{url('pharmacist_signup')}}" class="text-center">Register a new membership</a> -->

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    <!-- jQuery 2.1.3 -->
    <script src="{{ URL::asset('admin/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script>
      $(function () {

          $('#company_name').keyup(function(){
               $('#host_name').val($(this).val());
          });
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>