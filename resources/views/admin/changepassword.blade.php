@extends('admin.layouts.mainlayout')
@section('title') <title>Change Password</title>

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
        <h2>Change Password</h2>
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
        <h2>Change Password</h2>
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
                     <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                    <div class="row">
                        
        
                        <div class="form-group col-md-12" >
                                <label>Current Password<span>*</span> <small id="lblcurrent" style="color:red;display:none">Enter Current password</small></label>
                                <input style="background-color:#e9ecef"   type="password" id="txtcurrentpassword"  required="required" name="email" class="form-control" >
                            </div>
                            <input style="background-color:#e9ecef;display:none"  disabled readonly type="email" id="txtemail" value="{{$email}}" required="required" name="email" class="form-control" placeholder="Enter Email">
                            <div class="form-group col-md-12">
                                <label>New Password<span>*</span> 
                                <small id="lblnew" style="color:red;display:none">Enter New password</small>
                            
                                <small id="lblpasswordlimit" style="color:red;display:none">Must be 8 characters</small></label>
                                <input type="password" id="txtnewpassword"  style="background-color:#e9ecef"
                                 required="required" name="email" class="form-control" >
                            </div>

                            <div class="form-group col-md-12">
                                <label>Confirm New Password<span>*</span> 
                                <span id='message'></span>
                                <small id="lblconfirm" style="color:red;display:none">Enter Confirm Password</small></label>
                                <input type="password" id="txtconfirmpassword"  style="background-color:#e9ecef"
                               required="required" name="email" class="form-control" >
                            <label>* Password must be at least 8 characters</label>
                            </div>

                        
                    </div>
                  
                        
                        <div class="col-md-12">
                            

                        <div class="row" id="savechanges" >
                            <div class="form-group col-md-12 text-center" >
                                <button  id="btnupdatepassword"onclick="savechanges()" type="button" style="width:50%;margin:0 auto" 
                                 class="btn btn-secondary">Change Password</button>
                            </div>
                           
                        </div>

                            <div class="alert alert-success" role="alert" style="display:none" id="divsuccess">
                            Password Information Updated
                            </div>

                            <div class="alert alert-danger" role="alert" style="display:none" id="divfailure">
                            Invalid Current Password 
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




$('#txtnewpassword, #txtconfirmpassword').on('keyup', function () {
            if ($('#txtnewpassword').val() == $('#txtconfirmpassword').val()) {
                $('#message').html('Matching').css('color', 'white');
                document.getElementById("btnupdatepassword").disabled = false;
            } else {
                $('#message').html('Not Matching').css('color', 'red');
                document.getElementById("btnupdatepassword").disabled = true;
            }
        });
function savechanges()
{   
    var currentpassword = $("#txtcurrentpassword").val();
    var newpassword = $("#txtnewpassword").val();
    var confirmnewpassword = $("#txtconfirmpassword").val();
   var email =  $("#txtemail").val();


    if(currentpassword=="")
    {
        $("#lblcurrent").fadeIn();
        return;
    }
    else
    {
        $("#lblcurrent").fadeOut();
    }


    if(newpassword=="")
    {
        $("#lblnew").fadeIn();
        return;
    }
    else
    {
        
        $("#lblnew").fadeOut();
    }


    if(newpassword.length <8)
    {
        $("#lblpasswordlimit").fadeIn();
        return;
    }
    else
    {
        
        $("#lblpasswordlimit").fadeOut();
    }

    if(confirmnewpassword=="")
    {
        $("#lblconfirm").fadeIn();
        return;
    }
    else
    {
        
        $("#lblconfirm").fadeOut();
    }


    
            $.ajax({
            type: "POST",
            url: "{{url('admin/update_password')}}",
            data: {'email':email,'currentpassword':currentpassword,'newpassword':newpassword,"_token":"{{ csrf_token() }}"},
            success: function(result){
                  
                if(result ==1)
                {
                    $("#divfailure").fadeOut();
                    $("#divsuccess").fadeIn();
                    $("#divsuccess").fadeOut(5000);

                    $("#txtcurrentpassword").val("");
                    $("#txtnewpassword").val("");
                    $("#txtconfirmpassword").val("");
                }
                else
                {

                    $("#divfailure").fadeIn();
                    
                    
                }
               
            }
            });


}



</script>

@endsection