@extends('genral.layouts.mainlayout')
@section('title') <title>Tour length</title>
 <style>
  .dropzone {
    min-height: 150px;
    border: 2px dotted rgba(0, 0, 0, 0.3);
    background: white;
    padding: 20px 59px;
}


 .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}


 </style>
@endsection



@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Add Tour length
            <small>Preview</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="dashboard">Forms</a></li>
            <li class="active">General Elements</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content"    style="    min-height: 160px;" >
          <div class="row">
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
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header">
                <form role="form" action="{{url('add_tour_length')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="name">Days</label>
                              <input type="text" class="form-control"  maxlength="3" onkeyup="set_night_value()" onkeypress="return restrictAlphabets(event);"  required id="no_of_day" name="no_of_day" placeholder="day..">
                            </div>
                        </div>

                        <div class="col-md-4">
                             <div class="form-group">
                               <label for="email">Night</label>
                               <input type="text" class="form-control" maxlength="3"  onkeyup="set_day_value()" onkeypress="return restrictAlphabets(event);" required id="no_of_night" name="no_of_night" placeholder="night..">
                              </div>
                        </div>
                        <div class="col-md-4" style="padding-top:24px; ">
                           <button type="submit" class="btn btn-primary ">Add length</button>
                        </div>
                        
                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->


          </div>   <!-- /.row -->
        </section><!-- /.content -->



         <!-- Main content -->
         <section class="content">
          <div class="row">
            <div class="col-xs-12">


              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">All length List</h3> <div class="pull-right alertmessage"></div>
                </div><!-- /.box-header -->
                <div class="box-body">
                @if(isset($all_tour_length))
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                       
                        <th>Number Of  Days</th>
                       
                        <th>Number Of Night</th>
                       
                        <!-- <th style="width: 60px;" >Action</th> -->
                      </tr>
                    </thead>
                    <tbody>
                     @foreach($all_tour_length as $value)
                      <tr id="row_{{$value->id}}">
                        <td>{{$value->no_of_day}}</td>
                        <td>{{$value->no_of_night}}</td>
                         <td>
                        <a href="javascript:void(0);" title="delete" onclick="delete_tour_length({{$value->id}});" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        
                        <!--<a href="{{url('edit_tour_length/'.$value->id)}}" title="edit"><i class="fa fa-edit text-success"></i></a>-->
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



 
      <!-- Modal -->
    <div class="modal fade" id="my_map_Modal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Select Address</h4>
            </div>
            <form action="{{url('booking')}}"  method="post" >
            {{ csrf_field() }}
              <div class="modal-body" style="padding:0px; " >
                <input type="hidden"  name="event_date"  id="event_date" />
                <div id="myMap" style="height:435px;  width:100%;     position: static; "></div>
                <input id="map_address" type="text" style="width:600px; display:none; "/><br/>
                <input type="hidden" id="latitude" placeholder="Latitude"/>
                <input type="hidden" id="longitude" placeholder="Longitude"/>
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
            </div>
            </form>
          </div>
        </div>
      </div>

@endsection


@section('customjs')


    <script type="text/javascript">




       

   
     

   
   


      //     restrict Alphabets  
      function restrictAlphabets(e){
          var x=e.which||e.keycode; 
          if((x>=48 && x<=57) || x==8 ||
            (x>=35 && x<=40)|| x==46)
            return true;
          else
            return false;
      }

        function set_night_value()
        {
            var days_val=$("#no_of_day").val(); 
           
           if(days_val){$("#no_of_night").val(parseInt(days_val)-1)}
        }
        function set_day_value()
        {
            var night_val=$("#no_of_night").val(); 
           
           if(night_val){$("#no_of_day").val(parseInt(night_val)+1)}
        }
      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').dataTable({
          "ordering": false,
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": true,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
      });


      function delete_tour_length(rowId)
      {
          if(confirm('Do you want  to  delete this?'))
          {
              $.ajax({
                  type: "POST",
                  url: "{{url('delete_tour_length')}}",
                  data: {'row_id':rowId,"_token":"{{ csrf_token() }}"},
                  success: function(result){
                      //console.log(result);
                      if(result=='200'){
                        $('#row_'+rowId).remove();
                        $('.alertmessage').html('<span class="text-success">Tour Length deleted...</span>');
                      }
                      else{ $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>'); }
                  }
              });
          }
      }

    </script>
@endsection
