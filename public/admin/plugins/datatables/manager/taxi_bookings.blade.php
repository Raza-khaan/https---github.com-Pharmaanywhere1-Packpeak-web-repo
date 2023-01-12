@extends('genral.layouts.mainlayout')
@section('title') <title>Taxi Booking </title> 
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Taxi Booking
            <small>Preview</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="dashboard">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>

         <!-- Main content -->
         <section class="content"> 
         <div class="row">
          <div class="col-md-12 alert_message">
                @if(Session::has('msg'))
                {!!  Session::get("msg") !!}
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
          </div>
          <div class="row">

            <div class="col-xs-12">
              

              <div class="box">
                <div class="box-header">
                  <!-- <h3 class="box-title">All Activities Types</h3>  -->
                  <div class="pull-left alertmessage"></div>
                </div>
                <div class="box-body">
                @if(isset($get_all_taxis))
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Booking Id</th>
                        <th>Guest Details </th>
                        
                        <th>Children</th>
                        <th>Passengers</th>
                        <th>Source Address</th>
                        <th>Destination Address</th>
                        <th>Booking Date</th>
                        <th>Tour Start Date</th>
                        <th>Action</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($get_all_taxis as $value)
                      <tr id="row_{{$value->id}}">
                        <td>{{$value->booking_id}} </td>
                        <td>{{$value->guest_name}}<br/>{{$value->guest_email}}<br/>{{$value->guest_whatsapp}}</td>
                       
                        <td>{{$value->children_below}}</td>
                        <td>{{$value->passengers}}</td>
                        <td>{{$value->source_address}}</td>
                        <td>{{$value->destination_address}}</td>
                        <td>{{date("F j, Y",strtotime($value->booking_start_date))}}</td>
                        <td>{{date("F j, Y",strtotime($value->booking_start_date))}}
                         <br/>{{date("g:i a",strtotime($value->tour_start_time))}}
                        </td>
                        <td>

                        <a href="{{url('edit_taxi_booking/'.$value->id)}}" title="edit"  ><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;
                        <!--<a href="javascript:void(0);" title="view"  ><i class="fa fa-eye text-success"></i></a>&nbsp;&nbsp;-->
                        <a href="javascript:void(0);" title="delete" onclick="delete_request_booking({{$value->id}});" id="delete_row_id_{{$value->id}}">
                        @if($value->delete_request=='0')
                          <i class="fa fa-trash text-danger"></i>
                        @elseif($value->delete_request=='1')
                          <i class="fa fa-trash text-info"></i>
                          <i class="fa fa-arrow-right text-info"></i>
                        @endif
                        </a>

                        
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


  <!-- delete taxi booking -->
      <div class="modal fade" id="delete_taxi_booking" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"> Request to Admin  &nbsp;&nbsp;&nbsp; <span class="alertmessage_of_request_modal"></span></h4>
              </div>
              <div class="modal-body">
                 <form action="javascript:void(0);" id="delete_row_modal_form" method="post" class="form-group">
                      <input type="hidden" name="event" id="event" value="">
                      <input type="hidden" name="row_id" id="row_id" value="">
                     <div class="form-group">
                       <label for="delete_reason">Why you want to delete this?</label>
                       <textarea name="delete_reason" id="delete_reason" rows="4"  class="form-control" placeholder="Enter reason ...."></textarea>
                     </div>
                     <div class="form-group">
                        <button type="submit" class="btn btn-md btn-primary " >Request</button>
                        <button type="reset" class="btn btn-md btn-default"  data-dismiss="modal">Cancel</button>
                        
                     </div>
                 </form>
              </div>
            </div>
        </div>
      </div>

@endsection


@section('customjs')
      
    
    
    
    
    
    <!-- page script -->
    <script type="text/javascript">

    //  for chnage the Driver Type  And Set  Automaticaly  Rate of the Driver 
$(document).ready(function(){
    var gettype=$("input[type=radio][name='type']:checked").val();
    if(gettype=='contract')
    {
      $("input[type=radio][name='rate'][value='monthly']").prop('checked', false);
      $("input[type=radio][name='rate'][value='daily']").prop('checked', true); 
    }
    else if(gettype=='employee')
    {  
      $("input[type=radio][name='rate'][value='daily']").prop('checked', false);
      $("input[type=radio][name='rate'][value='monthly']").prop('checked', true);
    }
    else
    {
      
      $("input[type=radio][name='rate'][value='daily']").prop('checked', true);
    }
    $("input[type=radio][name='type']").change(function(){
       var gettype=this.value; 
        if(gettype=='contract')
        {
          $("input[type=radio][name='rate'][value='monthly']").prop('checked', false);
          $("input[type=radio][name='rate'][value='daily']").prop('checked', true); 
        }
        else if(gettype=='employee')
        {  
          $("input[type=radio][name='rate'][value='daily']").prop('checked', false);
          $("input[type=radio][name='rate'][value='monthly']").prop('checked', true);
        }
        else
        {
          
          $("input[type=radio][name='rate'][value='daily']").prop('checked', true);
        }
    }); 
});

      $(function () {
        $('#example1').dataTable({
          "ordering": false,
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": false,
        });
      });

      $('#delete_row_modal_form').submit(function(e){
         e.preventDefault(); 
         var rowId=$('#row_id').val(); 
         var delete_reason=$('#delete_reason').val(); 
         if(delete_reason)
         {
          $.ajax({
                  type: "POST",
                  url: "{{url('delete_request_taxi_booking')}}",
                  data: {'row_id':rowId,'delete_reason':delete_reason,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      if(result=='0'){
                        $('#delete_row_id_'+rowId).html('<i class="fa fa-trash text-info"></i><i class="fa fa-arrow-right text-info"></i>'); 
                        $('#delete_taxi_booking').modal('hide'); 
                        displayMessage('Booking delete  Request sent ...','alertmessage','success');
                      }
                      else if(result=='1')
                      {
                        $('#delete_row_id_'+rowId).html('<i class="fa fa-trash text-danger"></i>');
                        $('#delete_taxi_booking').modal('hide');  
                        displayMessage('Booking delete  Request Return ...','alertmessage','success');
                      }
                      else{  displayMessage('Somthing event wrong!...','alertmessage_of_request_modal','danger'); }
                  }
              });
         }
         else
         {  
           //displayMessage('Reason is  required...','alertmessage_of_request_modal','danger');
           $('.alertmessage_of_request_modal').html('<span class="text-danger"> Reason is  required...</span>'); 
         }
        
      }); 

      function displayMessage(message,class_name,type) {
	           $("."+class_name).html('<span class="text-'+type+'">'+message+'</span>');
             setInterval(function() { $(".text-"+type).fadeOut(); }, 4000);
         }



      function delete_request_booking(rowId)
      {
          if(rowId)
          {
              $('#row_id').val(rowId); 
              $('#delete_reason').val(""); 
              var m= $('#delete_taxi_booking').modal(); 
          } 
      }

    </script>
@endsection




