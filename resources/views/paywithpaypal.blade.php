<html>
<head>
	<meta charset="utf-8">
	<title>Paypal</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

  <style>
     body{
      background-color: #E0E0E0;
 }
 .card-pricing.popular {
                z-index: 1;
                border: 1px solid #007bff;
            }
            .card-pricing .list-unstyled li {
                padding: .5rem 0;
                color: #6c757d;
                font-weight: 300;
            }

            .btn{
                border-radius: 1px;
                font-weight:300;
            }
            .hvr:hover{

                color: #fff;
                background-color: #007bff;
                border: 1px solid #007bff !important;
            } 

    </style>
</head>
<body>

<form class="form-horizontal" method="POST" id="payment-form" role="form" action="{!! URL::route('paypal') !!}" >
                        {{ csrf_field() }}
                        <input id="website_id" name="txtwebsiteid" hidden value="{{$website_id}}" />
<input id="row_id" name="row_id" hidden value="{{$row_id}}" />
<input id="subscription_id" name="subscription_id" hidden value="{{$subscription_id}}" />
<input hidden value ="{{$Host_Name}}" id="company" name ="company" 
                                  type="text" class="form-control" 
                                  autofocus>
                                  <input hidden value ="{{$Host_Name}}" id="company" name ="company" 
                                  type="text" class="form-control" 
                                  autofocus>
                                  <input hidden value="{{$package}}" id="package" name = "package" disabled readonly type="text" class="form-control"   autofocus>


<input hidden value="{{$Amount}}" id="amount" name ="amount"  readonly type="text" class="form-control"  autofocus>
<input hidden value="0" id="packageid" name ="packageid"  readonly type="text" class="form-control"  autofocus>
<input value="Subscription" name="transactiontype" hidden />
<div class="container mt-5"  >
                <div  id="packagedetails" class="card card-pricing text-center px-3 mb-4 col-md-4" style="margin:auto;">
                    <span class="h6 w-60 mx-auto px-4 py-1 rounded-bottom bg-primary text-white shadow-sm">Paywith Paypal</span>
                    <div class="bg-transparent card-header pt-4 border-0">
                        <h1 class="h1 font-weight-normal text-primary text-center mb-0" data-pricing-value="15">$<span class="price">{{$Amount}}</span><span class="h6 text-muted ml-2">/ per month</span></h1>
                    </div>
                    <div class="card-body pt-0">
                        <ul class="list-unstyled mb-4">
                            <li><strong>Company: </strong> {{$Host_Name}}</li>
                            <li><strong>Package: </strong> {{$package}}</li>
                            <li><strong>Up to : </strong> {{$Admin}} Admin</li>
                            <li><strong>Up to : </strong>{{$users}} users</li>
                            <li><strong>Up to : </strong>{{$SMS}} sms allow</li>
                            
                          
                        </ul>
                        <button type="submit" class="btn btn-primary">
                                    Paywith Paypal
                                </button>
                    </div>
                </div>
                
            </div>
            </form> 

	
</body>
</html>