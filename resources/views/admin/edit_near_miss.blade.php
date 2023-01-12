@extends('admin.layouts.mainlayout')
@section('title') <title>Near Miss</title>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="dash-wrap">
 <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Update  Near Miss</h2>
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
        <h2>Update Near Miss</h2>
    </div>

      
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
             
                <form role="form" action="{{url('admin/update_near_miss/'.$near_miss[0]->website_id.'/'.$near_miss[0]->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}


                <nav class="dash-breadcrumb" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="#">Near Miss</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Near Miss</li>
            </ol>
        </nav>
                  <div class="row">

                        <div class="col-md-6 m-auto">
                        <div class="update-information">
                    <div class="notes">
                    <h3 class="text-center">Update Near Miss</h3>

                    <div class="row">
                            <div class="col-md-12">
                          <div class="form-group">
                            <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
                                @if(count($all_pharmacies)  && isset($all_pharmacies))
                                @foreach($all_pharmacies as $row)
                                    @if($row->website_id==$near_miss[0]->website_id)
                                    <input type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
                                    @endif
                                @endforeach
                                @endif
                            <span class="alert_company"></span>
                          </div>
                            
  <label> Error Type </label>
                          <div class="col-md-12">
                                    <div class="row">
                                    <div class="col-md-4 check-label">
                                    <input class="minimal" type="checkbox" @if($near_miss[0]->missed_tablet=='1') checked @endif  name="missed_tablet" class="minimal" value="missed_tablet"  />
                                    &nbsp;Missed tablet                                </label>
                                    </div>
                                    <div class="col-md-4 check-label">
                                    <input class="minimal" type="checkbox" @if($near_miss[0]->extra_tablet=='1') checked @endif name="extra_tablet" class="minimal" value="extra_tablet"  />&nbsp;Extra tablet
                                    </div>
                               
                                    <div class="col-md-4 check-label">
                                    <input class="minimal" type="checkbox" @if($near_miss[0]->wrong_tablet=='1') checked @endif name="wrong_tablet" class="minimal" value="wrong_tablet"  />&nbsp;Wrong tablet
                                    </div>
                                    </div>
                          </div>

                          <div class="col-md-12 mt-2">
                                    <div class="row">
                                    <div class="col-md-4 check-label">
                               
                               
                                    <input type="checkbox" @if($near_miss[0]->wrong_day=='1') checked @endif name="wrong_day" class="minimal" value="wrong_day"  />&nbsp;Wrong day
                             
</div>
<div class="col-md-4 check-label">
                                
                                    <input type="checkbox" id="other_checkbox" @if($near_miss[0]->other!="") checked @endif name="other_checkbox" class="minimal"   />&nbsp;other
                              
                                </div>
                            </div>
                            </div>
                           </div>

                            <div class="form-group other_field">
                              @if($near_miss[0]->other!="")
                                <label for="other">Other? <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" minlength="3"  value="{{$near_miss[0]->other}}"  name="other" id="other" placeholder="other" >
                              @endif
                             </div>

                             <div class="col-md-12">
                            <div class="form-group">
                              <label for="person_involved">Person involved <span class="text-danger"> *</span></label><span class="loader_patient"></span>
                              <input type="text" name="person_involved"  value="{{$near_miss[0]->person_involved}}"  id="person_involved" class="form-control" style="text-transform: capitalize;">
                            </div>
</div>
                            <!-- textarea -->
                            <div class="col-md-12">
                              <div class="form-group">
                                <label for="Initials">Initials <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control"  style="resize: none;" rows="4" name="initials" id="initials" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123)"  placeholder="Initials." value="{{$near_miss[0]->initials}}"/>
                              </div>
                              </div>
                             
                                <div class="col-md-12">
                                  <div class="form-group">
                                      <button style="width:100%" type="submit" class="btn btn-primary">Update Near Miss</button>
                                  </div>
                                </div>
                               
                             
                             </div>
                             </div>
                             </div>
                             </div>
                        </div>

                       

                 </div>

                </form>
             







      </div><!-- /.content-wrapper -->





@endsection


@section('customjs')


    <script type="text/javascript">



      $(function () {
        //Datemask yyyy-mm-dd
        $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){});


        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
          checkboxClass: 'icheckbox_minimal-red',
          radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass: 'iradio_flat-green'
        });

        $('#other_checkbox').on('ifChanged', function(event){
        //alert($(this).val());
        $(this).on('ifChecked', function(event){
            // alert("checked");
            $('.other_field').html('<label for="other">Other? <span class="text-danger"> *</span></label>\
                                <input type="text" class="form-control" minlength="3"  name="other" id="other"\ placeholder="other" >');
        });
        $(this).on('ifUnchecked', function(event){
          // alert("Unchecked");
          $('.other_field').html('');
        });

      });

 $("#initials").on("keyup", function(){
        $(this).val(($(this).val()).toUpperCase());
      });



      });

    // $(document).ready(function(){});




      //     restrict Alphabets
      function restrictAlphabets(e){
          var x=e.which||e.keycode;
          if((x>=48 && x<=57) || x==8 ||
            (x>=35 && x<=40)|| x==46)
            return true;
          else
            return false;
      }


    </script>
@endsection
