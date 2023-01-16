@extends('admin.layouts.mainlayout')
    @section('title') <title>dashboard </title> 
 
    @endsection
    @section('content')
    <!-- Header Wrapper. Contains Header content -->
    <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <div class="pharma-add">
              <a href="#">Subscription Settings</a>
              <a href="{{url('admin/customize_cycle_dates')}}">Admin Settings</a>
            </div>
            <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
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


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <div class="pharma-add pharma-add-mobile">
          <a href="#">Subscription Settings</a>
          <a href="#">Admin Settings</a>
      </div>

      <nav class="dash-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Settings</a></li>
          <li class="breadcrumb-item active" aria-current="page">Subscription Settings</li>
        </ol>
      </nav>
        <!-- Content Header (Page header) -->
      <div class="all-subscriptions">
        <h2>All Subscription</h2>
        @if(isset($subscription) && count($subscription))
             <!-- Main content -->
        <div style="margin-top:50px !important;"></div> 
        
            <!-- <div class="col-md-12 col-lg-12"> -->
              <!-- general form elements -->
                <form role="form" action="{{url('admin/update_subscription/'.$subscription[0]->id)}}" method="post" enctype="multipart/form-data" class="form-inline">
                {{ csrf_field() }}
                <div class="row">
                            <div class="form-group ml-2 col-md-2">
                              <label for="subcription">Subcription Type: </label>
                              <input type="text" class="form-control ml-1" maxlength="20" value="{{$subscription[0]->title}}" required id="title" name="title" placeholder="Subscription..">
                            </div>
                            <div class="form-group ml-2 col-md-2">
                              <label for="plan_validity">Plan Validity: </label>
                              <input type="text" class="form-control ml-1" maxlength="20" value="{{$subscription[0]->plan_validity}}" required id="plan_validity" name="plan_validity" placeholder="plan validity..">
                            </div>
                            <div class="form-group ml-2 col-md-2">
                              <label for="allowed_sms">Allowed Sms: </label>
                              <input type="text" class="form-control ml-1" maxlength="20" value="{{$subscription[0]->allowed_sms}}" required id="allowed_sms" name="allowed_sms" placeholder="Allowed sms per month">
                            </div>
                            <div class="form-group ml-2 col-md-2">
                              <label for="allowed_sms">Amount: </label>
                              <input type="text" class="form-control ml-1" maxlength="20" value="{{$subscription[0]->amount}}" required id="amount" name="amount" placeholder="Allowed sms per month">
                            </div>
                            <div class="form-group ml-2 col-md-2 mt-4 ml-3">
                             </br>
                              <button type="submit" class="btn btn-primary">Update</button> 
                            </div>
                            </div>
                </form>
          <!-- </div> -->
             <!-- /.row -->
        @endif    
        <div style="margin-top:50px !important;"></div>
        <div class="row">
          <div class="col-md-6 col-lg-4">
            <div class="plans">
              <div class="basic">
                <img class="img-fluid" src="{{ URL::asset('admin/images/pharmacy.png')}}" alt="">
              </div>
              <h3>Basic Plan</h3>
              <p>{{$subcriptions[2]['plan_validity']}}  Days Validity</p>
              <a class="edit-plan-btn" href="{{url('admin/edit_subscription/3')}}">Edit Plan</a>
              <a class="form-setting-btn" href="{{url('admin/small/add_form/3')}}">Form Settings</a>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="plans">
              <div class="basic stand">
                <img class="img-fluid" src="{{ URL::asset('admin/images/pharmacy.png')}}" alt="">
              </div>
              <h3>Standard Plan</h3>
              <p>{{$subcriptions[1]['plan_validity']}} Days Validity</p>
              <a class="edit-plan-btn" href="{{url('admin/edit_subscription/2')}}">Edit Plan</a>
              <a class="form-setting-btn" href="{{url('admin/medium/add_form/2')}}">Form Settings</a>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="plans">
              <div class="basic prem">
                <img class="img-fluid" src="{{ URL::asset('admin/images/pharmacy.png')}}" alt="">
              </div>
              <h3>Premium Plan</h3>
              <p>{{$subcriptions[0]['plan_validity']}} Days Validity</p>
              <a class="edit-plan-btn" href="{{url('admin/edit_subscription/1')}}">Edit Plan</a>
              <a class="form-setting-btn" href="{{url('admin/large/add_form/1')}}">Form Settings</a>
            </div>
          </div>
        </div>
      </div>  

    </div>
      
    <!-- /.content-wrapper -->

      @endsection

    @section('customjs')

   

</script>

@endsection

