@extends('tenant.layouts.mainlayout')
@section('title') <title>Patient Notifications Settings</title>

@endsection

@section('content')
<style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                       

    bottom: 90;
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">


            <!-- Main content -->
            <section class="content" style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);"> 
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
            <div class="col-md-12">
              
            <input  id="txtapplogout" value="{{$applogout}}" style="display:none" /> 
            <input  id="txtdefaultcycle" value="{{$defaultcycle}}" style="display:none" /> 

              
            <div class="box">
                
                <div class="box-body pre-wrp-in table-responsive">

                  <table id="example1"   class="table">
                    <thead>

                      <tr>

                      <!-- <th></th> -->
                        <th>{{__('First Name')}}</th>
                        
                        <th>{{__('Notification Type')}}</th>
                        <th >{{__('Date')}}</th>
                        <th>{{__('Action')}}</th>
                      </tr>

                    </thead>
                    <tbody>


                    @foreach($pickups as $pickup)
                        
                      <tr >
                        <!-- <td></td> -->
                      <td>{{ $pickup->patientname }}</td>
                      
                      <td>{{ $pickup->type }}</td>
                        <td>{{ date("j/n/Y",strtotime($pickup->created_at)) }}</td>
                        
                        <td>
                        <a href="#" title="{{__('edit')}}"><i class="fa fa-edit text-info"></i></a>
                        <a href="#" title="{{__('delete')}}" ><i class="fa fa-trash text-danger"></i>&nbsp;&nbsp;
                        
                        </td>
                        


                      </tr>
                      
                      @endforeach

                    </tbody>

                  </table>



                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->

<br/> <br/>
        <div class="row" style="margin-bottom:100px">
            
            <div class="col-md-5">
            <div class="patient-information all-logs-info">
              
            <h4 >Update Information <small id="alertmessage"> </small></h3>  
            <div class="row">
              <div class="col-md-6">
                  <label>
                      <small>Mobile App Logout Time</small>
                  </label>
                  <select id="ddlmobiletime" name="ddlmobiletime" class="form-select">
                    <option value="10">10 Minutes </option>
                    <option value="20">20 Minutes </option>
                    <option value="30">30 Minutes </option>
                    <option value="40">40 Minutes </option>
                  </select>
              </div>  

              <div class="col-md-6">
                <label><small>Default Patients Pick Up Cycle</small></label>
                <input type="number" min="1" value="{{$defaultcycle}}"  style="width:97%" id="txtpatientcycle" name = "txtpatientcycle"/> 
              </div>
            </div>

            <br/>
            <div class="row">
              <div class="col-md-6">
              <button onclick="updateaccesslevel()" style="width:100%" type="button" class="btn btn-primary" >Update </button>

              </div>
              <div class="col-md-6">
              <button onclick="resetvalue()"  style="width:100%;background-color:transparent;border-color:grey" type="button" class="btn btn-light" >Reset </button>

              </div>
            </div>
</div>
</div>
</div>
      </div><!-- /.content-wrapper -->


@endsection


@section('customjs')

    <script type="text/javascript">
      //  For   Bootstrap  datatable

      $(document).ready(function(){
        $("#ddlmobiletime").select2();
        $("#lblmainheading").html("Patient Notifications  Settings");
        var applogout = $("#txtapplogout").val();
          $("#ddlmobiletime").val(applogout);
          $("#ddlmobiletime").trigger("change");
      });


      function resetvalue()
      {
        var applogout = $("#txtapplogout").val();
        var defaultcycle = $("#txtdefaultcycle").val();

        $("#ddlmobiletime").val(applogout);
        $("#ddlmobiletime").trigger("change");
        $("#txtpatientcycle").val(defaultcycle);
      }

      function updateaccesslevel()
      {
        var applogout = $("#ddlmobiletime").val();
        var defaultcycle = $("#txtpatientcycle").val();

        if(defaultcycle=="")
        {
          defaultcycle =0;
        }

        $.ajax({
        type: "POST",
        url: "{{url('update_pharmacy_tenant')}}",
        data: {'applogout':applogout,'defaultcycle':defaultcycle,"_token":"{{ csrf_token() }}"},
        success: function(result)
        {

          if(result=='200')
          {

            $('#alertmessage').html('<span class="text-success">Information updated...</span>');
            $("#alertmessage").fadeIn();
            $("#alertmessage").fadeOut(4000);
            $("#txtapplogout").val(applogout);
          $("#txtdefaultcycle").val(defaultcycle);
          }
          else
          {
            $('#alertmessage').html('<span class="text-warning">Somthing event wrong!...</span>');
          }
        }
        });

      }

    </script>
@endsection
