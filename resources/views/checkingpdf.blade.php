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
   
   <center>   <h1> Checking Report </h1></center>
    <table id="example1" class="table table-bordered table-striped" border="1" >
                      <thead>
                        <tr>
                          <th>Patient Name</th>
                          <th>No Of Week</th>
                          <th>Pharmacist signature</th>
                          <th>Note From Patient</th>
                                        
                        
                        
                          
                         <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>                      
                        
                         @foreach($newarray as $value)
                         <tr> 
                          <td>{{$value->patients->first_name.' '.$value->patients->last_name}}</td>

                          
                          <!-- <td>{{$value->patient_id }}</td> -->

                          <td>{{$value->no_of_weeks}}</td>
                          <td><img src="{{$value->pharmacist_signature}}" style="height:45px;  width:100px; "/></td>
                       
                          <td>{{substr($value->note_from_patient,0,30)}}</td>
                       
                        
                         
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                       </tr>
                       @endforeach
                      </tbody>
                      	</table>
 
  </div>
</body>
</html>
