
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PeakPack</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- favicon -->
    <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <!-- <link href="{{ URL::asset('admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" /> -->
    <link href="{{ URL::asset('admin/bootstrap/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" /> -->
    <!-- Theme style -->
    <!-- <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" /> -->
    <!-- iCheck -->
    <!-- <link href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" /> -->


    <link href="{{ URL::asset('admin/bootstrap/css/style.css')}}" rel="stylesheet" />

    <style>
     body {
        /* background-image: url("{{ URL::asset('admin/tourpdf/images/backbeauty.jpg') }}"); */
        background:lightgray;
        background-repeat: no-repeat, repeat;
        background-position: center; 
        background-size: cover;  
      }
      .padding-left-10{
        padding-left:70px; 
      }

      .invalid-feedback {
          width: 100%;
          margin-top: 0.25rem;
          font-size: 80%;
          color: #e3342f;
      }

      control:invalid, .form-control.is-invalid {
          border-color: red;
          padding-right: calc(1.6em + 0.75rem);
          background-repeat: no-repeat;
          background-position: right calc(0.4em + 0.1875rem) center;
          background-size: calc(0.8em + 0.375rem) calc(0.8em + 0.375rem);
      }
      .login-box-msg{
        top: 378px;
left: 849px;
height: 34px;
font: var(--unnamed-font-style-normal) normal var(--unnamed-font-weight-normal) 28px/42px var(--unnamed-font-family-product-sans-medium);
letter-spacing: var(--unnamed-character-spacing-0);
color: var(--unnamed-color-001833);
text-align: left;
font: normal normal normal 28px/42px Product Sans Medium;
letter-spacing: 0px;
color: #001833;
opacity: 1;
      }
      .login-box-body{
        top: 308px;
left: 585px;
width: 750px;
height: 465px;
background: #FFFFFF 0% 0% no-repeat padding-box;
box-shadow: 0px 3px 20px #0000000F;
border-radius: 10px;
opacity: 1;
      }

      .login-box{
        top: 0px;
left: 0px;
width: 1920px;
height: 1080px;
background: var(--unnamed-color-f5f6fa) 0% 0% no-repeat padding-box;
background: #F5F6FA 0% 0% no-repeat padding-box;
opacity: 1;
      }
    </style>
 
  </head>
  <body >
  <div class="container-fluid" style="margin:11% auto !Important">

      <!-- <div class="login-box-body"> -->
        
      
        <div class="row">
        <div class="col-md-4"></div>
        <div class="update-information col-md-4">
        
        <div class="notes">

        @if(Session::has('msg'))
          {!!  Session::get("msg") !!}
        @endif

        <div class="text-center mb-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
          
          <p class="login-box-msg text-center"><b>Forgot Password?</b></p>
          <span>We will send you a link on your email to reset your password</span> 
        </div>
        <!-- <form action="{{ url('admin/passwords/email') }}" method="post"> -->

        <!-- <form action="{{ url('admin/passwords/changepassword') }}" method="get"> -->
        <form action="{{ url('admin/passwords/email') }}" method="post">
          
        <!--dashboard-->
            {{ csrf_field() }}
            
        <div class="row" id="step1">
          <div class="col-md-2"></div>
          <div class="form-group col-md-8">
            <input type="text"  name="email" class="form-control @error('email') is-invalid @enderror  " placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror

          </div>
          <div class="col-md-2"></div>
          <div class="col-md-2"></div>

            <div class="form-group col-md-8">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
            </div><!-- /.col -->
        </div>
        <div class="row">
        <div class="col-md-2"></div>
          <div class="form-group col-md-8 text-center">
          <i class="fa fa-arrow-left"></i><a class="ml-2" style="color:black;" href="{{url('admin-login')}}">Back to Login </a><br>
          </div>
        </div>
    </div>
        </div>
          
        </form>
      <!--  <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div>-->
        <!-- /.social-auth-links -->
        
    </div>
      <!-- </div> /.login-box-body -->
    </div><!-- /.login-box -->

    
    <!-- jQuery 2.1.3 -->
    <script src="{{ URL::asset('admin/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ URL::asset('admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ URL::asset('admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>