@extends('admin.layouts.mainlayout')
@section('title') <title>Due Pickup Report </title>
<style>
  /* #example1_wrapper {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-left: 20px;
} */


  </style>
@endsection





@section('content')
 <!-- Content Wrapper. Contains page content -->
 
 <div class="content-wrapper">
        <div class="dash-wrap">
        <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Due Pickup Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
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

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb" style="width:100%">
          <div class="row">
            <div class="col-md-7">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">Due Pickup Reports</li>
              </ol>
            </div>

            
          </div>    
         

            
            </nav>

          </div>
       
          <style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                       
/* right: -1062%;
    bot    tom: 90; */
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>

   
       
       
             



         <!-- Main content -->
         <section class="content">
          <div class="row">
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
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                <div class="search-logs">
                <form class="report-form"   action="{{ url('admin/due_patient_filter_report') }}" method="post"  >
                {{ csrf_field() }}

                <div class="row">
                <div class="col-md-12">
                @if(isset($all_pharmacy))
                            Pharmacy
                            <select onchange="this.form.submit()"  id="company_id" name="company_id">
                                <option value="0">Select Pharmacy</option>
                                  @foreach($all_pharmacy as $pharmacy)
                                    @if($pharmacy->website_id == $company_id)
                                  <option selected value="{{ $pharmacy->website_id }}">{{ $pharmacy->company_name }} </option>
                                  @else
                                  <option  value="{{ $pharmacy->website_id }}">{{ $pharmacy->company_name }} </option>
                                  @endif
                                  @endforeach
                            </select>
                    @endif    
                    &nbsp;&nbsp;
                    Week Date <input value="{{$nextweekdate}}"   name="dateweeks" type="date"/>
                </div>

                  <div class="col-md-12 mt-3">
                  
                  Date From 
                      <input value="{{$datefrom}}"  name="datefrom" type="date"/> 
                      to
                      <input value="{{$dateto}}" name="dateto" type="date" />
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>OR </strong>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Include Patients 
                      @if($weeks==0)
                      <input   style="width:10%" name="excludeweeks" type="number"/>
                      @else
                      <input value={{$weeks}} style="width:10%"   name="excludeweeks" type="number"/>
                      
                      @endif
                      (Weeks) 
                    <button type="submit" class="btn btn-primary">search<i class="ml-2 fas fa-arrow-circle-right"></i></button>
                    

                   
                  </div>


                 
                </div>
                  
                </form>
<hr/>



                <div class="table-responsive">
            <div class="col-md-12">
                
                @if(isset($new_patients))
                  <table id="example1" class="table">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Patient Name/DOB</th>
                        <th>Facility</th>
                        <th>Store</th>
                        <th>Last pick-up date/Number of weeks last picked up</th>
                        <th>Next due pick up date!</th>
                       
                        <th >medication enough until</th>
                        <th>How many weeks are needed?</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($new_patients as $value)
                     @php
                        $m=explode(',',$value->locations);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                      <tr id="row_{{$value->id}}">
                        <td></td>
                      <td>
                        {{ucfirst($value->first_name).' '.ucfirst($value->last_name)}} {{$value->dob}}
                      </td>
                      <td>{{strtoupper($value->name)}}</td>
                      <td>
                           @if(isset($locations) && count($locations))
                              @php $locationarray=array(); @endphp
                              @foreach($locations as $row)
                                @php array_push($locationarray,$row->name); @endphp
                              @endforeach
                              {{implode(',',$locationarray)}}
                            @endif
                          </td>
                          <td>
                     @if($value->lastpickupdate <>"")
                     {{date("j/n/Y",strtotime($value->lastpickupdate))}}, {{$value->noofpickuppacks}}
                     @else
                     {{$value->noofpacks}}
                     @endif
                     </td>


                     @if($value->Isconditionalformatting=="1")
                     <td  style="color:red">
                     {{date('d-m-Y', strtotime($value->nextduepickupdate))}}
                     
                    
                    </td>
                     @else
                     <td style="color:black">
                     <!-- {{$value->nextduepickupdate}} -->
                     {{date('d-m-Y', strtotime($value->nextduepickupdate))}}
                    </td>
                     @endif
                     
                     <td>
                      <!-- {{$value->medicalenough}} -->

                      {{date('d-m-Y', strtotime($value->medicalenough))}}

                     </td>
                     <td>{{$value->weeksneeded}}</td>
                     
                     
                    </tr>
                     
                      @endforeach
                    </tbody>
                  </table>
                  
                  @else
                  <h5 class="box-title text-danger">There is no data.</h3>
                  @endif


                 
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->

@endsection


@section('customjs')

<script type="text/javascript">
   $(document).ready(function(){
  $("#company_id").select2();
});
    

      
      
      

    </script>
@endsection
