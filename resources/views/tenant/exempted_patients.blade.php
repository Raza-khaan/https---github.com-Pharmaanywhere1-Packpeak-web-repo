@extends('tenant.layouts.mainlayout')
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
        <div >
          

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
                    <div class="row">

                    <div class="col-md-10">
                   
                      <select id="epatient_id">
                          <option>Select Patient </option>
                            @foreach($all_patients as $patient)
                            <option value="{{ $patient->website_id }}|{{ $patient->id }}">
                                {{ $patient->pharmacy }} - {{ $patient->first_name }}
                            </option>
                            @endforeach
                      </select>
                         <small id="lblpatienterror" style="display:none;color:red">please select patient *</small>
                     
                    </div>
                    <div class="col-md-2">
                    <button class="btn btn-primary" onclick="exemptPatient()">Add Patient</button> 
                    </div>
                    </div>
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
                      url: "{{url('add_exempted_patient')}}",
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
     

       

      function exemptPatient()
      {
        var patientInfo = document.getElementById("epatient_id").value;
        
        if(patientInfo != 'Select Patient')
        {
          $("#lblpatienterror").fadeOut();
            const myArr = patientInfo.split("|");
            var company = myArr[0];
            var patientID = myArr[1];
            console.log(patientInfo);
              if(confirm('Do you want  to exempt this patient?'))
              {
                  $.ajax({
                      type: "POST",
                      url: "{{url('add_exempted_patient')}}",
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
        
        else
        {
          
          $("#lblpatienterror").fadeIn();
          $("#epatient_id").focus();
          return;
        }
      }
      

      $(document).ready(function(){
      $("#epatient_id").select2();
      $("#lblmainheading").html("Exempted Patients");
      });

    </script>
@endsection
