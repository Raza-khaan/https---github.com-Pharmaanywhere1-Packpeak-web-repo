@extends('tenant.layouts.mainlayout')
    @section('title') <title>Packboard </title> 

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
        <!-- <link rel="stylesheet" type="text/css" href="{{ URL::asset('packboardassets/css/bootstrap.min.css') }}"  /> -->
       
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
         
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('packboardassets/css/style.css') }}" /> 
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.min.css'></link>
        <link rel="stylesheet" type="text/css"  href="{{ URL::asset('packboardassets/css/responsive.css') }}"  />
         <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />  -->
       
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script>

        
        
               <style>


.box-header .form-group {
    margin-bottom: 0rem !important;
}

.box-body {
   
}

.board-block-wrp
{
    border-radius: 10px;
    background: #fff;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    padding: 40px 40px 20px;
}


::-webkit-scrollbar {
    -webkit-appearance: none;
    width: 2px;
}
::-webkit-scrollbar-thumb {
    border-radius: 1px;
    background-color: rgba(0,0,0,.1);
    -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
}
#company_id
{
  ba
}
#sortable1
{
}
  .family
    {
      font-family:inherit !Important;
    }
.selected {
    /* background:LightGray !important; */
}

          </style>
          

    @endsection
   
    @section('content')
    @include('sweetalert::alert')
    <!-- Header Wrapper. Contains Header content -->
    <div class="dash-wrap" >
          <div class="dashborad-header">
            <!-- <a id="menu-bar" href="{{url('create_plan')}}"><i class="fa fa-bars"></i></a> -->
            <h2>Packs Board</h2>
            <div>
            <li>
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Search" id="tosearch" />

            </div>
            </li>
            </ul>
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
                    
<div class="content-wrapper">

<div class="container">
  <!-- Board BLock -->
  <div class="board-block-wrp">
  <form action="{{url('packboard')}}" method="get">
 
               @csrf
             
              
                <div class="board-header">
                    <div class="row">
                  
                       
                        <div class="col-md-3 col-sm-12">
                        <div class="form-group">
                                <label>
                                    Select Date: <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#alert-modal"><i class="far fa-times"></i> Clear All</a>
                                </label>
                                <input type="text" class="form-control" name="startdate" id="date" value="" />
                            </div>
                            <!-- <div class="form-group">
                                <label>
                                     Start Date: <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#alert-modal"><i class="far fa-times"></i> Clear All</a>
                                </label>
                                <input type="date" class="form-control"  name="startdate"  value="{{ $final }}"/> -->
                                <!-- <input type="date" class="form-control" name="enddate"  value="{{ date(' Y-m-d') }}"/> -->
                            <!-- </div>
                            
                        </div>  
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>
                                     End Date: <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#alert-modal"></a>
                                </label> -->
                                <!-- <input type="date" class="form-control" name="startdate"  value="{{ date(' Y-m-d') }}"/> -->
                                <!-- <input type="date" class="form-control" name="enddate"  value="{{ $currentdate}}"/>
                            </div>
                             -->
                        </div>
                       
                       
                        <!-- <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Exclude patients who did NOT pick up for the last</label>
                                <select class="select2 js-states">
                                    <option>Please Select</option>
                                    <option>1 Week</option>
                                    <option>2 Week</option>
                                    <option>3 Week</option>
                                    <option>1 Month</option>
                                    <option>2 Months</option>
                                    <option>3 Months</option>
                                </select>
                            </div>
                        </div>
                        -->
                       
                        <div class="col-md-2  col-sm-12" style="height: 60px !important;">
                            <div class="dropdown" style="margin-left: 0 !important;">
                                <a href="javascript:void(0)" class="dropdown-toggle"  id="filterby"> Filter By <img src="images/filter.svg" alt="" /> </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{url('/packboard')}}"  id="show">
                                            <input class="form-check-input" type="checkbox" value="" id="1"/>
                                            Show all (All)
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);">
                                           
                                            <input class="form-check-input" name="hold" type="checkbox" value="1"  />
                                            <span class="hold-dot"></span> On Hold
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" id="due">
                                            <input class="form-check-input" type="checkbox" value="" id="3" />
                                            <span class="due-dot"></span> Due
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" id="overdue">
                                            <input class="form-check-input" type="checkbox" value="" id="4" />
                                            <span class="overdue-dot"></span> Overdue
                                        </a>
                                    </li>
                                   
                                </ul>
                               
                            </div>
                            
                        </div>
                        <div class="col-md-2"  style="height: 60px !important; ">
                        
                        <input type="submit" name=""  class="btn btn-success" value="Apply Filter" id="" style=" padding: 7px; !important">
                        </div>
                        <div class="col-md-2"  style="height: 60px !important; ">
                        
                        <!-- <input type="submit" name=""  class="btn btn-success" value="Calender" id="" style=" padding: 7px; !important"> -->
                       <button class="btn btn-success" style=" padding: 7px; !important"> <a href="{{url('full_calender')}}" type="button" style="color : white;">Full calender</a></button>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                               
                                <ul>
                                <li> <b>  Status: </b></li>
                                    <li><span class="default-dot"></span> Default</li>
                                    <li><span class="hold-dot"></span> On Hold</li>
                                    <li><span class="due-dot"></span> Due</li>
                                    <li><span class="overdue-dot"></span> Overdue</li>
                                </ul>
                            </div>
                        </div>
                      
                    </div>
                </div>
                </form>
                <!-- Pack Block -->
                
                <div class="pack-block"  >
                   
                    <div class="row main-row"  > 
                        
                        <div class="col-lg-3 col-md-6 col-sm-12" id="">
                            <div class="pack-box" >
                                <ul>
                                    <li>
@foreach ($getpatientlastpickup as $patientpickup)
    

                                        <div class="box-header " >
                                            <h2>To Pack</h2>
                                            <div class="form-group" style="margin-bottom: 0rem; !important">
                                                <ul>
                                                    <li>
                                                        <input type="text" class="form-control" placeholder="Search" id="searchone" />
                                                        <img src="images/search.svg" alt="" />
                                                    </li>
                                                    <li>
                                                        <a href="#">A-Z <img src="images/filter.svg" alt="" /></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                

                                <div class="box-body droptrue" id="sortable1">
                                     <div class="pack-card brd-color1" id="1">
                                        <div class="card-info" >
                                            
                                            <h3>{{$patientpickup->patients->first_name.' '.$patientpickup->patients->last_name}}</h3>
                                            <ul>
                                                <li><span>Patient</span></li>
                                                <li>
                                                    <a href="javascript:void(0);" class="view_details"> <img src="images/arrow-drop-down.svg" alt="" /> View Details</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-btn">
                                            
                                        </div>

                                        <div class="card-information">
                                            <ul class="information-info">
                                                <li><strong>Patient Name:</strong> <span>{{$patientpickup->patients->first_name.' '.$patientpickup->patients->last_name}}</span></a></li>
                                                <li><strong>Date Of Birth:</strong> <span>{{$patientpickup->dob}}</span></a></li>
                                                <li><strong>Receiver:</strong> <span>Patient</span></a></li>
                                                <li><strong>Notes:</strong> <span>N/A</span></a></li>
                                                <li><strong>Pickup Date:</strong> <span>{{$patientpickup->created_at}}</span></a></li>
                                                <li class="location-txt"><strong>Location:</strong> <span>{{$patientpickup->notes_from_patient}}</span></a></li>
                                            </ul>
                                           
                                            <a href="javascript:void(0);" class="hide-info"><i class="fas fa-caret-up"></i> Hide Details</a>    
                                        </div>
                                       
                                    </div>
                                    @endforeach
                                   
                                  
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="pack-box" id="">
                                <div class="box-header">
                                    <h2>Packed <span>{{ $packed_patient_count->count() }}/{{ $patients->count() }}</span></h2>
                                    <div class="form-group">
                                        <ul>
                                            <li>
                                                <input type="text" class="form-control" placeholder="Search" id="search" />
                                                <img src="images/search.svg" alt="" />
                                            </li>
                                            <li>
                                                <a href="#">A-Z <img src="images/filter.svg" alt="" /></a>
                                            </li>
                                            
                                        </ul>
                                        <div class="add-card">
                                        <a href="javascript:void(0);" onclick="set_value('1');removealert('0')" class="errorhide" data-bs-toggle="modal" data-bs-target="#card-modal"><i class="fal fa-plus" ></i> Add Card</a>
                                    </div>
                                    </div>
                                    
                                </div>
                                <div id="div">
                            <div class="box-body droptrue" id="sortable2">
                                    
                                   @foreach ($packed as $patientdata )
                                   
                                   
                                   @if(isset($patientdata->patients->first_name) && $patientdata->patients->first_name!="")
                                 
                                    <div class="pack-card brd-color2" id="{{$patientdata->patients->id}}">
                                    <div class="checkbox">
                                            <input type="checkbox" id="checkbox{{$patientdata->id}}"><label for="checkbox{{$patientdata->id}}"></label>
                                        </div>
                                  
                                        <div class="card-info"  id="packed_{{$patientdata->id}}"  style="margin-bottom: -6%;">
                                     
                                      <h3>{{$patientdata->patients->first_name.' '.$patientdata->patients->last_name}}</h3>
                                           
                                            <ul>
                                              <li>
                                                <span style="margin-bottom: -9%;">{{date("j/n/Y, h:i A",strtotime($patientdata->created_at))}}</span>
                                            </li>
                                              </ul>
                                              <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="view_details"> <img src="images/arrow-drop-down.svg" alt="" /> View Details</a>
                                                </li>
                                            </ul>
                                        </div>
                                      
                                        <div class="card-btn">
                                            @if ($patientdata->hold == '1')
                                            <a href="{{url('packed_unhold_button',$patientdata->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($patientdata->hold == '0')
                                            <a href="#packed_{{$patientdata->id}}" onclick="savehold({{$patientdata->id}});" id="save_hold"><i class="fas fa-clock"></i>On Hold</a>
                                             <!-- <button class="card-btn"  id="save_hold"> <i class="fas fa-clock"></i>On Hold</button>  -->
                                            @endif
                                        </div>

                                        <div class="card-information">
                                            <ul class="information-info" id="">
                                               
                                            
                                                <li><strong>Patient Name:</strong> <span>{{$patientdata->patients->first_name.' '.$patientdata->patients->last_name}}</span></a></li>
                                                <li><strong>Date Of Birth:</strong> <span>{{$patientdata->patients->dob}}</span></a></li>
                                               
                                                <li><strong>Notes:</strong> <span>N/A</span></a></li>
                                                <li><strong>Pickup Date:</strong> <span>{{date("j/n/Y, h:i A",strtotime($patientdata->created_at))}}</span></a></li>
                                                <li class="location-txt"><strong>Location:</strong> <span>{{$patientdata->note_from_patient}}</span></a></li>
                                            </ul>   
                                            <h4>Options:</h4>
                                            <ul class="btn-box">
                                                <li><a href="#" onclick="editpatient('{{$patientdata->id}}','{{$patientdata->patient_id}}','{{$patientdata->no_of_weeks}}','{{$patientdata->note_from_patient}}');set_value('1');"  data-bs-toggle="modal"  data-bs-target="#card-modal"> <img src="images/edit.svg" alt=""> Edit</a></li>
                                                <li><a href="{{url('packed_Delete',$patientdata->id)}}" class="delete-btn"> <img src="images/delete.svg" alt="">Delete</a></li>
                                                <li>
                                            @if ($patientdata->hold == '1')
                                            <a href="{{url('packed_hold_button',$patientdata->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($patientdata->hold == '0')
                                            <a href="#packed_{{$patientdata->id}}" onclick="savehold({{$patientdata->id}});" id="save_hold"><i class="fas fa-clock"></i>On Hold</a>
                                            @endif
                                                   
                                                </li>
                                            </ul>
                                            <a href="javascript:void(0);" class="hide-info"><i class="fas fa-caret-up"></i> Hide Details</a>
                                        </div>  
                                    </div>
                                   @endif
                                    @endforeach
                                   
                                    

                                   
                                </div>
                              
                            </div>
                        </div>
</div>
                       
                        <div class="col-lg-3 col-md-6 col-sm-12">   
                            <div class="pack-box">
                                <div class="box-header">
                                    <h2>Checked <span>{{ $checking_patient_count->count() }}/{{ $patients->count() }}</span></h2>
                                    <div class="form-group">
                                        <ul>
                                            <li>
                                                <input type="text" class="form-control" placeholder="Search" id="searchthree"/>
                                                <img src="images/search.svg" alt="" />
                                            </li>
                                            <li>
                                                <a href="#">A-Z <img src="images/filter.svg" alt="" /></a>
                                            </li>
                                       
                                       
                                            
                                            </ul>
                                        <div class="add-card">
                                        <a href="javascript:void(0);" onclick="set_value('2');removealert('0')" data-bs-toggle="modal" data-bs-target="#card-modal"><i class="fal fa-plus"></i> Add Card</a>
                                    </div>
                                    <!-- <a href="{{url('time_limt_packed')}}">latest entry</a> -->
                                    </div>
                                </div>
                                <div class="box-body droptrue" id="sortable3">
                                @foreach ($checkings     as $Checking )
                                @if(isset($Checking->patients->first_name) && $Checking->patients->first_name!="")
                                
                                    <div class="pack-card brd-color1" id="{{$Checking->patients->id}}" >
                                  
                                    <div class="checkbox">
                                            <input type="checkbox" id="checkbox{{$Checking->id}}"><label for="checkbox{{$Checking->id}}"></label>
                                        </div>
                                 
                                        <div class="card-info" style="margin-bottom: -6%;">
                                            <h3>{{$Checking->patients->first_name.' '.$Checking->patients->last_name}}</h3>
                                           
                                            
                                            <ul>
                                                <li><span   style="margin-bottom: -11%;">{{date("j/n/Y, h:i A",strtotime($Checking->created_at))}}</span></li>
                                                </ul>
                                                <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="view_details"> <img src="images/arrow-drop-down.svg" alt="" /> View Details</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-btn">
                                        @if ($Checking->hold == '1')
                                            <a href="{{url('checking_unhold_button',$Checking->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($Checking->hold == '0')
                                            <a href="#checked_{{$Checking->patients->id}}" onclick="checkinghold({{$Checking->id}})"><i class="fas fa-clock"></i>On Hold</a>
                                            @endif
                                        </div>

                                        <div class="card-information">
                                            <ul class="information-info">
                                                <li><strong>Patient Name:</strong> <span>{{$Checking->patients->first_name.' '.$Checking->patients->last_name}}</span></a></li>
                                                <li><strong>Date Of Birth:</strong> <span>15/01/1980</span></a></li>
                                                <li><strong>Receiver:</strong> <span>Patient</span></a></li>
                                                <li><strong>Notes:</strong> <span>N/A</span></a></li>
                                                <li><strong>Pickup Date:</strong> <span>{{date("j/n/Y, h:i A",strtotime($Checking->created_at))}}</span></a></li>
                                                <li class="location-txt"><strong>Location:</strong> <span>{{$Checking->note_from_patient}}</span></a></li>
                                            </ul>
                                            <h4>Options:</h4>
                                            <ul class="btn-box">
                                                <li><a href="javascript:void(0);" onclick="editpatient('{{$Checking->id}}','{{$Checking->patient_id}}','{{$Checking->no_of_weeks}}','{{$Checking->note_from_patient}}');set_value('2');" data-bs-toggle="modal" data-bs-target="#card-modal"> <img src="images/edit.svg" alt=""> Edit</a></li>
                                                <li><a href="{{url('checking_board_Delete',$Checking->id)}}" class="delete-btn"> <img src="images/delete.svg" alt=""> Delete</a></li>
                                                <li>
                                                @if ($Checking->hold == '1')
                                            <a href="{{url('checking_unhold_button',$Checking->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($Checking->hold == '0')
                                            <a href="#checked_{{$Checking->patients->id}}" onclick="checkinghold({{$Checking->id}})"><i class="fas fa-clock"></i>On Hold</a>
                                            @endif
                                                </li>
                                            </ul>
                                            <a href="javascript:void(0);" class="hide-info"><i class="fas fa-caret-up"></i> Hide Details</a>
                                        </div>

                                    </div>
                                    @endif
                                    @endforeach
                                   

                                   
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="pack-box">
                                <div class="box-header">
                                    <h2>Picked Up <span>{{ $pickup_patient_count->count() }}/{{ $patients->count() }}</span></h2>
                                    <div class="form-group">
                                        <ul>
                                            <li>
                                                <input type="text" class="form-control" placeholder="Search"  id="searchfour"/>
                                                <img src="images/search.svg" alt="" />
                                            </li>
                                            <li>
                                                <a href="#">A-Z <img src="images/filter.svg" alt="" /></a>
                                            </li>
                                        </ul>
                                        <div class="add-card">
                                        <a href="javascript:void(0);"  onclick="set_value('3');removealert('0')" data-bs-toggle="modal" data-bs-target="#card-modal"><i class="fal fa-plus"></i> Add Card</a>
                                    </div>
                                    </div>
                                </div>
                                <div class="box-body droptrue" id="sortable4">
                                @foreach ($Pickups     as $pickup )
                                @if(isset($pickup->patients->first_name) && $pickup->patients->first_name!="")
                                    <div class="pack-card brd-color1" id="{{$pickup->patients->id}}">
                                    <div class="checkbox">
                                            <input type="checkbox" id="checkbox{{$pickup->id}}"><label for="checkbox{{$pickup->id}}"></label>
                                        </div>
                                 
                                        <div class="card-info" style="margin-bottom: -6%;">
                                            <h3>{{$pickup->patients->first_name.' '.$pickup->patients->last_name}}</h3>
                                            <ul>
                                                 <li><span   style="margin-bottom: -11%;">{{date("j/n/Y, h:i A",strtotime($pickup->created_at))}}</span></li>
                                               </ul>
                                               <ul>
                                                  <li>
                                                    <a href="javascript:void(0);" class="view_details"> <img src="images/arrow-drop-down.svg" alt="" /> View Details</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-btn">
                                        @if ($pickup->hold == '1')
                                            <a href="{{url('pickup_unhold_button',$pickup->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($pickup->hold == '0')
                                            <a href="#pickup{{$pickup->id}}" onclick="pickuphold({{$pickup->id}})"><i class="fas fa-clock"></i>On Hold</a>
                                            @endif
                                        </div>

                                        <div class="card-information">
                                            <ul class="information-info">
                                                <li><strong>Patient Name:</strong> <span>{{$pickup->patients->first_name.' '.$pickup->patients->last_name}}</span></a></li>
                                                <li><strong>Date Of Birth:</strong> <span>15/01/1980</span></a></li>
                                                <li><strong>Receiver:</strong> <span>Patient</span></a></li>
                                                <li><strong>Notes:</strong> <span>N/A</span></a></li>
                                                <li><strong>Pickup Date:</strong> <span>{{date("j/n/Y, h:i A",strtotime($pickup->created_at))}}</span></a></li>
                                                <li class="location-txt"><strong>Location:</strong> <span>{{$pickup->notes_from_patient}}</span></a></li>
                                            </ul>
                                            <h4>Options:</h4>
                                            <ul class="btn-box">
                                                <li><a href="javascript:void(0);"  onclick="editpatient('{{$pickup->id}}','{{$pickup->patient_id}}','{{$pickup->no_of_weeks}}','{{$pickup->notes_from_patient}}');set_value('3');" data-bs-toggle="modal" data-bs-target="#card-modal"> <img src="images/edit.svg" alt=""> Edit</a></li>
                                                <li><a href="{{url('pickup_board_Delete',$pickup->id)}}" class="delete-btn"> <img src="images/delete.svg" alt=""> Delete</a></li>
                                                <li>
                                                @if ($pickup->hold == '1')
                                            <a href="{{url('pickup_unhold_button',$pickup->id)}}" class="active"><i class="fas fa-clock"></i>On Hold</a>
                                            @elseif ($pickup->hold == '0')
                                            <a href="#pickup{{$pickup->id}}" onclick="pickuphold({{$pickup->id}})"><i class="fas fa-clock"></i>On Hold</a>
                                            @endif
                                                  
                                                </li>
                                            </ul>
                                            <a href="javascript:void(0);" class="hide-info"><i class="fas fa-caret-up"></i> Hide Details</a>
                                        </div>

                                    </div>
                                    @endif
                                @endforeach
                                   



                                   
                                </div>
                               
                            </div>
                        </div>

                      



                    </div>
                </div>
            </div>
            <!-- End Board BLock -->
        </div>





<!-- Pack Block -->
<div class="modal fade" id="alert-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><img src="images/alert.svg" alt=""> Alert</h5>
                    </div>
                    <div class="modal-body">
                        <h2>Would you like to change date?</h2>
                        <ul>
                            <li><a href="{{url('/packboard')}}">Yes</a></li>
                            <li><a href="javascript:void(0);" data-bs-dismiss="modal">No</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="alert-models" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Warning Drag</h5>
        <button type="button" class="close" data-dismiss="modal" id="close" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Cannot Reversed Drag & Drop.</p>
      </div>
      
    </div>
  </div>
</div>
        
        <div class="modal fade" id="card-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
        
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" >
                        <h5 class="modal-title" ><img src="images/browsers-outline.svg" alt="" /><span  id="typess"></span>     
              </h5>
                        <a href="{{url('/packboard')}}" data-bs-dismiss="modal" class="close-btn"   ><img src="images/close-circle.svg" alt="" id="close"/></a>
                    </div>
                   
          
         
        <!-- Modal -->
                    <div class="modal-body loadMore">
                    <form action="{{ url('save_packed_fields') }}" method="post">  
                           {{ csrf_field() }}  
                           <div class="alert alert-danger" role="alert" id="errordiv" >
 <span id="errormessage"></span>
</div>
    
 
                       <input type="text" name="text" id="txtselectedlist" value="" style="display:none"/>
                       <input type="text" name="type" id="type" value="" style="display:none"/>
                       <input type="text" name="id" id="first" style="display:none"/>   
                
                        <div class="form-group">
                        
                              <label class="family" for="name">{{__('Patient Name')}} <span style="color:red">*</span></label>
                              
                               <select onchange="getchagecount()" placeholder="Select Patient" id="first_name" name="patient_id[]"   class="form-control js-example-basic-multiple" multiple="mutliple"> 
                              <!-- <select onchange="getchagecount()" required style="height:30px" name="patient_name[]" id="patient_name" class="form-control js-example-basic-multiple"  multiple="multiple">     -->
                               <option value="selected" >{{__('Select Patient')}}</option>

@foreach($patients as $patient)  
@php
   $row=$patient;
  $location="";
  $lastnotes="";

  $rowid = $row->id;

  $checkinglocations=App\Models\Tenant\Pickups::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
  
  
  if(!empty($checkinglocations))
  {
    $lastnotes =$checkinglocations->notes_from_patient;
  }
  
  $Patientlocations=App\Models\Tenant\Patient::where('id',$row->id)->orderBy('created_at','desc')->first();
  $PLocations=App\Models\Tenant\PatientLocation::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
  if(!empty($checkinglocations) && $checkinglocations->location!="" && !empty($Patientlocations) && $Patientlocations->location!=NULL){
          $checkinglocation=$checkinglocations->location;
          $patientlocation=$Patientlocations->location;
          $checkinglocation=explode(',',$checkinglocation);
          $patientlocation=explode(',',$patientlocation);
          $all_location=array_merge($checkinglocation,$patientlocation);
          $all_location=array_unique($all_location);
          $location=implode(',',$all_location);
          
  }
  elseif(!empty($checkinglocations) && $checkinglocations->location!=""){
      $location=$checkinglocations->location;
      
  }
  elseif(!empty($Patientlocations) && $Patientlocations->location!=NULL){
      $location=$Patientlocations->location;
      
  }
  $patientLastNoteForPatient=App\Models\Tenant\NotesForPatient::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
  $last_noteForPatient="";
  $last_noteForPatientDate="";

  if(!empty($checkinglocations) && !empty($patientLastNoteForPatient)){
        if($patientLastNoteForPatient->created_at > $checkinglocations->created_at){
          $last_noteForPatient=$patientLastNoteForPatient->notes_for_patients;
          $last_noteForPatientDate=$patientLastNoteForPatient->created_at;
        }
        else{
          $last_noteForPatient=$checkinglocations->note_from_patient;
          $last_noteForPatientDate=$checkinglocations->created_at;
        }
        
      }
  elseif(!empty($patientLastNoteForPatient)){
        $last_noteForPatient=$patientLastNoteForPatient->notes_for_patients;
        $last_noteForPatientDate=$patientLastNoteForPatient->created_at;
        
  }
  elseif(!empty($checkinglocations)){
      $last_noteForPatient=$checkinglocations->note_from_patient;
      $last_noteForPatientDate=$checkinglocations->created_at;
      
  }
@endphp

  <option  {{old('patient_id')==$patient->id?'selected':''}}  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"
data-lastpickupnotes = "{{$lastnotes}}"
  data-patientname = "{{$patient->first_name.' '.$patient->last_name}}"
  data-lastNoteForPatient="{{$patient->latestPickups?$patient->latestPickups->notes_from_patient:''}}"
  data-lastLocation="{{isset($PLocations->locations)?$PLocations->locations:''}}"
data-last_pick_up_by="{{isset($patient->latestPickups->pick_up_by)?$patient->latestPickups->pick_up_by:''}}"
data-last_carer_name="{{isset($patient->latestPickups->carer_name)?$patient->latestPickups->carer_name:''}}"
data-last_noteForPatient="{{isset($last_noteForPatient)?$last_noteForPatient:''}}"
data-last_noteForPatientDate="{{!empty($last_noteForPatient)?$last_noteForPatientDate:''}}"
    value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("Y-m-d",strtotime($patient->dob)):""}} ) </option>
@endforeach
</select> 

<!-- 
@if(count($patients) && isset($patients))
<select onchange="getchagecount()" placeholder="Select  Patient" id="first_name" name="patient_id[]"   class="form-control js-example-basic-multiple" multiple="mutliple">
                         <option value="">-- Select  Patient --</option>
                                @foreach($patients as $patient)

                                 @php
                                        $checkinglocations=App\Models\Tenant\Checking::where('patient_id',$patient->id)->orderBy('created_at','desc')->first();
                                        $Patientlocations=App\Models\Tenant\Patient::where('id',$patient->id)->orderBy('created_at','desc')->first();
                                        $PLocations=App\Models\Tenant\PatientLocation::where('patient_id',$patient->id)->orderBy('created_at','desc')->first();
                                        if(!empty($checkinglocations) && $checkinglocations->location!=""){
                                            $location=$checkinglocations->location;
                                        }
                                        elseif(!empty($Patientlocations) && $Patientlocations->location!=NULL){
                                            $location=$Patientlocations->location;
                                        }

                                 @endphp
                                  <option {{old('patient_id')==$patient->id?'selected':''}}
                                  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"
                                  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"
                                  data-last_CheckingLocation="{{isset($PLocations->locations)?$PLocations->locations:''}}"
                                   data-last_CheckingDD="{{isset($patient->latestChecking->dd)?$patient->latestChecking->dd:''}}"
                                   value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                @endforeach
                              </select>
                                @endif -->
@error('patient_id')
  <span class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
  </span>
@enderror
                         
 </div>
                           



                           <div class="form-group" >
                              <label class="family" for="no_of_weeks"   >{{__('Number of Weeks ')}}</label>
                              <!-- <input class="family text-center form-input" readonly  value="{{(old('no_of_weeks'))?old('no_of_weeks'):$default_cycle[0]['default_cycle']}}" style="float: right;margin-top: -13%;width: 8%;margin-bottom: .5rem;"  onkeypress="return restrictAlphabets(event);" > -->
                            </div>
                            <div style="margin-top: -18px; margin-bottom: 23px;">
                              <input type="number"    value="{{(old('no_of_weeks'))?old('no_of_weeks'):$default_cycle[0]['default_cycle']}}"  class="form-control @error('no_of_weeks') is-invalid @enderror" maxlength="3" onkeypress="return restrictAlphabets(event);" id="no_of_weeks"   name="no_of_weeks" placeholder="No Of Weeks"  >
                              @error('no_of_weeks')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-12"  id="notesdiv">
                            <label for="dob" class="family">{{__('Date Of Birth:')}} <span>(dd/mm/yy)</span></label>
                                <!-- <label for="dob" class="family">{{__('Date Of Birth')}}</label> -->
                                <input type="text" readonly value="{{old('dob')}}" class="form-control @error('dob') is-invalid @enderror"  max="{{Carbon\Carbon::now()->format('d/m/Y')}}"   id="dob" placeholder="Date Of Birth" >
                                @error('dob')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group" id="p">
                                <label>Receiver</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="who_pickup" id="inlineRadio1" value="Patient"/>
                                    <label class="form-check-label" for="inlineRadio1">Patient</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="who_pickup" id="inlineRadio2" value="carer" />
                                    <label class="form-check-label" for="inlineRadio2">Carer</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address:</label>
                                <textarea class="form-control" name="address" id="address" placeholder="107 Friar John Way, SECRET HARBOUR, Western Australia (WA), 6173" rows="4"></textarea>
                            </div>

<button type="submit" id="edit" class="btn btn-primary" ><img src="images/save.svg" alt="" /> Save</button>

                           
                        </form>
                    </div>
                </div>
            </div>
        </div>

       
</div>
</div>
<!-- End Board BLock -->
</div>
          
  
          <!-- /.content-wrapper -->
         
      @endsection
    
  
@section('customjs')



<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script> -->

<script type="text/javascript">
  
  var geocoder;
  var map;
  
  var markers = [];

  $('document').ready(function(){
        
    
    $("#main-wrap").css("display", "none");

     $("#company_id").select2();
      });

      $('li').bind('dragstart', function(event) {
      event.originalEvent.dataTransfer.setData("text/plain",  event.target.getAttribute('id'));
    });

    $('ul').bind('dragover', function(event) {
      event.preventDefault();
    });

    $('ul').bind('dragenter', function(event) {
      $(this).addClass("over");
    });

    $('ul').bind('dragleave drop', function(event) {
      $(this).removeClass("over");

    });

    $('li').bind('drop', function(event) {
      return false;
    });

    $('ul').bind('drop', function(event) {
      var listitem = event.originalEvent.dataTransfer.getData("text/plain");
      event.target.appendChild(document.getElementById(listitem));
      event.preventDefault();
      
    //   alert(event.target.id);
    });

</script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
         <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
         <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
         
     <script>
//         $(function() {
//         $( "#sortable" ).sortable(
//         {
//         update: function( event, ui ) {
//             $('#card-modal').modal('show');
//             console.log(ui.item.parent().attr('id'));
//         }
//         }, 
//         {connectWith:"#sortable-2,#sortable-3,#sortable-4"});

//         $( "#sortable-2" ).sortable({
//         update: function( event, ui) 
//         {
//   $('#card-modal').modal('show');
//   console.log(ui.item.parent().attr('id'));
//       //  document.getElementById("type").value = ui.item.parent().attr('id'); 
//       document.getElementById("typess").innerHTML="Packed";
//         document.getElementById("type").value ='1';
// //$('#selects').val('id')
// //        console.log('#selects').val('id');   
//   }
// },{connectWith:"#sortable,#sortable-3,#sortable-4"});

//     $( "#sortable-3" ).sortable(
//     {
//     update: function( event, ui )
//     {
        // $('#card-modal').modal('show');
        // console.log(ui.item.parent().attr('id'));
        // document.getElementById("typess").innerHTML="Checked";
        // document.getElementById("type").value = '2';
//     }
//     },{
//     connectWith:"#sortable,#sortable-2,#sortable-4"
//     });

//         $( "#sortable-4" ).sortable(
//         {
//         update: function( event, ui ) {
//             $('#card-modal').modal('show');
//             console.log(ui.item.parent().attr('id'));
//             document.getElementById("typess").innerHTML="Pickups";
//         document.getElementById("type").value = '3';
//         }


//         },{connectWith:"#sortable,#sortable-2,#sortable-3"});
//         });


 var a_href = [];
 var value_assign = "";
 var ddl_value_assign = [];

var drag = 0;



//var separate_value_assign = "'" ;
    
$(function () {
    $('.droptrue').on('click', 'div', function (ui,e) {
        $(this).toggleClass('selected');
        a_href.push($(this).attr('id'));
        ddl_value_assign.push($(this).attr('id'));
       // console.log(ddl_value_assign);

    console.log( $(this).parent().attr('id').replace("sortable",""));
    drag = $(this).parent().attr('id').replace("sortable","");
      if($(this).hasClass('selected'))
      {
        console.log('true');
      }
      else
      {
        console.log('false');
      }
    });

    $("div.droptrue").sortable({
        connectWith: 'div.droptrue',
        opacity: 0.6,
        revert: true,
        helper: function (e, item) {
            //console.log('parent-helper');
            // console.log(item.attr('id'));
           // console.log(e);
            if(!item.hasClass('selected'))
               item.addClass('selected');
            var elements = $('.selected').not('.ui-sortable-placeholder').clone();
            var helper = $('<div/>');
            item.siblings('.selected').addClass('hidden');
            return helper.append(elements);
        },
        start: function (e, ui) {
            var elements = ui.item.siblings('.selected.hidden').not('.ui-sortable-placeholder');
            ui.item.data('items', elements);
        },
        receive: function (e, ui) {
            ui.item.before(ui.item.data('items'));
        },
        stop: function (e, ui)
         {
            ui.item.siblings('.selected').removeClass('hidden');
            $('.selected').removeClass('selected');
        },
        update: function(e,ui){
           
            var ids =ui.item.parent().attr('id').replace("sortable","");
           // console.log(drag +'>'+ ids);
          if (drag > ids) {
            $('#alert-models').modal('show');
          }
          else
          {
            updatePostOrder();
            updateAdd();
            if(ids == 3)
            {
                latest_entry();
            }
            else if(ids == 4)
            {
                latest_entry_checking();
            }
            else
            {
                console.log('error');
            }
            
            
            var i = 0;
$('#txtselectedlist').val("");
for (i = 0; i < a_href.length; i++)
{
   value_assign += a_href[i] + ",";
   
}
a_href.length = 0;
console.log(value_assign);
$('#txtselectedlist').val(value_assign);

dropdown(ddl_value_assign);

          if(e.target.id == 'sortable2')
          {
            $("#p").hide();
            $('#card-modal').modal('show');
           
            set_value(1);
          
          }
          else if(e.target.id == 'sortable3')
          {
            $("#p").hide();
            $('#card-modal').modal('show');
           
            set_value(2);
           
          }
          else if(e.target.id == 'sortable4')
          {
            $("#p").show();
            $('#card-modal').modal('show');
           
            set_value(3);
           
          }
          else
          {
            document.getElementById("typess").innerHTML="To Pack";
          }
          }

          
        },
       
    });
   

    $("#sortable1, #sortable2, #sortable3, #sortable4").disableSelection();
    $("#sortable1, #sortable2, #sortable3, #sortable4").css('minHeight', $("#sortable1, #sortable2").height() + "px");
});

$('[data-search]').on('keyup', function() {
	var searchVal = $(this).val();
	var filterItems = $('[data-filter-item]');

	if ( searchVal != '' ) {
		filterItems.addClass('hidden');
		$('[data-filter-item][data-filter-name*="' + searchVal.toLowerCase() + '"]').removeClass('hidden');
	} else {
		filterItems.removeClass('hidden');
	}
});


function updatePostOrder() {
    var arr = [];
    $("#sortable2 div").each(function () {
        arr.push($(this).attr('id'));
    });
    $('#postOrder').val(arr.join(','));
   
}


function updateAdd() {
    
}
function removealert(type){
  $("#errordiv").fadeOut();
}

function latest_entry_checking()
{
   
    $.ajax({
                method: "get",
               // alert('kkk');
                url: "{{url('/time_limt_checking')}}",
               
                contentType: "application/json; charset=utf-8",
                dataType: "json",
               success: function(data) {
             //  alert(data[0].verifyid);
                if (data[0].verifyid == 1){
              //  alert(data.length);
                $("#errordiv").fadeIn();
                 document.getElementById("errormessage").innerHTML = "Dublicate Entry";
                 document.getElementById("first").value = data[0].id;
                // document.getElementById("first_name").value = data[0].first_name;
                 document.getElementById("no_of_weeks").value = data[0].no_of_weeks;
                 document.getElementById("address").value = data[0].notes_from_patient;
                  
               }
               else if(data[0].verifyid == -1)
                {
                    $("#errordiv").fadeOut();
                  // alert('2');   
                }
                
                
//                 if(data <= 12)
//                 {
// //                     swal({  
// //   title: "Dublicate Entry found!",  
// //   text: "Dublicate Entry found!",  
// //   icon: "danger",  
// //  // button: "{{url('/packboard')}}", 
// //   timer: 8888, 
// // }).then(function (data) {
// //   if (true) {
// //     window.location = "{{url('/packboard')}}";
// //   }
// // }); 
//  alert('Dublicate entry with json')
//                 }
//                 else
//                 {
                   
                 
//                 }
                
                },
                failure: function(data) {
                    console.log('Failed');
                },
                error: function(data)
                {
                    console.log(data)
                }

            }); 
          //  alert('1');
}
  
function latest_entry()
{
   
    $.ajax({
                method: "get",
               // alert('kkk');
                url: "{{url('/time_limt_packed')}}",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
               success: function(data) {
              //  alert(data[0].verifyid);
                // alert(data.length);
                if (data[0].verifyid == 1) 
                {   
                  //  alert(data.length);
                     $("#errordiv").fadeIn();
                document.getElementById("errormessage").innerHTML = "Dublicate Entry";
                   // console.log(data);
                 //   alert(data[0].id);
                // alert(data[1].first_name);
                //document.getElementById("first_name").value = data[0].patient_id;
             //   $('#first_name').val(patient_id).trigger('change') = data[0].patient_id;
                // $('#first_name').val(patient_id) =data[0].patient_id;
              //  $('#first_name').val(patient_id).trigger('change') = data[0].patient_id;
              document.getElementById("first").value = data[0].id;
               // document.getElementById("first_name").value = data[0].first_name;
                 document.getElementById("no_of_weeks").value = data[0].no_of_weeks;
                 document.getElementById("address").value = data[0].note_from_patient;

                    
                }
                else if(data[0].verifyid == -1)
                {
                    $("#errordiv").fadeOut();
                  // alert('2');   
                }
                // if(data <= 12)
                // {
                    
                //     // swal({  
                //     // title: "Dublicate Entry found!",  
                //     // text: "Dublicate Entry found!",  
                //     // icon: "danger",  
                //     // // button: "{{url('/packboard')}}", 
                //     // timer: 8888, 
                //     // }).then(function (data) {
                //     // if (true) {
                //     // window.location = "{{url('/packboard')}}";
                //     // }
                //     // });
                //     alert('Dublicate entry with json');
                // }
                // else
                // {
                   
                // }
                
                },
                failure: function(data) {
                    console.log('Failed');
                },
                error: function(data)
                {
                    console.log(data)
                }

            }); 
          //  alert('1');
}

function reset()
{
$("#patient").val("");
// var saveButton = document.getElementById('signature-pad-save'),
var clearButton = document.getElementById('signature-pad-clear');

clearButton.addEventListener('click', function (event) {
  signaturePad.clear();
});



var  clearButton2 = document.getElementById('signature-pad2-clear');

clearButton2.addEventListener('click', function(event) {
    signaturePad2.clear();
    //document.getElementById('patient_sign').value = "";
});


$("#note").val("");
  
}


$(document).ready(function(e) {
        var limit = 5;
            $(".loadMore li").slice(0, limit).show();
            $(document).on('click','#load_more',function(e){
            limit += 5;
            e.preventDefault();
            $(".loadMore li").slice(0, limit).show();
            });
        //var lenght_name = $(".loadMore li").length;
    });
function dropdown(e)
{

  var slice = e.slice(0, -1);
 
   $('#first_name').val(e).trigger('change');
 console.log();
    //$("#first_name").val(e).trigger('change');
}
    $(document).ready(function(){
       
  $("#hold").click(function(){
  
   //alert(  );
   // alert("The paragraph was clicked.");
   console.log($("#hold :input").val())
   //window.location.href = "{{url('packboard')}}";
  
  });
});
function restrictAlphabets(e){
        var x=e.which||e.keycode;
        if((x>=48 && x<=57) )
        return true;
        else
        return false;
     }
     function editpatient(id,patient_id,no_of_weeks,note_from_patient) 
     {
     
       document.getElementById("first").value = id;
       $('#first_name').val(patient_id).trigger('change');
      //  $("#first_name").val(patient_id);
        document.getElementById("no_of_weeks").value = no_of_weeks;
        document.getElementById("address").value = note_from_patient;
    
}

// function set_value(a) 
// {
//  // document.getElementById("demo").innerHTML = "Hello World";
 
 
// }
function set_value(a)
{
   // alert(a);
    if(a==1)
    {
        $("#p").hide();
       // alert();
        document.getElementById("typess").innerHTML="Packed";
        document.getElementById("type").value=a;
    }
    else if(a==2)
    {
        $("#p").hide();
        document.getElementById("typess").innerHTML="Checked";
        document.getElementById("type").value = a;
    }
    else if(a==3)
    {
        $("#p").show();
        document.getElementById("typess").innerHTML="Picked up";
        document.getElementById("type").value = a;
    }
   // document.getElementById("type").value = a,b,c;
    
 }
 $('#close').click(function() {
    location.reload();
});
// $('#card-modal').click(function() {
//     location.reload();
// });
 function set_model(a)
{
    document.getElementById("editcard").value = a;
    
 }
 
 function set_hold(a)
{
    document.getElementById("hold").value = a;
    
 }
//  function save_hold()
// {
   
//  }

 function reset()
{

  
  $("#patient").val("");
  $("#patient").trigger('change');
  $("#dob").val("");
  $("#last_pick_up_day").val("");
  $("#last_pick_up_month").val("");
  $("#last_pick_up_year").val("");

  

  
// var saveButton = document.getElementById('signature-pad-save'),
var clearButton = document.getElementById('signature-pad-clear');

clearButton.addEventListener('click', function (event) {
  signaturePad.clear();
});
var  clearButton2 = document.getElementById('signature-pad2-clear');

clearButton2.addEventListener('click', function(event) {
    signaturePad2.clear();
    //document.getElementById('patient_sign').value = "";
});


$("#note").val("");
  
}

$('#first_name').change(function(){
        var ob=$(this).children('option:selected');
        var dob=ob.attr('data-dob');
        var no_of_weeks=ob.attr('data-no_of_weeks');
       // alert(dob);
        var lastPickupDate=ob.attr('data-lastPickupDate');
        var lastPickupWeek=ob.attr('data-lastPickupWeek');
        var lastNoteForPatient=ob.attr('data-lastNoteForPatient');
        $('#dob').val(dob);
        $('#no_of_weeks').val(no_of_weeks);
        $('#last_pickup_date').text(lastPickupDate);
        $('#last_pickup_week').text(lastPickupWeek);

        $('#weeks_last_picked_up').val(lastPickupWeek);
        $('#last_pick_up_date').val(lastPickupDate);
        $('#note').html(lastNoteForPatient);
      });

      function getWeeksDiff(startDate, endDate) {
  const msInWeek = 1000 * 60 * 60 * 24 * 7;

  return Math.round(Math.abs(endDate - startDate) / msInWeek);
}
 function getSlots()
{
   var date = document.getElementById("dateOfPickup").value;
   var company_name = document.getElementById("company_name").value;    

   if(company_name =="")
   {
        $("#lblcompanyerror").fadeIn();
        $("#company_name").focus();
        return;
   }
   else
   {
    $("#lblcompanyerror").fadeOut();
   }
                if(date) {                
                  $.ajax({
                    type: "get",
                data:{"date":date,"company_name":company_name},
                    dataType: "html",
                    url: "/admin/getPickupSlots",
                    cache: false,
                    beforeSend: function() {
                      $('#slots').html('loading please wait...');
                   },
                    success: function(htmldata) {
                     
                       $('#slots').html(htmldata);
                    }
                  });
                }
}

 $(document).ready(function(){
   
    $("#first_name").select2();
  //  alert();
  $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#sortable2 div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

$(document).ready(function(){
  $("#searchthree").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#sortable3 div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
$(document).ready(function(){
  $("#searchfour").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#sortable4 div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
$(document).ready(function(){
  $("#searchone").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#sortable1 div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
$(document).ready(function(){
  $("#tosearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".droptrue div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
 
function getchagecount()
{
    var ddvalue = $("#first_name").val();

    if(ddvalue.length>1)
    {
    $("#notesdiv").hide();
    $("#notesdiv").val("");
    }
    else
    {
        $("#notesdiv").fadeIn();
    }
 }
 var date=new Date(); 
                    var firstDay=new Date(date.getFullYear(), date.getMonth(), 1); 
                    var lastDay=new Date(date.getFullYear(), date.getMonth() + 1, 0);
// var today = new Date();
// var endDate = new Date();
// endDate.setMonth(endDate.getMonth() + 1);
$(function () {
        $("#date").daterangepicker(
            {
                startDate: firstDay, // after open picker you'll see this dates as picked
    endDate: lastDay,
    locale: {
       format: 'DD-MM-YYYY',
    },
                opens: "left",
            },
            function (start, end, label) {
                console.log("A new date selection was made: " + start.format("YYYY-MM-DD") + " to " + end.format("YYYY-MM-DD"));
            }
        );
    });

// $(document).ready(function(e,start,end){
//   $('#daterange').datepicker({
//     numberOfMonths :2,
//    // dateFormat: "dd/mm/yy" + '-' +"dd/mm/yy"
//    dateFormat: "dd/mm/yy" + "-" + "dd/mm/yy",
//    separator: " to "
//   }
// //   function (start, end, label) {
// //     dateFormat:"A new date selection was made: " + start.format("YYYY-MM-DD") + " to " + end.format("YYYY-MM-DD")
// // }
// );            });

      function savehold(val)
      {

// alert(1);
// return;
        //  event.preventDefault();
        //   alert('ok');
            $.ajax({
                type: "Get",
               // alert('kkk');
                url: "{{url('packed_hold_button')}}/"+val,
               
                success: function(data) {
               // alert('data save');
                },
                failure: function(data) {
                    console.log('Failed');
                }

            });
            


        }
function saveunhold(value)
{
   // alert('ok');
    $.ajax({
                type: "Get",
               // alert('kkk');
                url: "{{url('packed_unhold_button')}}/"+value,
               
                success: function(data) {
               // alert('data save');
                },
                failure: function(data) {
                    console.log('Failed');
                }

            });  
}
function checkinghold(val)
{
    $.ajax({
                type: "Get",
               // alert('kkk');
                url: "{{url('checking_hold_button')}}/"+val,
               
                success: function(data) {
               // alert('data save');
                },
                failure: function(data) {
                    console.log('Failed');
                }

            }); 
}
function pickuphold(val)
{
    $.ajax({
                type: "Get",
               // alert('kkk');
                url: "{{url('pickup_hold_button')}}/"+val,
               
                success: function(data) {
               // alert('data save');
                },
                failure: function(data) {
                    console.log('Failed');
                }

            }); 
}
   </script>
   
     <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script> -->

 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> 
 <!--<script src="multiselect/jquery.multiselect.js"></script> -->
<script src="./packboardassets/js/bootstrap.bundle.min.js"></script>
 <script src="./packboardassets/js/custom.js"></script>  

@endsection

