<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<style>
.page-break {
    page-break-after: always;
}
</style>
</head>
<body>
  <div id="content">
    <center>
      <h1> Pickup Report </h1>
    <table id="example1" class="table table-bordered table-striped" border="1" >
                      <thead>
                        <tr>
                          <th>Patient Name</th>
                          <th>DOB</th>
                          <th>No Of Week</th>
                          <th>Who is picking up?</th>                         
                          <th>Carer`s Name</th>
                          <th>Note From Patient</th>
                          <th>Patient signature</th>
                          <th>Pharmacy signature</th>
                          <!-- <th>Location</th> -->
                         <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        
                         @foreach($newarray as $value)
                         <tr> 
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          <td>{{$value->no_of_weeks}}</td>
                          <td>{{ucfirst($value->pick_up_by)}}</td>
                          <td>{{ucfirst($value->carer_name)}}</td>
                          <td>{{substr($value->notes_from_patient,0,30)}}</td>
                          <td><img src="{{$value->patient_sign}}" style="height:45px;  width:100px; "/></td>
                          <td><img src="{{$value->pharmacist_sign}}" style="height:45px;  width:100px; "/></td>
                          <!-- <td>{{$value->locations}}</td> -->
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                       </tr>
                       @endforeach
                      </tbody>
                      	</table>
 </center>  
  </div>
</body>
</html>
