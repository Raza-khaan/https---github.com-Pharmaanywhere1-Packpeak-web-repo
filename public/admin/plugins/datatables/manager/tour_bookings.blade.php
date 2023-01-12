@extends('genral.layouts.mainlayout')
@section('title') <title>Tour Booking </title> 
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          Tour Booking 
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
                @if(isset($get_all_tours))
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Booking Id</th>
                        <th>Guest Details </th>
                        
                        <th style="width:100px;">Kids (Below 3/8)</th>
                        <th >Passengers</th>
                        <!-- <th>Arrival Flight No</th>
                        <th>Departure Flight No</th> -->
                        <th>Booking Date</th>
                        <th> Start Date</th>
                        <th> End Date</th>
                        <th>External Amount</th>
                        <th>Internal Amount</th>
                        <th>Booking Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($get_all_tours as $value)
                      
                      <tr id="row_{{$value->id}}">
                        <td>{{$value->booking_id}} </td>
                        <td>{{$value->guest_name}}<br/>{{$value->guest_email}}<br/>{{$value->guest_whatsapp}}</td>
                       
                        <td style=" text-align:center; ">{{$value->children_below_8?$value->children_below_8:'0'}}/{{$value->children_below_3?$value->children_below_3:'0'}}</td>
                        <td  style=" text-align:center; " >{{$value->passengers}}</td>
                        <!-- <td>{{$value->arrival_flight_no}} <br/>{{$value->arrival_flight_time?$value->arrival_flight_time:''}}</td>
                        <td>{{$value->departure_flight_no}}<br/>{{$value->departure_flight_time?$value->departure_flight_time:''}}</td> -->
                        <td>{{date("F j, Y",strtotime($value->created_at))}}</td>
                        <td>{{date("F j, Y",strtotime($value->booking_start_date))}}
                         <br/>{{date("g:i a",strtotime($value->tour_start_time))}} 
                        </td>
                        <td>{{date("F j, Y",strtotime($value->booking_end_date))}}</td>
                        <td>{{$value->external_tour_cost}}</td>
                        <td>{{$value->internal_tour_cost}}</td>
                       <td class="status">
                        @if($value->booking_status=='1') <span class="btn btn-primary btn-xs btn-block">Pending</span> 
                        @elseif($value->booking_status=='2') <span class="btn btn-success btn-xs btn-block">Confirm by Customer</span>
                        @elseif($value->booking_status=='3') <span class="btn btn-danger btn-xs btn-block">Canceld</span>
                        @elseif($value->booking_status=='4') <span class="btn btn-warning btn-xs btn-block">Unresponded by Customer</span>
                        @elseif($value->booking_status=='5') <span class="btn btn-success btn-xs btn-block">Complete</span>
                        @elseif($value->booking_status=='6') <span class="btn btn-warning btn-xs btn-block">Inprogress</span>
                        @else <span class="btn btn-primary btn-xs btn-block">Pending</span>
                        @endif 
                        </td>
                        <td>
                        <select  onchange="update_status(this,{{$value->id}},{{$value->external_tour_cost}})"> 
                          <option value="">--Status--</option>
                          <option @if($value->booking_status=='1') selected @endif value="1" >Pending</option>
                          <option @if($value->booking_status=='2') selected @endif value="2">Confirm by Customer</option>
                          <option @if($value->booking_status=='3') selected @endif value="3">Canceld</option>
                          <option @if($value->booking_status=='4') selected @endif value="4">Unresponded by customer</option>
                          <option @if($value->booking_status=='5') selected @endif value="5">Complete</option>
                          <option @if($value->booking_status=='6') selected @endif value="6" disabled >Inprogress</option>
                          </select><br/>
                        <a href="{{url('edit_tour_booking/'.$value->id)}}" title="edit"  ><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <!--<a href="javascript:void(0);" title="view"  ><i class="fa fa-eye text-success"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
                        <a href="javascript:void(0);" title="delete" onclick="delete_request_booking({{$value->id}});" id="delete_row_id_{{$value->id}}">
                        @if($value->delete_request=='0')
                          <i class="fa fa-trash text-danger"></i>
                        @elseif($value->delete_request=='1')
                          <i class="fa fa-trash text-info"></i>
                          <i class="fa fa-arrow-right text-info"></i>
                        @endif

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


      <!-- delete tour booking -->
      <div class="modal fade" id="delete_tour_booking" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"> Request to Admin &nbsp;&nbsp;&nbsp; <span class="alertmessage_of_request_modal"></span></h4>
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
                        <button type="button" class="btn btn-md btn-default"  data-dismiss="modal">Cancel</button>
                        
                     </div>
                 </form>
              </div>
            </div>
        </div>
      </div>



      <!-- Update Confirm  modal  -->
    <div class="modal fade" id="update_booking_confirm_modal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Tour Approve</h4>
            </div>
            <div class="modal-body">
            <form role="form"  action="javascript:void(0)" id="update_booking_confirm_form" method="POST" enctype="multipart/form-data">
                     {{ csrf_field() }}
                     <div class="box-body">
                        <input type="hidden" id="status_value">
                        <input type="hidden" id="class_">
                        <input type="hidden" id="booking_status">
                        <input type="hidden"  id="rowId" name="rowId">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="tour_amount">Total Tour Amount (INR)</label>
                                  <input type="text" value="80000" id="tour_amount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="advanced_amount">Advanced Amount (INR)</label>
                                  <input type="text" name="advanced_amount" class="form-control" id="advanced_amount" onkeypress="return restrictAlphabets(event);" >
                            </div>
                        </div>
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="comments">Comments</label>
                              <textarea class="form-control" name="comments"  style="resize:none;" rows="4" id="comments"  placeholder="comments ..."></textarea>
                           </div>
                        </div>
                        
                        <div class="box-footer">
                          <span class="pull-left alertmessage"></span>
                          <button type="submit" class="btn btn-primary next-step pull-right">Approve Tour</button>
                        </div>

                     </div>
                  </form>
            </div>
            <div class="modal-footer">
              <!-- <button class="data-dismiss">x</button> -->
            </div>

          </div>
        </div>
    </div>

    <!-- Update Other  modal  -->
    <div class="modal fade" id="update_booking_other_modal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Update Status</h4>
            </div>
            <div class="modal-body">
            <form role="form"  action="javascript:void(0)" id="update_booking_other_form" method="POST" enctype="multipart/form-data">
                     {{ csrf_field() }}
                     <div class="box-body">
                        <input type="hidden" id="status_value">
                        <input type="hidden" id="class_">
                        <input type="hidden" id="booking_status">
                        <input type="hidden"  id="rowId" name="rowId">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label for="comments">Comments</label>
                              <textarea class="form-control" name="comments" style="resize:none;"  rows="4" id="comments"  placeholder="comments ..."></textarea>
                           </div> 
                        </div>
                        
                        <div class="box-footer">
                          <span class="pull-left alertmessage"></span>
                          <button type="submit" class="btn btn-primary next-step pull-right">Submit</button>
                        </div>

                     </div>
                  </form>
            </div>
            <div class="modal-footer">
              <!-- <button class="data-dismiss">x</button> -->
            </div>

          </div>
        </div>
    </div>

@endsection


@section('customjs')
      
    
    
    
    
    
    <!-- page script -->
    <script type="text/javascript">


function  update_status(Event,rowId,Amount)
     {
        if(confirm('Do you want  to  Update status ?'))
          {


            var booking_status='';
            var class_='';
            var status_value=$(Event).val();
             switch(status_value){
                case "1":
                  booking_status = " Pending ";
                  class_='primary';
                  break;
                case "2":
                  booking_status = " Confirm by Customer ";
                  class_='success';
                  break;
                case "3":
                  booking_status = " Canceld ";
                  class_='danger';
                  break;
                case "4":
                  booking_status = " Unresponded by customer ";
                  class_='warning';
                break;
                case "5":
                  booking_status = " Complete ";
                  class_='success';
                break;
                default:
                booking_status=" Pending ";
                class_='primary';
              }
              // update_stat(rowId,status_value,class_,booking_status);
              // alert("status value=>"+status_value+" Status id =>"+rowId+" Status=>"+booking_status+" Class=>"+class_);
                if(status_value=='2')
                {
                  $('#update_booking_confirm_modal #rowId').val(rowId);
                  $('#update_booking_confirm_modal #status_value').val(status_value);
                  $('#update_booking_confirm_modal #class_').val(class_);
                  $('#update_booking_confirm_modal #booking_status').val(booking_status);
                  // $('#booking_conversation_modal #conversation').val((row_data.conversation)?row_data.conversation:'');
                  $('#tour_amount').val(Amount); 
                  $('#update_booking_confirm_modal').modal();
                }
                else 
                {
                      $('#update_booking_other_modal #rowId').val(rowId);
                      $('#update_booking_other_modal #status_value').val(status_value);
                      $('#update_booking_other_modal #class_').val(class_);
                      $('#update_booking_other_modal #booking_status').val(booking_status);
                      // $('#rental_booking_form .box-footer .alertmessage').html('');
                      // alert(booking_status);           

                      $("#update_booking_other_modal").modal(); 
                }
          }
          else
          {
            return false; 
          }
     }

     function  update_stat(rowId,status_value,class_,booking_status)
     {     //alert(rowId+' hkjd '+status_value+'----'+class_+'======='+booking_status); 
        $.ajax({ 
                  type: "POST",
                  url: "{{url('update_tour_booking_status')}}",
                  data: {'row_id':rowId,'status_value':status_value,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      console.log(result);
                      if(result=='200'){
                        console.log('#row_'+rowId+' .status')
                        // $('#row_'+rowId+' .status').html("hello  Pradeep"); 
                        $('#row_'+rowId+' .status').html('<span class="btn btn-'+class_+' btn-xs btn-block" data-row="'+rowId+'" >'+booking_status+'</span>');
                        // remove
                        $('.box-header .alertmessage').html('<span class="alert alert-success text-bold">Status '+booking_status+' updated ...</span>'); 
                         //  window.location='https://www.google.com'; 
                          
                         
                      }
                      else{ 
                        $('.box-header .alertmessage').html('<span class="alert alert-danger text-bold">Somthing event wrong!...</span>'); 
                      }
                  }
              });
     }


    //  for chnage the Driver Type  And Set  Automaticaly  Rate of the Driver 
$(document).ready(function(){

  $('#update_booking_other_form').submit(function(event){
        event.preventDefault(); 
        // console.log($('#update_booking_other_form').serialize());  exit; 
        var status_value=$('#update_booking_other_form #status_value').val(); 
        var class_=$('#update_booking_other_form #class_').val();
        var booking_status=$('#update_booking_other_form #booking_status').val();
        var rowId=$('#update_booking_other_form #rowId').val(); 
        
        
          var formdata=$('#update_booking_other_form').serialize(); 
              $.ajax({
                    type: "POST",
                    url: "{{url('update_booking_other')}}",
                    data:formdata,
                    success: function(xhr, status, data){
                      console.log(xhr)
                      console.log(xhr.success)
                       if(xhr.success){
                        $( '#update_booking_other_form form').each(function(){
                            this.reset(); 
                        }); 
                        $('#update_booking_other_form .box-footer .alertmessage').html('<span class="alert alert-success text-bold">Request Submitted...</span>');
                         setTimeout(function(){
                          $('#update_booking_other_modal').modal('hide');
                        }, 2000);
                        // alert("last  update  =>  Great!!!!! Pradeep"); 
                        update_stat(rowId,status_value,class_,booking_status); 
                      }
                      else{ 
                        $('#update_booking_other_form .box-footer .alertmessage').html('<span class="alert alert-danger text-bold">Somthing event wrong!...</span>'); 
                        } 
                  },
                  error: function(xhr, status, data){ 
                     console.log(xhr);
                    // console.log(xhr.responseJSON.errors);
                      var errorString = '<div class="text-danger text-bold">';
                      $.each(xhr.responseJSON.errors, function( key, value) { 
                        errorString += value[0]+'| ';
                      }); 
                      errorString += '</div>';
                      $('#update_booking_other_form .box-footer .alertmessage').html(errorString);
                    }
                });
       });

       $('#update_booking_confirm_form').submit(function(event){
        event.preventDefault(); 
        // console.log($('#update_booking_confirm_form').serialize());  exit; 
        var status_value=$('#update_booking_confirm_form #status_value').val(); 
        var class_=$('#update_booking_confirm_form #class_').val();
        var booking_status=$('#update_booking_confirm_form #booking_status').val();
        var rowId=$('#update_booking_confirm_form #rowId').val(); 
          var formdata=$('#update_booking_confirm_form').serialize(); 
              $.ajax({
                    type: "POST",
                    url: "{{url('update_tour_booking_approve')}}",
                    data:formdata,
                    success: function(xhr, status, data){
                      console.log(xhr)
                      console.log(xhr.success)
                       if(xhr.success){
                        $( '#update_booking_confirm_form form').each(function(){
                            this.reset(); 
                        }); 
                        $('#update_booking_confirm_form .box-footer .alertmessage').html('<span class="alert alert-success text-bold">Request Submitted...</span>');
                         setTimeout(function(){
                          $('#update_booking_confirm_modal').modal('hide');
                        }, 2000);
                        // alert("last  update  =>  Great!!!!! Pradeep"); 
                        update_stat(rowId,status_value,class_,booking_status); 
                      }
                      else{ 
                        $('#update_booking_confirm_form .box-footer .alertmessage').html('<span class="alert alert-danger text-bold">Somthing event wrong!...</span>'); 
                        } 
                  },
                  error: function(xhr, status, data){ 
                     console.log(xhr);
                    // console.log(xhr.responseJSON.errors);
                      var errorString = '<div class="text-danger text-bold">';
                      $.each(xhr.responseJSON.errors, function( key, value) { 
                        errorString += value[0]+'| ';
                      }); 
                      errorString += '</div>';
                      $('#update_booking_confirm_form .box-footer .alertmessage').html(errorString);
                    }
                });
       });

       
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
          "responsive": true,
          "ordering": false,
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": true,
          "bSort": false,
          "bInfo": true,
          "bAutoWidth": false,
          
        });
      });

       

      // function delete_request_booking(event,rowId)
      // {
      //     if(confirm('Do you want  to send  delete  Request for this ?'))
      //     {
      //         $.ajax({
      //             type: "POST",
      //             url: "{{url('delete_request_tour_booking')}}",
      //             data: {'row_id':rowId,"_token":"{{ csrf_token() }}"},
      //             success: function(result){
                     
      //                 if(result=='0'){
      //                   $(event).html('<i class="fa fa-trash text-info"></i><i class="fa fa-arrow-right text-info"></i>'); 
      //                   $('.alertmessage').html('<span class="text-success">Booking delete  Request sent ...</span>');  
      //                 }
      //                 else if(result=='1')
      //                 {
      //                   $(event).html('<i class="fa fa-trash text-danger"></i>'); 
      //                   $('.alertmessage').html('<span class="text-success">Booking delete  Request Return ...</span>');  
      //                 }
      //                 else{ $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>'); }
      //             }
      //         });
      //     }
      // }


      $('#delete_row_modal_form').submit(function(e){
         e.preventDefault(); 
         var rowId=$('#row_id').val(); 
         var delete_reason=$('#delete_reason').val(); 
         if(delete_reason)
         {
          $.ajax({
                  type: "POST",
                  url: "{{url('delete_request_tour_booking')}}",
                  data: {'row_id':rowId,'delete_reason':delete_reason,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                    console.log(result);
                    
                      if(result=='0'){
                        $('#delete_row_id_'+rowId).html('<i class="fa fa-trash text-info"></i><i class="fa fa-arrow-right text-info"></i>'); 
                        $('#delete_tour_booking').modal('hide'); 
                        displayMessage('Booking delete  Request sent ...','alertmessage','success');
                      }
                      else if(result=='1')
                      {
                        $('#delete_row_id_'+rowId).html('<i class="fa fa-trash text-danger"></i>');
                        $('#delete_tour_booking').modal('hide');  
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
              var m= $('#delete_tour_booking').modal(); 
          } 
      }


    </script>
@endsection




