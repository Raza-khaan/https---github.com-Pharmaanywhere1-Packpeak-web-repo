@extends('tenant.layouts.mainlayout')
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
        
          

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>
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
        <div class="patient-information all-logs-info" style="margin-left:17px">
        <div class="row">
          <div class="col-md-12">
          <h4>Subscription Details</h3>  
          <hr/>
          </div>
          

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong>Package Name  </strong></label> <br/>
            <p>{{$Packagename}}</p>
          </div>

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong>Admin Limit   </strong></label> <br/>
            <p>{{$Allowedadmin}}</p>
          </div>

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong> User Limit  </strong></label> <br/>
            <p>{{$Alloweduser}}</p>
          </div>

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong>Default Cycle  </strong></label> <br/>
            <p>{{$defaultcycle}} Weeks</p>
          </div>

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong>SMS Allowed  </strong></label> <br/>
            <p>{{$smsallowed}}</p>
          </div>

          <div class="col-md-2">
            <label for="company_id" style="font-size:16px">
            <strong>Subscription Date  </strong></label> <br/>
            <p>
              @if ($subscriptiondate !="")
                {{ date('d-M-Y', strtotime($subscriptiondate))}}
              @else
              
              @endif
              
            

            </p>
          </div>

        </div>
        </div>
      </div>
    </div>
    <br/>

          <form class="form-horizontal" method="POST" id="payment-form" role="form" action="{{url('paypal')}}">
                        {{ csrf_field() }}
                        
      <div class="row">
            
      <div class="col-md-3">
      <div class="patient-information all-logs-info" style="margin-left:17px">
            
            
              <h4 style="text-align:center">Available SMS <small> </small></h3>  
           <hr/>
           
           <div class="row">
            <div class="col-md-7">
            <p><strong>Used Sms: </strong> </p> 
            </div>
            <div class="col-md-5">
            {{$usedsms}}
            </div>
           </div>

           <div class="row">
            <div class="col-md-7">
            <p><strong>Total Sms:</strong></p> 
            </div>
            <div class="col-md-5">
            {{$Allowedsms}} 
            </div>
           </div>

           <div class="row">
            <div class="col-md-7">
            <p><strong>Available Limit:</strong>  </p>   
            </div>
            <div class="col-md-5">
            {{$Availablesms}}
            </div>
           </div>
           
           
           
           
           <button type="button" onclick="showsmspackages()" class="btn btn-primary btn-flat">Upgrade</button>
      </div>
      </div>
      <div class="col-md-9" id="smspackages" style="display:none">
              
            <div class="patient-information all-logs-info" >
              <div class="row">

              <div class="col-md-12">
              <h4>SMS Packages</h3>  
              </div>


              </div>
              
            
                   
            <div class="patient-info-export">
                   
                                     
                   
                    <div class="row">
                      <div class="col-md-12">
                            <hr>
                            </div>

                            <div class="col-md-2">
                            
                            <input value="SMS" name="transactiontype" hidden />
                            <input hidden value="0" id="amount" name ="amount"  readonly type="text" class="form-control"  autofocus>
                            <input hidden value="{{$websiteid}}"  id="txtwebsiteid" name="txtwebsiteid"/>
                            <input name="txtpackageid" id="txtpackageid" hidden value="0" />
                            <input  name="txtpackagename" id="txtpackagename" hidden value="" />
                            <input hidden type="number" style="width:100%"name="txttotalsms" id="txttotalsms" >
                            <input  hidden type="number" style="width:100%" name="txttotalprice" id="txttotalprice"  >
                            <label for="company_id" style="font-size:16px">
                              <strong>Package Name  </strong></label> <br/>
                              
                            <select name="ddlpackage" id="ddlpackage" class="form-control @error('patient_id') is-invalid @enderror" >
                              <option value="">{{__('Select Patient')}}</option>
                              @foreach($smsprice as $smspackages)
                                <option   
                                    data-packagename = "{{$smspackages->name}}"
                                    data-packagesms = "{{$smspackages->noofsms}}"
                                    data-packageprice = "{{$smspackages->price}}"
                                    value="{{$smspackages->id}}">{{$smspackages->name}}  
                                </option>
                              @endforeach
                            </select>


                            </div>
                            
                            <div class="col-md-2">
                            <label  style="font-size:16px" for="no_of_weeks"><strong>Total SMS</strong></label> <br/>
                            
                              <label style="font-size:24px" id="lbltotalsms"></label>
                             <br>
                          </div>

                          <div class="col-md-3">
                            <label style="font-size:16px" for="no_of_weeks">
                            <strong>Package Price (AUD) </strong></label> <br/>
                            <label style="font-size:24px"  id="lbltotalprice"></label>
                             <br>
                          </div>
                            

                          
                             <div class="col-md-1 mt-1" style="display:none" id="purhcasediv">
                                <label> <strong>Action</strong></label>
                              <button style="padding:.375rem .75rem" class="btn btn-success" >Purchase</button>
                              <!-- <button style="padding:.375rem .75rem" class="btn btn-success" onclick="purchasesms()">Purchase</button> -->
                            </div>
      
                      <!-- /.box-body -->
                    </div>
                    
                </div>
              
                  
                 </div>
            </div><!-- /.row -->
            
        </section><!-- /.content -->

      </div><!-- /.content-wrapper -->
</form>
      
<br/>

    

                   
                    
                </div>
              
                  
         
            
        </section><!-- /.content -->

      </div>/.content-wrapper -->

@endsection


@section('customjs')

<script type="text/javascript">
  $(document).ready(function()
{
  $("#lblmainheading").html("General Settings");
});
      //  For   Bootstrap  datatable 
   
      function showsmspackages()
      {
        $("#smspackages").fadeIn();
      }

      $('#ddlpackage').on('change', function (e) 
      {
        if(this.value)
        {
          var ob=$(this).children('option:selected');
          var packagename = ob.attr('data-packagename');
          var packagesms=ob.attr('data-packagesms');
          var packageprice=ob.attr('data-packageprice');
          
          $("#txtpackageid").val(this.value);
          $("#txttotalsms").val(packagesms);
          $("#txttotalprice").val(packageprice);
          $("#txtpackagename").val(packagename);
          $("#amount").val(packageprice);  

          $("#lbltotalsms").html(packagesms);
          $("#lbltotalprice").html(packageprice);

          $("#purhcasediv").fadeIn();

        }
        else
        {
          $("#txttotalsms").val("0");
          $("#txttotalprice").val("0");
          $("#txtpackageid").val("0");
          $("#txtpackagename").val("");
          
          $("#lbltotalsms").html("");
          $("#lbltotalprice").html("");

          $("#purhcasediv").fadeOut();

        }
      });

          
      function purchasesms()
      {
        var pharmacy = $("#txtwebsiteid").val();
        
        if(pharmacy =="Select Pharmacy")
        {
          alert("select pharmacy");
          $("#txtwebsiteid").focus();
          return;
        }
        else
        {
         

        var smsprice = $("#txttotalprice").val();
        var noofsmspurchase = $("#txttotalsms").val();
        var totalamount =  $("#txttotalprice").val();
       
      
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

      


    </script>
@endsection
