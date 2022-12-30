@extends('admin.layouts.mainlayout')
@section('title') <title>Exempted Patients Report</title>
@endsection
@section('content')

<style>

  #example1_info
  {
    display:none;
  }
</style>
 <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->
        


        <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>Exempted Patients Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="assets/images/user.png" alt=""> <span>Amir Eid</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a> -->
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

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Exempted Patient Record</li>
              </ol>
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
            </div><!-- /.box-header -->
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    <div class="preview-record">
                      
                      @if(isset($all_patients))
                      <select id="epatient_id">
                          <option>Select Patient</option>
                            @foreach($all_patients as $patient)
                            <option value="{{ $patient->website_id }}|{{ $patient->id }}">
                                {{ $patient->pharmacy }} - {{ $patient->first_name }}
                            </option>
                            @endforeach
                      </select>
                      <button class="btn btn-primary" onclick="exemptPatient()">Add Patient</button>    
                      @endif                
                    </div>
                    
                </div>
            <div class="search-logs" style="margin-top:10px">
            <div class="table-responsive">
              <div class="col-md-12">
                  @if(isset($new_patients))
                    <table id="example1" class="table" style="width:100%">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Pharmacy</th>
                          <th>Date Time</th>
                          <th>Patient Name</th>
                          <th>DOB</th>
                           
                          <th>Mobile Number</th>
                          <th>Action</th>
 
                        </tr>
                      </thead>
                      <tbody>
                      @foreach($new_patients as $value)
                        @php
                        $m=explode(',',$value->locations);
                        $locations=App\Models\Admin\Location::select('name')->whereIn('id', $m)->get();
                        @endphp
                        <tr id="row_{{$value->id}}" >
                           <td></td>
                          <td>{{ucfirst($value->pharmacy)}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                           
                          <td>{{$value->phone_number}}</td>
                         <td>
                           <a href="javascript:void(0);" title="Delete" onclick="remove_exemption_record('{{$value["website_id"]}}','{{$value["id"]}}');"  ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        </td>
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

  
 /*delete by Ajax */
 function remove_exemption_record(website_id,rowId)
      {
        var patientInfo = rowId;
        if(patientInfo != '')
        {
            // const myArr = patientInfo.split("|");
            // var company = myArr[0];
            // var patientID = myArr[1];
            
              if(confirm('Do you want  to remove patient exemption?'))
              {
                  $.ajax({
                      type: "POST",
                      url: "{{url('admin/add_exempted_patient')}}",
                      data: {'isexemption':0,'company':website_id,patientID:patientInfo,"_token":"{{ csrf_token() }}"},
                      success: function(result){

                          if(result=='1'){

                            $('.alertmessage').html('<span class="text-success">Patient exempted...</span>');
                            location.reload();

                          }
                          else{
                            $('.alertmessage').html('<span class="text-success">Patient exemption removed!...</span>');
                            location.reload();
                            }
                      }
                  });
              }
        }
      }
      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').DataTable( {
          lengthChange: true,
          language: {
               // search: '<i class="fa fa-search"></i>',
                searchPlaceholder: "search",
               },

          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
          columnDefs: [ {
            orderable: false,
            sorting: false,
            className: 'select-checkbox',
            targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            dom: '<"top"if>Brt<"bottom"p>l',
            // dom: 'f<>Brtpl',
            buttons: [
               
                {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print',
                    'pageLength','colvis'
                ]
                },
            ],
             //select: true,
        });
        
      });

       

      function exemptPatient()
      {
        var patientInfo = document.getElementById("epatient_id").value;
        if(patientInfo != '')
        {
            const myArr = patientInfo.split("|");
            var company = myArr[0];
            var patientID = myArr[1];
            console.log(patientInfo);
              if(confirm('Do you want  to exempt this patient?'))
              {
                  $.ajax({
                      type: "POST",
                      url: "{{url('admin/add_exempted_patient')}}",
                      data: {'isexemption':1,'company':company,patientID:patientID,"_token":"{{ csrf_token() }}"},
                      success: function(result){
                          console.log(result);
                          
                        if(result=='1'){

                        $('.alertmessage').html('<span class="text-success">Patient exempted...</span>');
                        location.reload();

                        }
                        else{
                        $('.alertmessage').html('<span class="text-success">Patient exemption removed!...</span>');
                        location.reload();  
                      }
                      }
                  });
              }
        }
      }


    </script>
@endsection
