@extends('admin.layouts.mainlayout')
@section('title') <title>General Settings</title>
@endsection

<style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                       
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>
@section('content')
 <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->
        


        <div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>General Settings</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="user-menu">
              
            <div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="{{ URL::asset('admin/images/user.png')}}" alt=""> 
                      <span>
                      @if(!empty(session('admin')['name']))
                        {{session('admin')['name']}}  
                      @endif
                      </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a> -->
                      <a class="dropdown-item" href="{{url('admin/profile')}}">Profile</a>
                      <a class="dropdown-item" href="{{url('admin/changepassword')}}">Change Password</a>
                      <a class="dropdown-item" href="{{url('admin/logout')}}">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">General Settings</li>
              </ol>
            </nav>

          </div>
       



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
          <div class="row">
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    <div class="preview-record">
                      <h3>General Settings</h3>  
                                     
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                            <h6>No of Cycles</h6>
                            <hr>
                            </div>

                            <div class="col-md-2">
                            @if(isset($all_pharmacy))
                            <label for="company_id">Pharmacy: </label> <br/>
                            <select  id="company_id" name="company_id">
                                <option>Select Pharmacy</option>
                                  @foreach($all_pharmacy as $pharmacy)
                                  <option value="{{ $pharmacy->id }}">
                                      {{ $pharmacy->company_name }} 
                                  </option>
                                  @endforeach
                            </select>

                            @endif    
                            

                            </div>
                            
                            <div class="col-md-4">
                            <label for="no_of_weeks">Enter Number of Weeks to Pack Cycles in the field</label> <br/>
                            <input type="number" style="width:100%" name="no_of_weeks" id="no_of_weeks" maxlength="2" placeholder="Cycles">
                             <br>
                          </div>
                            

                          <div class="col-md-4">
                          <label for="no_of_weeks">Number of days for early reminder settings  </label> <br/>
                              <select id="ddlreminderdays" value="ddlreminderdays">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                              </select>
                             
                          </div>
                          
                             <div class="col-md-3 mt-3">
                             <button class="btn btn-primary" style="min-width:250px;padding:5px;font-size: large;" 
                             onclick="changeCycle()">Save</button>
</div>
      
                      <!-- /.box-body -->
                    </div>
                    
                </div>
              
                  
                 </div>
            </div><!-- /.row -->
            
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->




      <div class="row">
            <div class="col-md-12">
              <div class="patient-information all-logs-info">
                  <div class="patient-info-export">
                    <div class="preview-record">
                      <h3>SMS Packages</h3>  
                                     
                    </div>
                    <div class="row">
                     
                  <input hidden id="txtpackageid1" name = "txtpackageid1"  value="{{$smsdetails[0]->id }}" />
                            <div class="col-md-2">
                            
                            <label for="company_id">Package Name </label> <br/>
                              <input value="{{$smsdetails[0]->name}}"  id="txtpackagename1" name ="txtpackagename1" style="width:100%"  />
                            </div>
                            
                            <div class="col-md-2">
                            <label for="no_of_weeks">Total SMS</label> <br/>
                            <input value="{{$smsdetails[0]->noofsms}}"
                             type="number" style="width:100%" name="totalsms1" id="totalsms1"  >
                             <br>
                          </div>

                          <div class="col-md-2">
                            <label for="no_of_weeks">Package Price (AUD)</label> <br/>
                            <input
                            value="{{$smsdetails[0]->	price}}"
                            type="number" style="width:100%" name="txtprice1" id="txtprice1" maxlength="2" >
                             <br>
                          </div>
                            

                          
                             <div class="col-md-1 mt-1">
                              <label style="visibility:hidden">Action </label>
                             <button class="btn btn-primary" 
                             onclick="updatesms(1)">Update</button>
</div>
      
                      <!-- /.box-body -->
                    </div>

                    <br/>
                    <div class="row">
                     
                     <input hidden id="txtpackageid2" name = "txtpackageid2"  value="{{$smsdetails[1]->id }}" />
                               <div class="col-md-2">
                               
                               <label for="company_id">Package Name </label> <br/>
                                 <input value="{{$smsdetails[2]->name}}"  id="txtpackagename2" name ="txtpackagename2" style="width:100%"  />
                               </div>
                               
                               <div class="col-md-2">
                               <label for="no_of_weeks">Total SMS</label> <br/>
                               <input value="{{$smsdetails[1]->noofsms}}"
                                type="number" style="width:100%" name="totalsms2" id="totalsms2"  >
                                <br>
                             </div>
   
                             <div class="col-md-2">
                               <label for="no_of_weeks">Package Price (AUD)</label> <br/>
                               <input
                               value="{{$smsdetails[1]->	price}}"
                               type="number" style="width:100%" name="txtprice2" id="txtprice2" maxlength="2" >
                                <br>
                             </div>
                               
   
                             
                                <div class="col-md-1 mt-1">
                                 <label style="visibility:hidden">Action </label>
                                <button class="btn btn-primary" 
                                onclick="updatesms(2)">Update</button>
   </div>
         
                         <!-- /.box-body -->
                       </div>

             <br/>
             <div class="row">
                     
                     <input  hidden id="txtpackageid3" name = "txtpackageid3"  value="{{$smsdetails[2]->id }}" />
                               <div class="col-md-2">
                               
                               <label for="company_id">Package Name </label> <br/>
                                 <input value="{{$smsdetails[2]->name}}"  id="txtpackagename3" name ="txtpackagename3" style="width:100%"  />
                               </div>
                               
                               <div class="col-md-2">
                               <label for="no_of_weeks">Total SMS</label> <br/>
                               <input value="{{$smsdetails[2]->noofsms}}"
                                type="number" style="width:100%" name="totalsms3" id="totalsms3"  >
                                <br>
                             </div>
   
                             <div class="col-md-2">
                               <label for="no_of_weeks">Package Price (AUD)</label> <br/>
                               <input
                               value="{{$smsdetails[2]->	price}}"
                               type="number" style="width:100%" name="txtprice3" id="txtprice3" maxlength="2" >
                                <br>
                             </div>
                               
   
                             
                                <div class="col-md-1 mt-1">
                                 <label style="visibility:hidden">Action </label>
                                <button class="btn btn-primary" 
                                onclick="updatesms(3)">Update</button>
   </div>
         
                         <!-- /.box-body -->
                       </div>
                    
                </div>
              
                  
                 </div>
            </div><!-- /.row -->
            
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->
<br/>

    
                    </div>
                    
                </div>
              
                  
                 </div>
            </div><!-- /.row -->
            
        </section><!-- /.content -->

      </div>/.content-wrapper -->

@endsection


@section('customjs')

<script type="text/javascript">
      //  For   Bootstrap  datatable 
    function calculateprice()
    {
      var smsprice = $("#txtsmsprice").val();
      var noofsmspurchase = $("#txtsmspurchase").val();
      var totalamount =  smsprice * noofsmspurchase;
      
      $("#txttotalamount").val(totalamount);
    }

    
      $(function () {

        $('#example1').DataTable( {
          lengthChange: true,
          language: {
               // search: '<i class="fa fa-search"></i>',
                searchPlaceholder: "search",
               },

          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
          columnDefs: [ {
            orderable: false,
            sorting: false,
            className: 'select-checkbox',
            targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            dom: '<"top"if>Brt<"bottom"p>l',
            // dom: 'f<>Brtpl',
            buttons: [
               
                {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print',
                    'pageLength','colvis'
                ]
                },
            ],
             //select: true,
        });
        
      });

       
      function purchasesms()
      {
        var pharmacy = $("#website_id").val();
        
        if(pharmacy =="Select Pharmacy")
        {
          alert("select pharmacy");
          $("#website_id").focus();
          return;
        }
        else
        {
         

        var smsprice = $("#txtsmsprice").val();
        var noofsmspurchase = $("#txtsmspurchase").val();
        var totalamount =  $("#txttotalamount").val();
       
      
                  $.ajax({
                      type: "POST",
                      url: "{{url('admin/update_pharmacy_sms')}}",
                      data: {'websiteid':pharmacy,'smsprice':smsprice,'noofsmspurchase':noofsmspurchase,'totalamount':totalamount,"_token":"{{ csrf_token() }}"},
                      success: function(result){
                          
                          if(result=='200'){

                            $('.alertmessage').html('<span class="text-success">Cycles updated...</span>');
                            location.reload();

                          }
                          else{
                            $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>');
                            }
                      }
                  });
              
        
        
      }
    }

    function updatesms(id)
    {
      var packageid;
      var packagename;
      var packagesms;
      var packageprice;

      packageid =id;

      if(id==1)
      {
        packagename = $("#txtpackagename1").val();
        packagesms =  $("#totalsms1").val();
        packageprice = $("#txtprice1").val();
      }
      else if(id==2)
      {
        packagename = $("#txtpackagename2").val();
        packagesms =  $("#totalsms2").val();
        packageprice = $("#txtprice2").val();
      }
      else if(id==3)
      {
        packagename = $("#txtpackagename3").val();
        packagesms =  $("#totalsms3").val();
        packageprice = $("#txtprice3").val();
      }

     

      $.ajax({
                      type: "POST",
                      url: "{{url('admin/update_package_sms')}}",
                      data: {'id':id,'packagename':packagename,'packagesms':packagesms,'packageprice':packageprice,"_token":"{{ csrf_token() }}"},
                      success: function(result){
                          
                          if(result=='200'){

                            $('.alertmessage').html('<span class="text-success">Package updated...</span>');
                            location.reload();

                          }
                          else{
                            $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>');
                            }
                      }
                  });

      
    }
      function changeCycle()
      {
        
        var companyID = document.getElementById("company_id").value;
        var noOfWeeks = document.getElementById("no_of_weeks").value;
        var reminderdays = $("#ddlreminderdays").val();
        if(companyID != '')
        {
             
              if(confirm('Do you want to change no of cycles?'))
              {
                  $.ajax({
                      type: "POST",
                      url: "{{url('admin/customize_cycle_dates')}}",
                      // url: "{{url('admin/customize_cycle_dates')}}",
                      data: {'companyID':companyID,'reminderdays':reminderdays,'noOfWeeks':noOfWeeks,"_token":"{{ csrf_token() }}"},
                      success: function(result){
                          console.log(result);
                          
                          if(result=='200'){

                            $('.alertmessage').html('<span class="text-success">Cycles updated...</span>');
                          location.reload();

                          }
                          else{
                            $('.alertmessage').html('<span class="text-success">Somthing event wrong!...</span>');
                            }
                      }
                  });
              }
        }
      }


    </script>
@endsection
