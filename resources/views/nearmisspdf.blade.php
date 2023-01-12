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
      <h1> Near Miss Report </h1>
    <table id="example1" class="table table-bordered table-striped" border="1" >
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Person Involved </th>
                          <th>Initials</th>
                          <th>Missed tablet</th>
                          <th>Extra tablet</th>                         
                          <th>Wrong tablet </th>
                          <th>Wrong day</th>
                          <th>Other</th>
                         
                        </tr>
                      </thead>
                      <tbody>                      
                        
                         @foreach($newarray as $value)
                         <tr> 
                          <td>{{date("j/n/Y, h:i A",strtotime($value->created_at))}}</td>
                         <td>{{$value->person_involved}}</td>
                         <td>{{$value->initials}}</td>
                          <td>{{$value->missed_tablet}}</td>
                          <td>{{ucfirst($value->extra_tablet)}}</td>
                          <td>{{ucfirst($value->wrong_tablet )}}</td>
                          <td>{{ucfirst($value->wrong_day)}}</td>
                          <td>{{$value->other}}</td>
                          
                       </tr>
                       @endforeach
                      </tbody>
                      	</table>
 </center>  
  </div>
</body>
</html>
