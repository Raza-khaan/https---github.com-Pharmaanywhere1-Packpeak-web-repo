
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PeakPack || Admin || Login</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- favicon -->
    <link rel="icon" href="{{ asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ asset('/admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <!-- <link href="{{ asset('/admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" /> -->

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset('/admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ asset('/admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" />
    <style>
     body {
        /* background-image: url("{{ asset('admin/tourpdf/images/backbeauty.jpg') }}"); */
        background:lightgray;
        background-repeat: no-repeat, repeat;
        background-position: center; 
        background-size: cover;  
      }
      .padding-left-10{
        padding-left:70px; 
      }
    </style>
 
  </head>
  <body >
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
        
        <!-- <a href="{{url('/')}}"><b>Admin</b></a> -->
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <div class="row">
          <div class="col-md-12 text-center">
          <img src="{{ asset('/media/logos/logo.png') }}" style="height:90px;  width:80%; " alt="User Image" />      
        </div>
        </div>
      
      
      <!-- <p class="login-box-msg"><b>PackPeak- Admin</b></p> -->
        <form style="padding:12px;margin-top:15px" action="{{ url('sign_in') }}" method="post" style="margin-top:15px"><!--dashboard-->
        	{{ csrf_field() }}
          <div class="form-group has-feedback">
            <input type="text"  name="email" class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          
            <div class="col-xs-8"> 
            <a  href="{{url('/admin/passwords/reset')}}" style="margin-left: 51%;">I forgot my password</a>     
              <!-- <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>-->
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"  style="margin-top: 1%; margin-bottom: 1%;">Sign In</button>
            </div><!-- /.col -->
            <div class="row">
            <div class="col-md-6 ">
              <a  href="{{url('term_condition')}}" target="blank">Terms & Condition</a>  
              </div>
              <div class="col-md-6 text-right">
              <a  href="{{url('privacy_policy')}}" target="blank">Privacy Policy</a>  
              </div>
             
            </div>
            
            
        </form>

      <!--  <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div>-->
        <!-- /.social-auth-links -->

        

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    
    <!-- jQuery 2.1.3 -->
    <script src="{{ asset('/admin/plugins/jQuery/jQuery-2.1.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset('/admin/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="{{ asset('/admin/plugins/iCheck/icheck.min.js') }}" type="text/javascript"></script>
    <script>



      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });

        
      });

      $(document).ready(function () {
          
        });
    </script>
  </body>
</html>