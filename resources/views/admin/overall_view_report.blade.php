@extends('admin.layouts.mainlayout')
@section('title') <title>Overall View  Report </title>
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
            <h2>Overall View  Report</h2>
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
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">Overall View Reports</li>
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
                <form class="report-form"   action="{{ url('admin/overall_filter_report') }}" method="post"  >
                {{ csrf_field() }}

                <div class="row">
                
                 
                  <div class="col-md-12 mt-3">
                  
                  <div style="display:none">
                      Date From 
                      <input value="{{$datefrom}}"  name="datefrom" type="date"/> 
                      &nbsp;&nbsp;&nbsp; Date to
                      <input value="{{$dateto}}" name="dateto" type="date" />
                      <strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      OR </strong>
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    

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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Include Patients  
                      @if($weeks==0)
                      <input  required  style="width:5%" name="excludeweeks" type="number"/>
                      @else
                      <input required value={{$weeks}} style="width:5%"   name="excludeweeks" type="number"/>
                      
                      @endif
                      (Weeks)
                    <button type="submit" class="btn btn-primary">search<i class="ml-2 fas fa-arrow-circle-right"></i></button>
                    
                  </div>


                 
                </div>
                  
                </form>
<hr/>
<div class="box-body pre-wrp-in table-responsive">
              
                <div class="col-md-12">
                @if(isset($new_patients))
                  <table id="example1" class="table">
                    <thead>
                      <tr>
                      <th></th>
                        <th>Patient Name</th>
                        <th>Facility</th>
                        <th>Store</th>
                        <th>Last packed, number of packs packed</th>
                        <th>Last checked, number of packs checked</th>
                       
                        <th >Last Audit, #of packs Audited</th>
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
                     <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</td>
                     <td>{{strtoupper($value->name)}}</td>

                     <!-- <td>{{isset($value->facility)?strtoupper($value->facility->name):''}}</td> -->
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

                     <td>@if($value->lastcheckeddate <>"")
                     {{date("j/n/Y",strtotime($value->lastcheckeddate))}}, {{$value->noofcheckedpacks}}
                     @else
                     {{$value->noofcheckedpacks}}
                     @endif</td>

                      <td>@if($value->lastauditdate <>"")
                     {{date("j/n/Y",strtotime($value->lastauditdate))}}, {{$value->noofauditpacks}}
                     @else
                     {{$value->noofauditpacks}}
                     @endif</td>
                    
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

  function reloadpage()
  {
    location.reload();
  }
    


      
      
      

    </script>
@endsection
