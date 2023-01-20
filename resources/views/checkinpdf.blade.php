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
      <h1> Checkins Report </h1>
    <table id="example1" class="table table-bordered table-striped" border="1">
                      <thead>
                        <tr>
                          <th>Patient Name</th>
                          <th>DOB</th>
                          <th>No Of Week</th>                          
                          <th>Note From Patient</th>
                          <th>Location</th>
                         <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        
                         @foreach($newarray as $value)
                         <tr> 
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          <td>{{$value->no_of_weeks}}</td>
                          <td>{{substr($value->notes_from_patient,0,30)}}</td>
                          <td>{{$value->locations}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                       </tr>
                       @endforeach
                      </tbody>
                      	</table>
 </center>  
  </div>
</body>
</html>
