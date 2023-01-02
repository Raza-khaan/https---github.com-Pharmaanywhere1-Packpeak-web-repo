<style>
a.dt-button.dropdown-item.copyButton
{
  text-align: center !important;
    margin: 4px !important;
    width: 38% !important;
    color: white !important;
    background-color: #007bff !important;
    border-color: #007bff !important;
}

  </style>
<section>
  <div class="container-fluid">
    <div class="row">
      <div class="side-menu">
        <div class="logo-head">
          <a class="logo" href="{{url('admin/dashboard')}}"><img width="200px" src="{{ URL::asset('admin/images/logo.png')}}" alt=""></a>
          <a class="small-logo" href="{{url('admin/dashboard')}}"><img src="{{ URL::asset('admin/images/logo-small.png')}}" alt=""></a>
          <a class="menu-arrow" href="javascript:void(0);" id="close-btn"><img class="menu-arrow-left" src="{{ URL::asset('admin/images/arrow.png')}}" alt=""><img class="menu-arrow-right" src="{{ URL::asset('admin/images/arrow-right.png')}}" alt=""></a>
        </div>

        <div class="accordion">
          <a id="menu-bar-close" href="#"><i class="fa fa-times"></i></a>
          <ul>
            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/dashboard')}}"><img src="{{ URL::asset('admin/images/dashboard.png')}}" alt=""><span>Dashboard</span></a>
            </li>

               <!-- <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/all_pharmacies')}}">
                <img src="{{ URL::asset('admin/images/medkit.png')}}" alt=""><span>Pharmacies</span></a>
            </li>  -->


             <li class="sidenav">
              <h4><a href="pharmacies.html"><img src="{{ URL::asset('admin/images/medkit.png')}}" alt=""><span>Pharmacies</span></a><i class="fa fa-angle-right"></i></h4>
              <ul style="display: none;">
                <li class="page_item"><a class="nav-link" href="{{url('admin/all_pharmacies')}}">
                <img src="{{ URL::asset('admin/images/medkit.png')}}" alt="">
                  Pharmacies</a></li>
              </ul>
            </li>
             

            <!-- <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/all_technician')}}">
                <img src="{{ URL::asset('admin/images/people.png')}}" alt=""><span>Users</span></a>
            </li>  -->


             <li class="sidenav">
              <h4><a href="#"><img src="{{ URL::asset('admin/images/people.png')}}" alt=""><span>Users</span></a><i class="fa fa-angle-right"></i></h4>
              <ul style="display: none;">
                <li class="page_item"><a class="nav-link" href="{{url('admin/all_technician')}}">
                <img src="{{ URL::asset('admin/images/people.png')}}" alt="">
                Users</a></li>
              </ul>
            </li> 
             <li class="sidenav">
              <h4><a href="#"><img src="{{ URL::asset('admin/images/forms.png')}}" alt=""><span>Forms</span></a><i class="fa fa-angle-right"></i></h4>
              <ul style="display: none;">
                <li class="page_item"><a class="nav-link"  href="{{url('admin/add_pharmacy')}}">
                <img src="{{ URL::asset('admin/images/medkit.png')}}" alt="">  
                Add Pharmacy</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/technician')}}">
                <img src="{{ URL::asset('admin/images/people.png')}}" alt="">
                Add User</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/patients')}}">
                <img src="{{ URL::asset('admin/images/disabled.png')}}" alt="">  
                Add Patient</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/pickups')}}">
                <img src="{{ URL::asset('admin/images/ambulance.png')}}" alt="">  
                Add Pickup</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/checkings')}}">
                <img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt="">  
                Add Checking</a></li>

                <li class="page_item"><a class="nav-link"  href="{{url('admin/packed')}}">
                <img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt="">  
                Add Packed</a></li>


                <li class="page_item"><a class="nav-link"  href="{{url('admin/near_miss')}}">
                <img src="{{ URL::asset('admin/images/price-tag.png')}}" alt="">  
                Add Near Miss</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/returns')}}">
                <img src="{{ URL::asset('admin/images/reply.png')}}" alt="">  
                Add Return</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/audits')}}">
                <img src="{{ URL::asset('admin/images/editing.png')}}" alt="">  
                Add Audit</a></li>
                <li class="page_item"><a class="nav-link"  href="{{url('admin/notes_for_patients')}}">
                <img src="{{ URL::asset('admin/images/price-tag.png')}}" alt="">  
                Add Patient Notes</a></li>
              </ul>
            </li>
           

            <!-- <li class="sidenav">
              <a class="nav-link active" ><img src="{{ URL::asset('admin/images/list.png')}}" alt=""><span>Packs Board</span></a>
            </li>


            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/new_patients_report')}}" ><img src="{{ URL::asset('admin/images/disabled.png')}}" alt=""> <span>Patients</span></a>
            </li>

            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/packed_report')}}" ><img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt=""> <span>Packing</span></a>
            </li>

            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/checkings_report')}}" ><img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt=""> <span>Checking</span></a>
            </li>

            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/pickups_reports')}}" ><img src="{{ URL::asset('admin/images/ambulance.png')}}" alt=""> <span>Pickup</span></a>
            </li>


            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/all_returns')}}" ><img src="{{ URL::asset('admin/images/editing.png')}}" alt=""> <span>Returns</span></a>
            </li>

            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/all_audits')}}" ><img src="{{ URL::asset('admin/images/editing.png')}}" alt=""> <span>Audit</span></a>
            </li>

            
            <li class="sidenav">
              <a class="nav-link active" href="{{url('admin/near_miss')}}" ><img src="{{ URL::asset('admin/images/price-tag.png')}}" alt=""> <span>Near-miss</span></a>
            </li>

            <li class="sidenav"><a class="nav-link" href="{{url('admin/search')}}"><img src="{{ URL::asset('admin/images/search.png')}}" alt=""><span>Search</span></a></li> -->


            <li class="sidenav">
              <h4><a href="#"><img src="{{ URL::asset('admin/images/reports.png')}}" alt=""><span>Reports</span></a><i class="fa fa-angle-right"></i></h4>
              <ul style="display: none;">
              <li><a href="{{url('admin/pharmacy_reports')}}" >
                
              <img src="{{ URL::asset('admin/images/medkit.png')}}" alt=""> 
              Pharmacy  List</a></li>  
              <li><a href="{{url('admin/pickups_reports')}}" >
              <img src="{{ URL::asset('admin/images/ambulance.png')}}" alt="">   
              Pick-Ups List</a></li>
             
                <li><a href="{{url('admin/new_patients_report')}}" >
                <img src="{{ URL::asset('admin/images/disabled.png')}}" alt="">   
                Patient  List</a></li>
                <li><a href="{{url('admin/checkings_report')}}">
                <img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt="">   
                Checking Report</a></li>

                <li><a href="{{url('admin/packed_report')}}">
                <img src="{{ URL::asset('admin/images/magic-wand.png')}}" alt="">   
                Packed Report</a></li>
              
                <li><a href="{{url('admin/all_near_miss')}}">
                <img src="{{ URL::asset('admin/images/price-tag.png')}}" alt="">   
                Near-Misses List</a></li>
                <li><a href="{{url('admin/all_returns')}}">
                <img src="{{ URL::asset('admin/images/reply.png')}}" alt="">   
                Returns Reports</a></li>
                <li><a href="{{url('admin/all_audits')}}">
                <img src="{{ URL::asset('admin/images/editing.png')}}" alt="">   
                Audit Reports</a></li>
                <li><a href="{{url('admin/notes_for_patients_report')}}" >
                <img src="{{ URL::asset('admin/images/price-tag.png')}}" alt="">   
                Notes For Patients List</a></li>

                <li><a href="{{url('admin/overall_view_report')}}" >
                <img src="{{ URL::asset('admin/images/list.png')}}" alt="">   
                Overall View Report</a></li>

                <li><a href="{{url('admin/patient_report')}}" >
                <img src="{{ URL::asset('admin/images/list.png')}}" alt="">   
                Patient Report</a></li>

                <li><a href="{{url('admin/due_patient_report')}}" >
                <img src="{{ URL::asset('admin/images/list.png')}}" alt="">   
                Due Date Pickup</a></li>


                <li><a href="{{url('admin/compliance_index_report')}}" >
                <img src="{{ URL::asset('admin/images/list.png')}}" alt="">   
                Compliance Index</a></li>
              </ul>
            </li> 
            <li class="sidenav">
              <h4><a href="#"><img src="{{ URL::asset('admin/images/cog.png')}}" alt=""><span>Settings</span></a><i class="fa fa-angle-right"></i></h4>
              <ul style="display: none;">
                
              <li class="page_item"><a class="nav-link" href="{{url('admin/customize_cycle_dates')}}">
                <img src="{{ URL::asset('admin/images/cog.png')}}" alt="">  
                Packing cycle settings</a></li>

                <li class="page_item"><a class="nav-link" >
                <img src="{{ URL::asset('admin/images/ambulance.png')}}" alt="">  
                Patient notifications </a></li>
              <li class="page_item"><a class="nav-link" href="{{url('admin/subscriptions')}}">
                <img src="{{ URL::asset('admin/images/cog.png')}}" alt="">  
                Membership Settings</a></li>
                <!-- <li class="page_item"><a class="nav-link" href="{{url('admin/exempted_patients')}}">
                <img src="{{ URL::asset('admin/images/disabled.png')}}" alt="">    
                Exempted Patients</a></li> -->
                




                

                  <!-- <div class="logout">
          <a href="{{url('admin/logout')}}" class="nav-link"><img src="{{ URL::asset('admin/images/exit.png')}}" alt=""><span>Logout</span></a>
        </div> -->
                <!-- <li class="page_item"><a class="nav-link" href="{{url('admin/customize_cycle_dates')}}">Customize Cycle Dates</a></li> -->
              </ul>

            </li>

            <li class="sidenav"><a class="nav-link" href="{{url('admin/search')}}"><img src="{{ URL::asset('admin/images/search.png')}}" alt=""><span>Search</span></a></li>
            <li class="sidenav"><a class="nav-link" href="{{url('admin/logs')}}">
                    <img src="{{ URL::asset('admin/images/cog.png')}}" alt=""><span> Logs</span></a></li>

                  <li class="sidenav"><a class="nav-link" href="{{url('admin/logout')}}">
                    <img src="{{ URL::asset('admin/images/exit.png')}}" alt=""><span> Logout</span></a></li>
            
           
            

            <!-- <li class="sidenav"><a class="nav-link" href="{{url('admin/profile')}}"><img src="{{ URL::asset('admin/images/people.png')}}" alt=""><span>Profile</span></a></li>
            <li class="sidenav"><a class="nav-link" href="{{url('admin/changepassword')}}"><img src="{{ URL::asset('admin/images/reports.png')}}" alt=""><span>Change Password</span></a></li> -->
          </ul>
        </div>
     
      </div>
      <div class="dashboard-side">
        