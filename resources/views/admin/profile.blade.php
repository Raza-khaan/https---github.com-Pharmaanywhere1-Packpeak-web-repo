@extends('admin.layouts.mainlayout')
@section('title') <title>Profile</title>

<style type="text/css">

.update-information .reset-btn {
    border: 1px solid #707070;
    padding: 6px 50px !important;
    background-color: transparent;
    display: block;
    text-align: center;
    color: #707070;
    font-size: 18px;
    line-height: 26px;
    font-family: 'Product-Sans-Medium';
    width: 57% !important;
    float: right;
}

::placeholder {
  background-color:transparent !important;

}

</style>
<style>
.dt-buttons button {
    background: rgb(192, 229, 248) !important;
    border-color: rgb(255, 255, 255) !important;
    color: blue;
    font-weight: italic;
    color: #1f89bb;

    /* right: -1062%;
    bot    tom: 90; */
}

.btn-group,
.btn-group-vertical {
    flex-direction: column !important;
}
</style>
@endsection
@section('content')
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Profile</h2>
        <a class="small-logo-mobile" href="#"><img src="{{ URL::asset('admin/images/mobile-logo.png')}}" alt=""></a>
        <div class="user-menu">

            <div class="profile">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img src="{{ URL::asset('admin/images/user.png')}}" alt="">
                        <span>
                            @if(!empty(session('admin')['name']))
                            {{session('admin')['name']}}
                            @endif
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                       
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
        <h2>Add Checkings</h2>
    </div>
    <form class="report-form" id="form" role="form" action="{{url('admin/save_checking')}}" method="post"
        enctype="multipart/form-data">
        {{ csrf_field() }}
       


        <!-- general form elements -->
        <div class="col-md-6 m-auto">
            <div class="row">
                <div class="update-information" style="width:100%;margin-top:35px;border-radius:50px">
                    <div class="card">
                    

                    <div  class="card-header" style="color:white;background-color:#001833">
                     <h5>Profile  Information</h5>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="profile">
                                <div class="btn-group">
                                <img src="http://127.0.0.1:8000/admin/images/user.png" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="reset-patien" id="edit">
                                <a type="reset" onclick="editinformation()" class="btn reset-btn">Edit</a>
                            </div>
                        </div>
        
                        <div class="form-group col-md-12" style="margin-top:30px">
                                <label>Email<span>*</span></label>
                                <input style="background-color:#e9ecef"  disabled readonly type="email" id="txtemail" value="{{$email}}" required="required" name="email" class="form-control" placeholder="Enter Email">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Name<span>*</span> <small id="lblnameerror" style="color:red;display:none">Enter Name</small></label>
                                <input type="email" id="txtname"  style="background-color:#e9ecef"
                                value="{{$name}}" required="required" name="email" class="form-control" placeholder="Enter Name">
                            </div>

                            <div class="col-4 col-md-3">
                                <label for="inputState">Phone Number</label>
                            </div>
                            <div class="col-8 col-md-9">
                            <small id="lblphoneerror" style="color:red;display:none">Enter Number</small>
                            </div>
                            <div class="form-group col-4 col-md-3">
                                <select style="background-color:#e9ecef" disabled onchange="updatephonenumberlength()" id="inputState" class="form-control">
                                @if($code == null)
                                <option value="0">(..)</option>    
                                <option selected value="1">+61</option>
                                @elseif($code == "+61")
                                <option value="0">(..)</option>    
                                <option selected value="1">+61</option>
                                @else
                                <option selected value="0">(..)</option>    
                                <option value="1">+61</option>
                                @endif
                                
                                </select>
                            </div>

                            <div class="form-group col-8 col-md-9">
                            
                            @if($code == null)
                            <input style="background-color:#e9ecef" disabled  minlength="9" maxlength="9" id="phone" type="text" required="required" name="phone" class="form-control" onkeypress="return restrictAlphabets(event);" placeholder="Enter number here">
                                @elseif($code == "+61")
                                <input value="{{$number}}"  style="background-color:#e9ecef" disabled minlength="9" maxlength="9" id="phone" type="text" required="required" name="phone" class="form-control" onkeypress="return restrictAlphabets(event);" placeholder="Enter number here">
                                @else
                                <input  value="{{$number}}" style="background-color:#e9ecef" disabled minlength="10" maxlength="10" id="phone" type="text" required="required" name="phone" class="form-control" onkeypress="return restrictAlphabets(event);" placeholder="Enter number here">
                                @endif
                                
                            </div>
                    </div>
                  
                        
                        <div class="col-md-12">
                            

                        <div class="row" id="savechanges" style="display:none">
                            <div class="form-group col-md-12 text-center" >
                                <button onclick="savechanges()" type="button" style="width:50%;margin:0 auto"  class="btn update-btn">Save Changes</button>
                            </div>
                           
                        </div>

                            <div class="alert alert-success" role="alert" style="display:none" id="divsuccess">
                            Profile Information Updated
                            </div>
                    </div>
</div>


                    

                </div>

            </div>
        </div>
</div>
</form>
</div>
</div><!-- /.box-header -->
</div><!-- /.box -->



@endsection


@section('customjs')

<script
    src="{{ URL::asset('admin/plugins/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js') }}">
</script>

<script src="{{ URL::asset('admin/plugins/signature/underscore-min.js') }}"></script>


<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.0.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/jquery-ui.min.js"></script>
<link rel="Stylesheet" type="text/css" href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.9.2/themes/blitzer/jquery-ui.css" />


<script type="text/javascript">

function editinformation()
{
    $("#edit").fadeOut();
    $("#savechanges").fadeIn();
    $('#txtname').removeAttr("disabled");
    $('#phone').removeAttr("disabled");
    $('#inputState').removeAttr("disabled");

    $("#txtname").css("background-color","#F5F6FA");
    $("#phone").css("background-color","#F5F6FA");
    $("#inputState").css("background-color","#F5F6FA");
}

function savechanges()
{   
    var email = $("#txtemail").val();
    var name = $("#txtname").val();
    var phone = $("#phone").val();
    var inputState = $("#inputState").val();

    if(name=="")
    {
        $("#lblnameerror").fadeIn();
        return;
    }
    else
    {
        $("#lblnameerror").fadeOut();
    }


    if(phone=="")
    {
        $("#lblphoneerror").fadeIn();
        $("#lblphoneerror").html("Enter number");
        return;
    }
    else
    {
        if(inputState ==1)
        {
            if(phone.length <9)
            {
                $("#lblphoneerror").fadeIn();
                $("#lblphoneerror").html("Enter 9 digits phone number");
                return;
            }
            else
            {
                $("#lblphoneerror").fadeOut();
            }
        }
        else  if(inputState == 0)
        {
            if(phone.length <10)
            {
                $("#lblphoneerror").fadeIn();
                $("#lblphoneerror").html("Enter 10 digits phone number");
                return;
            }
            else
            {
                $("#lblphoneerror").fadeOut();
            }
        }
       
    }

    
            $.ajax({
            type: "POST",
            url: "{{url('admin/update_profile')}}",
            data: {'email':email,'name':name,'code':inputState,'phone':phone,"_token":"{{ csrf_token() }}"},
            success: function(result){
                
                $("#divsuccess").fadeIn();
                $("#divsuccess").fadeOut(5000);
            }
            });


    $("#savechanges").fadeOut();
    $("#edit").fadeIn();
    $('#txtname').attr("disabled");
    $('#phone').attr("disabled");
    $('#inputState').attr("disabled");

    $("#txtname").css("background-color","#e9ecef");
    $("#phone").css("background-color","#e9ecef");
    $("#inputState").css("background-color","#e9ecef");

}
function updatephonenumberlength()
    {
        var inputState = $("#inputState").val();
        if(inputState ==0)
        {
            $("#phone").attr('minlength','10');
            $("#phone").attr('maxlength','10');
        }

        if(inputState >  0 )
        {
            alert(inputState.length);
            
            $("#phone").attr('minlength','9');
            $("#phone").attr('maxlength','9');
        }
        
    }


</script>

@endsection