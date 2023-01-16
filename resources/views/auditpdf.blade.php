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
      <h1> Audit Report </h1>
    <table id="example1" class="table table-bordered table-striped" border="1" >
                      <thead>
                        <tr>
                          <th>Patient Name</th>
                          <th>DOB</th>
                          <th>No Of Week</th>
                          <th>Store</th>                         
                          <th>Other store</th>
                          <th>Staff Initials</th>
                         <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        
                         @foreach($newarray as $value)
                         <tr> 
                          <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</a></td>
                          <td>{{date("j/n/Y",strtotime($value->dob))}}</td>
                          <td>{{$value->no_of_weeks}}</td>
                          <td>{{ucfirst($value->store)}}</td>
                          <td>{{ucfirst($value->store_others_desc)}}</td>
                          <td>{{$value->staff_initials}}</td>
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                       </tr>
                       @endforeach
                      </tbody>
                      	</table>
 </center>  
  </div>
</body>
</html>
