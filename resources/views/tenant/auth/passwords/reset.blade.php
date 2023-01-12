
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>PeakPack || Parmacy || Reset  Password</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Favicon  -->
    <link rel="icon" href="{{ URL::asset('media/logos/favicon.ico') }}" type="image/x-icon"/>
    <!-- Bootstrap 3.3.2 -->
    <link href="{{ URL::asset('admin/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <!-- <link href="{{ URL::asset('admin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
 -->   
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ URL::asset('admin/dist/css/AdminLTE.min.css') }} " rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ URL::asset('admin/plugins/iCheck/square/blue.css') }} " rel="stylesheet" type="text/css" />
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
      <!-- <div class="login-logo">
        <a href="{{url('admin-login')}}"><img src="{{ $getDetails->logo ? asset('storage/'.$getDetails->logo) : asset('media/logos/logo.png') }}" style="height:130px;  width:100%; "></a>
      </div> -->
      <div class="login-box-body">
        <p class="login-box-msg"><b>{{__('Reset Password')}}</b> {{ $getDetails->company_name }}</p>
        <p>{{ $getDetails->account }}.{{env('PROJECT_HOST', 'packpeak.com.au') }}</p>
        <form action="{{ url('passwords/reset/'.$getDetails->row_id) }}" method="post"><!--dashboard-->
            {{ csrf_field() }}
          <div class="form-group has-feedback">
            <label for="email">Email</label>
            <input type="text" readonly value="{{$getDetails->email}}" class="form-control @error('email') is-invalid @enderror  " placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
          <!-- <div class="form-group has-feedback">
            <label for="email">Password</label>
            <input type="password"  name="password" class="form-control @error('password') is-invalid @enderror  " placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @error('password')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div> -->

          <div class="form-group">
                                <label for="password">{{__('Password')}}<span style="color:red">*</span></label>
                                <div class="input-group">
                                    <input type="password"  id="password" name="password" class="form-control @error('password') is-invalid @enderror  " placeholder="Password"/>
                                    <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="togglePassword"></i>
                                    </div>
                                    @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                <label for="password">{{__('Password')}}<span style="color:red">*</span></label>
                                <div class="input-group">
                                    <input type="password"  id="confirm_password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror  " placeholder="Confirm Password"/>
                                    <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="toggleconfirmPassword"></i>
                                    </div>
                                    @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                    @enderror
                                    </div>
                                </div>
          <!-- <div class="form-group has-feedback">
            <label for="email">Confirm Password</label>
            <input type="password"  name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror  " placeholder="Confirm Password"/>
            <div class="input-group-addon" style="background-color:#F5F6FA;padding-top:6px">
                                     <i class="fa fa-eye" id="togglePassword"></i>
                                    </div>
            @error('password_confirmation')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div> -->
          
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Reset Password</button>
            </div><!-- /.col -->
          
        </form>

      <!--  <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div>-->
        <!-- /.social-auth-links -->
       <br/>
        <a href="{{url('admin-login')}}">login as Pharmacy or Technician</a><br>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4 padding-left-10" >
        <a href="{{url('pharmacist_login')}}" class="text-center btn btn-xs btn-primary">Pharmacy login</a> &nbsp; &nbsp;
        <a href="{{url('pharmacist_signup')}}" class="text-center btn btn-xs btn-primary">Add New Pharmacy </a>&nbsp; &nbsp;
        <a href="{{url('/')}}" class="text-center btn btn-xs btn-primary">Supper Admin </a>&nbsp; &nbsp;
      </div>
    </div> -->
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


      // old password
     const togglePassword = document.querySelector('#togglePassword');
     const password = document.querySelector('#password');
     if(togglePassword && password){
      
        togglePassword.addEventListener('click', function (e) {
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye slash icon
        this.classList.toggle('fa-eye-slash');
      });
     }
     

  // Confirm_password
  const toggleConfirmPassword = document.querySelector('#toggleconfirmPassword');
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
  </body>
</html>