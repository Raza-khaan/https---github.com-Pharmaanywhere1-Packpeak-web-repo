

<button style="display:none" id="second"  type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Open Modal</button>
<input value="{{Session::get('phrmacy')->email}}" id="txtpharmacyemail" style="display:none"/>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        <h4 class="modal-title">Email verification</h4>
      </div>
      <div class="modal-body">
        <p>Please check your email, account verification link send to  email.</p>
        
      </div>
      <div class="modal-footer">
        <p style="color:green;display:none" id="lblsendemail">verification link send to email</p>
      <a  style="color:white" href="javascript:window.location.reload(true)" type="button" class="btn btn-secondary">Refresh</a>  
      <a onclick="resendemail()"   href="{{url('sendverificationemail/'.Session::get('phrmacy')->email)}}" 
      style="color:white" type="button" class="btn btn-primary">Resent Link</a>
      </div>
    </div>

  </div>
</div>

<section id="main-container">
    <div class="container-fluid">
        <div class="row">
            <div class="side-menu">
                <div class="logo-head">
                    <a class="logo" href="{{url('dashboard')}}">
                        <img width="200px"
                            src="{{ URL::asset('admin/images/logo.png')}}" alt=""></a>
                    <a class="small-logo" href="{{url('admin/dashboard')}}"><img
                            src="{{ URL::asset('admin/images/logo-small.png')}}" alt=""></a>
                    <a class="menu-arrow" href="javascript:void(0);" id="close-btn"><img class="menu-arrow-left"
                            src="{{ URL::asset('admin/images/arrow.png')}}" alt=""><img class="menu-arrow-right"
                            src="{{ URL::asset('admin/images/arrow-right.png')}}" alt=""></a>
                </div>

                <div class="accordion">
                    <a id="menu-bar-close" href="#"><i class="fa fa-times"></i></a>
                    <ul>
                        <li class="sidenav">
                            <a class="nav-link active" href="{{url('dashboard')}}"><img
                                    src="{{ URL::asset('admin/images/dashboard.png')}}"
                                    alt=""><span>Dashboard</span></a>
                        </li>

                        @php
                            $Subscription=App\Models\Tenant\AccessLevel::find(1);
                            $differ_day=3;
                            $admin_parmacy=App\Models\Tenant\Company::where('id','1')->first();
                            $current_date=\Carbon\Carbon::now()->format('Y-m-d');
                            $start_date = \Carbon\Carbon::createFromFormat('Y-m-d',$current_date);

                            $end_date=\Carbon\Carbon::createFromFormat('Y-m-d',$admin_parmacy->expired_date)->format('Y-m-d');
                            $different_days = $start_date->diffInDays($end_date);

                        @endphp

                        @if( $current_date < $admin_parmacy->expired_date ||
                            ( $admin_parmacy->expired_date < $start_date && $different_days <=($differ_day-1) ) )
                                @if(Session::has('phrmacy') && Session::get('phrmacy')->roll_type=='admin')
                                <li class="sidenav">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/users.png')}}"
                                                alt=""><span>Users</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display: none;">
                                        <li class="page_item"><a class="nav-link" href="{{url('/')}}/technician"><i
                                                    class="fa fa-user-md"></i> &nbsp;&nbsp;&nbsp; Add User</a></li>

                                        <li class="page_item"><a class="nav-link" href="{{url('/')}}/all_technician"><i
                                                    class="fa fa-list"></i> &nbsp;&nbsp;&nbsp; All Users</a></li>

                                        <li class="page_item"><a class="nav-link"
                                                href="{{url('/')}}/all_suspended_technician"><i
                                                    class="fa fa-list-alt"></i> &nbsp;&nbsp;&nbsp; Suspended Users</a></li>
                                    </ul>
                                </li>
                                @endif
                                
                                @if($Subscription->form6 || $Subscription->form7 )
                                <li class="sidenav ">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/disabled.png')}}"
                                                alt=""><span>Patients</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display: none;">
                                        @if($Subscription->form6)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/patients">
                                        <img src="{{ URL::asset('admin/images/disabled.png')}}"
                                                alt="">    Add New Patient</a></li>
                                        @endif @if($Subscription->form7)
                                        <li class="page-item"><a class="nav-link"
                                                href="{{url('/')}}/new_patients_report">
                                                <img src="{{ URL::asset('admin/images/list.png')}}"  alt="">
                                                New Patient Report</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form1 || $Subscription->form2 || $Subscription->form3 ||
                                $Subscription->form5)

                                <li class="sidenav">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/ambulance.png')}}"
                                                alt=""><span>Pick Ups</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display: none;">
                                        @if($Subscription->form1)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/pickups">
                                        <img src="{{ URL::asset('admin/images/ambulance.png')}}"  alt="">   
                                        Add Pick Ups</a></li>
                                        @endif @if($Subscription->form2)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/pickups_reports">
                                        <img src="{{ URL::asset('admin/images/list.png')}}"  alt=""> Pickups Report</a></li>
                                        @endif @if($Subscription->form5)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/all_compliance"><img src="{{ URL::asset('admin/images/list.png')}}"  alt=""> All Compliance Reports</a></li>
                                        @endif @if($Subscription->form4)
                                        <!-- <li class=""><a href="{{url('/')}}/six_month_compliance"><i class="fa fa-circle-o"></i>6 Monthly Index Reports</a></li>                -->
                                        @endif
                                        @if($Subscription->form3 && session()->get('phrmacy')->roll_type=='admin')
                                        <li class="page-item"><a class="nav-link"
                                        href="{{url('/pickups_calender')}}">

                                                <!-- href="{{url('/')}}/pickups_calender"> -->
                                                
                                                <img src="{{ URL::asset('admin/images/dashboard.png')}}"  alt="">
                                           
                                                Pick
                                                Ups Calender</a></li>
                                        @endif

                                        
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form9 || $Subscription->form10 )
                                <li class="sidenav">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/magic-wand.png')}}"
                                                alt=""><span>Checkings</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display: none;">
                                        @if($Subscription->form9)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/checkings">
                                        <img src="{{ URL::asset('admin/images/magic-wand.png')}}"
                                                alt="">
                                        
                                        Add Checking</a></li>
                                        @endif @if($Subscription->form10)
                                        <li class="page-item"><a class="nav-link"
                                                href="{{url('/')}}/checkings_report">
                                                <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">
                                                Checking Report</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif


                                @if($Subscription->form9 || $Subscription->form10 )
                                <li class="sidenav">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/magic-wand.png')}}"
                                                alt=""><span>Packed</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display: none;">
                                        @if($Subscription->form9)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/packed">
                                        <img src="{{ URL::asset('admin/images/magic-wand.png')}}"
                                                alt="">
                                        
                                        Add Packed</a></li>
                                        @endif @if($Subscription->form10)
                                        <li class="page-item"><a class="nav-link"
                                                href="{{url('/')}}/packed_report">
                                                <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">
                                                Packed Report</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form15 || $Subscription->form16 )
                                <li class="sidenav ">
                                <h4><a href="#"><img src="{{ URL::asset('admin/images/reply.png')}}"
                                                alt=""><span>Returns</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display:none;">
                                        @if($Subscription->form15)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/returns">
                                            
                                        <img src="{{ URL::asset('admin/images/reply.png')}}"
                                                alt="">
                                        Add
                                                Return</a></li>
                                        @endif @if($Subscription->form16)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/all_returns">
                                        <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">    
                                        All Returns</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form17 || $Subscription->form18 )
                                <li class="sidenav">
                                <h4><a href="#"><img src="{{ URL::asset('admin/images/editing.png')}}"
                                                alt=""><span>Audits</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display:none;">
                                        @if($Subscription->form17)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/audits">
                                        <img src="{{ URL::asset('admin/images/editing.png')}}"
                                                alt="">
                                            Add
                                                Audit</a></li>
                                        @endif @if($Subscription->form18)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/all_audits">
                                            
                                        <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">
                                        All
                                                Audit</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form19 || $Subscription->form20 || $Subscription->form21 )
                                <li class="sidenav">
                                <h4><a href="#"><img src="{{ URL::asset('admin/images/price-tag.png')}}"
                                                alt=""><span>Notes for Patients</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display:none;">
                                        @if($Subscription->form19)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/notes_for_patients">
                                        <img src="{{ URL::asset('admin/images/price-tag.png')}}"
                                                alt="">    
                                        Add Notes For Patients</a></li>
                                        @endif @if($Subscription->form20)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/notes_for_patients_report">
                                        <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">    
                                        Notes Patients Report</a></li>
                                        @endif @if($Subscription->form21)
                                        <li class=""><a href="{{url('/')}}/sms_tracking_report">
                                        <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">    
                                            Sms Tracking Report</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif

                                @if($Subscription->form11 || $Subscription->form12 || $Subscription->form13 ||
                                $Subscription->form14)
                                <li class="sidenav">
                                    <h4><a href="#"><img src="{{ URL::asset('admin/images/price-tag.png')}}"
                                                alt=""><span>Near Miss</span></a><i class="fa fa-angle-right"></i></h4>
                                    <ul style="display:none;">
                                        @if($Subscription->form11)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/near_miss">
                                        <img src="{{ URL::asset('admin/images/price-tag.png')}}"
                                                alt="">    
                                        
                                        Near
                                                Miss</a></li>
                                        @endif @if($Subscription->form12)
                                        <li class="page-item"><a class="nav-link" href="{{url('/')}}/all_near_miss">
                                            
                                        <img src="{{ URL::asset('admin/images/list.png')}}"
                                                alt="">
                                        All Near Misses</a></li>
                                        @endif @if($Subscription->form13)
                                        <!-- <li class=""><a href="{{url('/')}}/nm_last_month"><i class="fa fa-circle-o"></i>Last Month Report</a></li> -->
                                        @endif @if($Subscription->form14)
                                        <!-- <li class=""><a href="{{url('/')}}/nm_monthly"><i class="fa fa-circle-o"></i>NM Montholy Report V2</a></li> -->
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                @endif

                                @if(session()->get('phrmacy')->roll_type=='admin')
                                
                                <li class="sidenav">
                                    <a class="nav-link active" href="{{url('exempted_patients')}}"><img
                                    src="{{ URL::asset('admin/images/disabled.png')}}"
                                    alt=""><span>Exempted Patients</span></a>
                                </li>
                                <li class="sidenav">
                                    <a class="nav-link active" href="{{url('Sms_settings')}}"><img
                                    src="{{ URL::asset('admin/images/dashboard.png')}}"
                                    alt=""><span>General Settings</span></a>
                                </li>

                                <li class="sidenav">
                                    <a class="nav-link active" href="{{url('/pickups_notifications')}}"><img
                                    src="{{ URL::asset('admin/images/cog.png')}}"
                                    alt=""><span>Settings</span></a>
                                </li>

                           

                                <li class="sidenav">
                                    <a class="nav-link active" href="{{url('search')}}"><img
                                    src="{{ URL::asset('admin/images/Search.png')}}"
                                    alt=""><span>Search</span></a>
                                </li>
                                @endif

                                <li class="sidenav">
                                <a href="{{url('logout')}}" class="nav-link"><img
                            src="{{ URL::asset('admin/images/reply.png')}}" alt=""><span>Logout</span></a>
                                </li>
                                
                    </ul>
                </div>
                
            </div>

        <div class="dashboard-side" style="padding-left:0px !important">

        <p id="lblaccountverification"  class="d-block px-3 py-2 text-center text-bold text-white old-bv" style="display:none !Important;background:#001833;color:white;">Please verify your email, Didn't receive email? <a   href="{{url('sendverificationemail/'.Session::get('phrmacy')->email)}}" > Resend Link</a>  </p>
        
        <div class="dashboard-wrap" id="main-wrap">
        <div class="dashborad-header">
            <!-- <a id="menu-bar" href="{{url('create_plan')}}"><i class="fa fa-bars"></i></a> -->
            <h2 style="padding-left:15px" id="lblmainheading"></h2>

            <!-- <a class="btn btn-primary" href="create_plan"> Paypal</a> -->
            <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
            <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button style="background-color:transparent" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                    
                      <a class="dropdown-item" href="{{url('logout')}}">Logout</a>
                    </div>
                  </div>
                  
                </div>
            </div>
          </div>
          </div>