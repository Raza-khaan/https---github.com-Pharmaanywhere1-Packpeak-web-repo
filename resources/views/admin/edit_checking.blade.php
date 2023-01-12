@extends('admin.layouts.mainlayout')
@section('title') <title>Update Checking</title>
 
  <style type="text/css">
   

   .signature-component {
  text-align: left;
  display: inline-block;
  max-width: 100%;
}
.signature-component h1 {
  margin-bottom: 0;
}
.signature-component h2 {
  margin: 0;
  font-size: 100%;
}
.signature-component button {
  padding: 1em;
  background: transparent;
  box-shadow: 2px 2px 4px #777;
  margin-top: .5em;
  border: 1px solid #777;
  font-size: 1rem;
}
.signature-component button.toggle {
  background: rgba(255, 0, 0, 0.2);
}
.signature-component canvas {
  display: block;
  position: relative;
  border: 1px solid;
}
.signature-component img {
  position: absolute;
  left: 0;
  top: 0;
}
 </style>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 
 <div class="dash-wrap">
 <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Update Checkings</h2>
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
                        <!-- <a class="dropdown-item" href="{{url('user-details/'.session('admin')['id'])}}">My
                            Profile</a>
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
              <!-- general form elements -->
           
                <form id="form"  role="form" action="{{url('admin/update_checking/'.$get_checking[0]->website_id.'/'.$get_checking[0]->id)}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="reports-breadcrum">
                    <div class="row">
                        <div class="col-md-8">
                            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}"><img src="assets/images/icon-home.png" alt="">Home</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Checkings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add Checking</li>
                            </ol>
                            </nav>
                            
                        </div>
                       <div class="col-md-4" >
                        <div class="reset-patien">
                        <a type="reset" onclick="reset()" class="btn reset-btn">Reset</a>
                            <a target="_blank" class="btn btn-md btn-primary" style="font-size:17px;color:white;padding:
                            10px 12px"
                                href="{{url('admin/patients')}}">Add Patient <i class="fa fa-plus"></i></a>
                        </div>
                       
                        </div>
                        
                        
            </div>
        </div>
        <div class="col-md-6 m-auto">
                        <div class="row">
                        <div class="update-information">
                    <div class="notes">
                        <h3 class="text-center">Update Checking</h3>
                      
                        <div class="row">

                        <div class="form-group col-md-6">
                                  <label for="company_name">Company Name <span class="text-danger"> *</span></label> <span class="loader_company"></span>
                                    @if(count($all_pharmacies)  && isset($all_pharmacies))
                                        @foreach($all_pharmacies as $row)
                                        @if($row->website_id==$get_checking[0]->website_id)
                                        <input required type="text" readonly value="{{$row->company_name}} - {{$row->name}}" class="form-control">
                                        @endif
                                        @endforeach
                                    @endif
                                  <span class="alert_company"></span>
                                </div>

                                <div class="form-group col-md-6">
                              <label for="patient_name">Patient Name <span class="text-danger"> *</span></label>
                              <select style="height:30px" required name="patient_name[]" id="patient_name" class="form-control js-example-basic-multiple"  multiple="multiple">
                                  <option value="">-- Select Patient--</option>
                                  @foreach($all_patients as $row)
                                  @php 
                                      $CompanyName=$get_checking[0]->website_id;
                                      $patientId=$row->id;
                                      $get_user=App\User::get_by_column('website_id',$CompanyName);
                                      config(['database.connections.tenant.database' => $get_user[0]->host_name]);
                                      DB::purge('tenant');
                                      DB::reconnect('tenant');

                                      $checkinglocations=App\Models\Tenant\Checking::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
                                        $Patientlocations=App\Models\Tenant\PatientLocation::where('patient_id',$row->id)->orderBy('created_at','desc')->first();
                                        if(!empty($checkinglocations) && $checkinglocations->location!=""){
                                            $location=$checkinglocations->location;
                                        }
                                        elseif(!empty($Patientlocations)){
                                            $location=$Patientlocations->locations;
                                        }
                                      DB::disconnect('tenant');
                                   @endphp 

                                   <option value="{{$row->id}}" @if($row->id==$get_checking[0]->patient_id) selected @endif 
                                   data-last_CheckingLocation="{{!empty($Patientlocations)?$Patientlocations->locations:''}}"
                                   data-last_CheckingDD="{{isset($row->latestChecking->dd)?$row->latestChecking->dd:''}}"

                                   >
                                   {{$row->first_name.' '.$row->last_name}} ( {{$row->dob?date("j/n",strtotime($row->dob)):""}} ) </option>
                                  @endforeach
                                </select>
                            </div>
                            </div>
                                
                        
                            <div class="col-md-12">
                            <div class="form-group">
                              <label for="no_of_weeks">Number of Weeks <span class="text-danger"> *</span></label>
                              <input  required type="text" class="form-control" value="{{$get_checking[0]->no_of_weeks}}" maxlength="3" onkeypress="return restrictAlphabets(event);" id="no_of_weeks"   name="no_of_weeks" placeholder="No Of Weeks">
                            </div>
                        </div> 



                        <div class="col-md-12">
                           

                            
                           

                            <div class="form-group">
                               <label for="pharmacist_signature"> Pharmacist Signature
                               <span class="text-danger">
                                        *</span>    
                            </label>
                               
                                <div  class="signature-component btn btn-group">
                                  <!-- <button type="button" id="save">Save</button> -->
                                  <button type="button" id="clear">Clear</button>
                                  <!-- <button type="button" id="showPointsToggle">Show points?</button> -->
                                </div>
                                   
                                   
                               
                            </div>

                            
                            <div class="row">
                               <div class="col-md-12">
                               <div class="form-group" >
                                  <section class="signature-component">
                                      <canvas style="width:100%" id="signature-pad"></canvas>
                                      <label id="signaturerequired" style="color:red;display:none">Draw signature*</label>
                                      <textarea  name="pharmacist_signature" id="pharmacist_signature" style="display:none;"></textarea>
                                  </section><br/>
                                  <!-- Previous signature -->
                                  <img style="display:none"  id="scream" src="{{$get_checking[0]->pharmacist_signature}}" style="border: 1px solid lightgray;width: 100%;"/>
                               </div>
                               </div>
                               
                            </div>

                        </div>

                       
                              <!-- <div class="row">
                                <div class="col-md-3">
                                    <label for="dd"></label>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" @if($get_checking[0]->dd) checked @endif name="dd" class="minimal" value="1"  />&nbsp;DD                                </label>
                                        </label>
                                    </div>
                                </div>
                                </div> -->

                             
                               
                            
                                <div class="row">
<div class="col-md-12">
<label>Location </label>
</div>
                                <div class="col-md-6">
                                    
                                    
                                    
                                        @if(isset($all_Location)  && count($all_Location))
                                            @foreach($all_Location as  $value)
                                            
                                            @if($value->id==3 && $get_checking[0]->dd ==1)
                                            <label>
                                                    <input checked id="chklocation_{{$value->id}}" type="checkbox" {{ (is_array(explode(',',$patient_location[0]->locations)) and in_array($value->id, explode(',',$patient_location[0]->locations))) ? ' checked' : '' }} name="location[]" class="minimal " value="{{$value->id}}"  />&nbsp;{{$value->name}} &nbsp;                               
                                                <label>
                                            @else
                                            <label>
                                                    <input  id="chklocation_{{$value->id}}" type="checkbox" {{ (is_array(explode(',',$patient_location[0]->locations)) and in_array($value->id, explode(',',$patient_location[0]->locations))) ? ' checked' : '' }} name="location[]" class="minimal " value="{{$value->id}}"  />&nbsp;{{$value->name}} &nbsp;                               
                                                <label>
                                            @endif
                                                
                                            
                                            @endforeach
                                        @endif
                                       
                                        
                                
                                </div>

                                <div class="col-md-2" style="">
                            <label for="dd"></label>
                            
                            <label id="ddDiv">
                                
                            @if($get_checking[0]->dd ==1)
                            <input onchange="checksafe()" checked type="checkbox" name="dd" id="dd" class="  minimal" value="1">&nbsp;DD                                </label>
                            @else
                            <input onchange="checksafe()"  type="checkbox" name="dd" id="dd" class="  minimal" value="1">&nbsp;DD                                </label>
                            @endif
                            

                            
                                </div>
                                </div>
                               

                            
                              
                             
                              <!-- textarea -->
                              <div class="form-group">
                                <label for="note">Note For Patient </label>
                                <textarea class="form-control"  style="resize: none;" rows="6" name="note" id="note"   placeholder="Note for Patient ...">{{$get_checking[0]->note_from_patient}}</textarea>
                              </div>

                              <div class="">
                                <button style="width:100%" type="submit" class="btn btn-primary">Update Checkings</button>
                              </div>

                        
                        </div>
                        </div>
                        </div>
                 </div>

                </form>
                
      



        

      </div><!-- /.content-wrapper -->



 

@endsection


@section('customjs')

<script src="{{ URL::asset('admin/plugins/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js') }}"></script>

<script src="{{ URL::asset('admin/plugins/signature/underscore-min.js') }}"></script>

    <script type="text/javascript">


function checksafe()
{
    if($('#dd').is(":checked"))
    {
        $('#chklocation_3').prop('checked', true);
    }
    else
    {
        $('#chklocation_3').prop('checked', false);
    }
    
}
function reset()
{
    location.reload();
}

$('#form').submit(function() {
    if ($.trim($("#pharmacist_signature").val()) === "") {
        // alert('you did not fill out one of the fields');
        $("#signaturerequired").fadeIn();
        return false;
    }
});


window.onload = function() {
    var c = document.getElementById("signature-pad");
  var ctx = c.getContext("2d");
  var img = document.getElementById("scream");
  ctx.drawImage(img, 10, 10);
}

      $(function () {
        //Datemask yyyy-mm-dd
        // $("#dob").inputmask("yyyy-mm-dd", {"placeholder": "yyyy-mm-dd"});
        // var pickerOptsGeneral = { format: "yyyy-mm-dd", autoclose: true, minView: 2, maxView: 2, todayHighlight: true }; //  ,startDate:  new Date()
        // $('#dob').datetimepicker(pickerOptsGeneral).on('changeDate',function(ev){}); 

        // $('#patient_name').select2({multiple: true,placeholder: "Select Patient"});
        $('#patient_name').select2({placeholder: "Select Patient"}
        ).on('change', function (e) {
              
                      if(this.value){
                            var ob=$(this).children('option:selected');
                            var last_CheckingLocation=ob.attr('data-last_CheckingLocation');
                            var last_CheckingDD=ob.attr('data-last_CheckingDD');

                      if(last_CheckingLocation){
                        let arr = last_CheckingLocation.split(',');
                        if(arr.length){
                        //   $('input[name="location[]"]').parent().removeClass("checked");
                        //   $('input[name="location[]"]').iCheck('uncheck');
                          for(var i=0; i < arr.length;  i++){
                           
                            // $('input[name="location[]"][value='+arr[i]+']').parent().addClass("checked");
                            // $('input[name="location[]"][value='+arr[i]+']').attr("checked",'checked');
                            // $('input[name="location[]"][value='+arr[i]+']').iCheck('check');
                           }
                        }
                      }
                      else{
                        // $('input[name="location[]"]').parent().removeClass("checked");
                        // $('input[name="location[]"]').removeAttr("checked");
                        // $('input[name="location[]"]').iCheck('uncheck');
                      }

                      if(last_CheckingDD=='1'){
                            // $('input[name="dd"][value=1]').parent().addClass("checked");
                            // $('input[name="dd"][value=1]').attr("checked",'checked');
                            // $('input[name="dd"][value=1]').iCheck('check');

                      }else{
                        // $('input[name="dd"]').parent().removeClass("checked");
                        // $('input[name="dd"]').removeAttr("checked");
                        $('input[name="dd"]').iCheck('uncheck');
                      }
                      
                      
                            
                            

                   }
                  
                  
                   });
      

        $('input[name="dd"]').on('ifChanged', function(event) {
            

              if($(this).prop('checked')){
                //    $('input[type=checkbox][value=3]').parent().addClass("checked");
                //    $('input[type=checkbox][value=3]').attr("checked",'checked');
                // $('input[type=checkbox][value=3]').iCheck('check');
              }
              else{
                //   $('input[type=checkbox][value=3]').parent().removeClass("checked");
                //   $('input[type=checkbox][value=3]').removeAttr("checked");
                // $('input[type=checkbox][value=3]').iCheck('uncheck');
              }
        });

         
         


      });
      
    $(document).ready(function(){
        
        $('#patient_name').select2();

        $('input[name="dd"]').on('ifChanged', function(event) {
            if($(this).prop('checked')){                
                //$('input[type=checkbox][value=3]').iCheck('check');
              }
              else{                
              //  $('input[type=checkbox][value=3]').iCheck('uncheck');
              }
        });

        $('input[type=checkbox][value=3]').on('ifChanged', function(event) {
            if($(this).prop('checked')){                
               // $('input[name="dd"]').iCheck('check');
              }
              else{                
                //$('input[name="dd"]').iCheck('uncheck');
              }
        });

        
      });


      //     restrict Alphabets  
      function restrictAlphabets(e){
      var x=e.which||e.keycode; 
      if((x>=48 && x<=57) )
      return true;
      else
      return false;
     }

      $('#patient_name').click(function(){
           if($('#company_name').val()==false){
                $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>'); 
                $('select[id="company_name"]').css('border','1px solid red');
           } 
      });
      $('#dob').click(function(){
           if($('#company_name').val()==false){
                $('.alert_company').html('<span class="text-danger">Please select a valid company . </span>'); 
                $('select[id="company_name"]').css('border','1px solid red');
           } 
        });

       /* get All Patient  List  By  Website id */
       $('#company_name').click(function(){
           if($(this).val()){
              
              $.ajax({
                  type: "POST",
                  url: "{{url('admin/get_patients_by_website_id')}}",
                  data: {'website_id':$(this).val(),"_token":"{{ csrf_token() }}"},
                  beforeSend: function() {
                    $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
                  },
                  success: function(result){
                    // console.log(result);
                    $('.loader_company').html('');
                    $('.alert_company').html(''); 
                    $('select[id="company_name"]').css('border','none');
                    $('#patient_name').html(result);
                  }
              });
           } 
        });
    /* get  Patienst  Dote of  birth by Patient  id and Website id  */
    



    </script>

    <script id="INLINE_PEN_JS_ID">
   /*!
 * Modified
 * Signature Pad v1.5.3
 * https://github.com/szimek/signature_pad
 *
 * Copyright 2016 Szymon Nowak
 * Released under the MIT license
 */
var SignaturePad = (function(document) {
    "use strict";

    var log = console.log.bind(console);

    var SignaturePad = function(canvas, options) {
        var self = this,
            opts = options || {};

        this.velocityFilterWeight = opts.velocityFilterWeight || 0.7;
        this.minWidth = opts.minWidth || 0.5;
        this.maxWidth = opts.maxWidth || 2.5;
        this.dotSize = opts.dotSize || function() {
                return (self.minWidth + self.maxWidth) / 2;
            };
        this.penColor = opts.penColor || "black";
        this.backgroundColor = opts.backgroundColor || "rgba(0,0,0,0)";
        this.throttle = opts.throttle || 0;
        this.throttleOptions = {
            leading: true,
            trailing: true
        };
        this.minPointDistance = opts.minPointDistance || 0;
        this.onEnd = opts.onEnd;
        this.onBegin = opts.onBegin;

        this._canvas = canvas;
        this._ctx = canvas.getContext("2d");
        this._ctx.lineCap = 'round';
        this.clear();

        // we need add these inline so they are available to unbind while still having
        //  access to 'self' we could use _.bind but it's not worth adding a dependency
        this._handleMouseDown = function(event) {
            if (event.which === 1) {
                self._mouseButtonDown = true;
                self._strokeBegin(event);
            }
        };

        var _handleMouseMove = function(event) {
           event.preventDefault();
            if (self._mouseButtonDown) {
                self._strokeUpdate(event);
                if (self.arePointsDisplayed) {
                    var point = self._createPoint(event);
                    self._drawMark(point.x, point.y, 5);
                }
            }
        };

        this._handleMouseMove = _.throttle(_handleMouseMove, self.throttle, self.throttleOptions);
        //this._handleMouseMove = _handleMouseMove;

        this._handleMouseUp = function(event) {
            if (event.which === 1 && self._mouseButtonDown) {
                self._mouseButtonDown = false;
                self._strokeEnd(event);
                document.getElementById('pharmacist_signature').value = signaturePad.toDataURL();
            }
        };

        this._handleTouchStart = function(event) {
            if (event.targetTouches.length == 1) {
                var touch = event.changedTouches[0];
                self._strokeBegin(touch);
            }
        };

        var _handleTouchMove = function(event) {
            // Prevent scrolling.
            event.preventDefault();

            var touch = event.targetTouches[0];
            self._strokeUpdate(touch);
            if (self.arePointsDisplayed) {
                var point = self._createPoint(touch);
                self._drawMark(point.x, point.y, 5);
            }
        };
        this._handleTouchMove = _.throttle(_handleTouchMove, self.throttle, self.throttleOptions);
        //this._handleTouchMove = _handleTouchMove;

        this._handleTouchEnd = function(event) {
            var wasCanvasTouched = event.target === self._canvas;
            if (wasCanvasTouched) {
                event.preventDefault();
                self._strokeEnd(event);
            }
        };

        this._handleMouseEvents();
        this._handleTouchEvents();
    };

    SignaturePad.prototype.clear = function() {
        var ctx = this._ctx,
            canvas = this._canvas;

        ctx.fillStyle = this.backgroundColor;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        this._reset();
    };

    SignaturePad.prototype.showPointsToggle = function() {
        this.arePointsDisplayed = !this.arePointsDisplayed;
    };

    SignaturePad.prototype.toDataURL = function(imageType, quality) {
        var canvas = this._canvas;
        return canvas.toDataURL.apply(canvas, arguments);
    };

    SignaturePad.prototype.fromDataURL = function(dataUrl) {
        var self = this,
            image = new Image(),
            ratio = window.devicePixelRatio || 1,
            width = this._canvas.width / ratio,
            height = this._canvas.height / ratio;

        this._reset();
        image.src = dataUrl;
        image.onload = function() {
            self._ctx.drawImage(image, 0, 0, width, height);
        };
        this._isEmpty = false;
    };

    SignaturePad.prototype._strokeUpdate = function(event) {
        var point = this._createPoint(event);
        if(this._isPointToBeUsed(point)){
            this._addPoint(point);
        }
    };

    var pointsSkippedFromBeingAdded = 0;
    SignaturePad.prototype._isPointToBeUsed = function(point) {
        // Simplifying, De-noise
        if(!this.minPointDistance)
            return true;

        var points = this.points;
        if(points && points.length){
            var lastPoint = points[points.length-1];
            if(point.distanceTo(lastPoint) < this.minPointDistance){
                // log(++pointsSkippedFromBeingAdded);
                return false;
            }
        }
        return true;
    };

    SignaturePad.prototype._strokeBegin = function(event) {
        this._reset();
        this._strokeUpdate(event);
        if (typeof this.onBegin === 'function') {
            this.onBegin(event);
        }
    };

    SignaturePad.prototype._strokeDraw = function(point) {
        var ctx = this._ctx,
            dotSize = typeof(this.dotSize) === 'function' ? this.dotSize() : this.dotSize;

        ctx.beginPath();
        this._drawPoint(point.x, point.y, dotSize);
        ctx.closePath();
        ctx.fill();
    };

    SignaturePad.prototype._strokeEnd = function(event) {
        var canDrawCurve = this.points.length > 2,
            point = this.points[0];

        if (!canDrawCurve && point) {
            this._strokeDraw(point);
        }
        if (typeof this.onEnd === 'function') {
            this.onEnd(event);
        }
    };

    SignaturePad.prototype._handleMouseEvents = function() {
        this._mouseButtonDown = false;

        this._canvas.addEventListener("mousedown", this._handleMouseDown);
        this._canvas.addEventListener("mousemove", this._handleMouseMove);
        document.addEventListener("mouseup", this._handleMouseUp);
    };

    SignaturePad.prototype._handleTouchEvents = function() {
        // Pass touch events to canvas element on mobile IE11 and Edge.
        this._canvas.style.msTouchAction = 'none';
        this._canvas.style.touchAction = 'none';

        this._canvas.addEventListener("touchstart", this._handleTouchStart);
        this._canvas.addEventListener("touchmove", this._handleTouchMove);
        this._canvas.addEventListener("touchend", this._handleTouchEnd);
    };

    SignaturePad.prototype.on = function() {
        this._handleMouseEvents();
        this._handleTouchEvents();
    };

    SignaturePad.prototype.off = function() {
        this._canvas.removeEventListener("mousedown", this._handleMouseDown);
        this._canvas.removeEventListener("mousemove", this._handleMouseMove);
        document.removeEventListener("mouseup", this._handleMouseUp);

        this._canvas.removeEventListener("touchstart", this._handleTouchStart);
        this._canvas.removeEventListener("touchmove", this._handleTouchMove);
        this._canvas.removeEventListener("touchend", this._handleTouchEnd);
    };

    SignaturePad.prototype.isEmpty = function() {
        return this._isEmpty;
    };

    SignaturePad.prototype._reset = function() {
        this.points = [];
        this._lastVelocity = 0;
        this._lastWidth = (this.minWidth + this.maxWidth) / 2;
        this._isEmpty = true;
        this._ctx.fillStyle = this.penColor;
    };

    SignaturePad.prototype._createPoint = function(event) {
        var rect = this._canvas.getBoundingClientRect();
        return new Point(
            event.clientX - rect.left,
            event.clientY - rect.top
        );
    };

    SignaturePad.prototype._addPoint = function(point) {
        var points = this.points,
            c2, c3,
            curve, tmp;

        points.push(point);

        if (points.length > 2) {
            // To reduce the initial lag make it work with 3 points
            // by copying the first point to the beginning.
            if (points.length === 3) points.unshift(points[0]);

            tmp = this._calculateCurveControlPoints(points[0], points[1], points[2]);
            c2 = tmp.c2;
            tmp = this._calculateCurveControlPoints(points[1], points[2], points[3]);
            c3 = tmp.c1;
            curve = new Bezier(points[1], c2, c3, points[2]);
            this._addCurve(curve);

            // Remove the first element from the list,
            // so that we always have no more than 4 points in points array.
            points.shift();
        }
    };

    SignaturePad.prototype._calculateCurveControlPoints = function(s1, s2, s3) {
        var dx1 = s1.x - s2.x,
            dy1 = s1.y - s2.y,
            dx2 = s2.x - s3.x,
            dy2 = s2.y - s3.y,

            m1 = {
                x: (s1.x + s2.x) / 2.0,
                y: (s1.y + s2.y) / 2.0
            },
            m2 = {
                x: (s2.x + s3.x) / 2.0,
                y: (s2.y + s3.y) / 2.0
            },

            l1 = Math.sqrt(1.0 * dx1 * dx1 + dy1 * dy1),
            l2 = Math.sqrt(1.0 * dx2 * dx2 + dy2 * dy2),

            dxm = (m1.x - m2.x),
            dym = (m1.y - m2.y),

            k = l2 / (l1 + l2),
            cm = {
                x: m2.x + dxm * k,
                y: m2.y + dym * k
            },

            tx = s2.x - cm.x,
            ty = s2.y - cm.y;

        return {
            c1: new Point(m1.x + tx, m1.y + ty),
            c2: new Point(m2.x + tx, m2.y + ty)
        };
    };

    SignaturePad.prototype._addCurve = function(curve) {
        var startPoint = curve.startPoint,
            endPoint = curve.endPoint,
            velocity, newWidth;

        velocity = endPoint.velocityFrom(startPoint);
        velocity = this.velocityFilterWeight * velocity +
            (1 - this.velocityFilterWeight) * this._lastVelocity;

        newWidth = this._strokeWidth(velocity);
        this._drawCurve(curve, this._lastWidth, newWidth);

        this._lastVelocity = velocity;
        this._lastWidth = newWidth;
    };

    SignaturePad.prototype._drawPoint = function(x, y, size) {
        var ctx = this._ctx;

        ctx.moveTo(x, y);
        ctx.arc(x, y, size, 0, 2 * Math.PI, false);
        this._isEmpty = false;
    };

    SignaturePad.prototype._drawMark = function(x, y, size) {
        var ctx = this._ctx;

        ctx.save();
        ctx.moveTo(x, y);
        ctx.arc(x, y, size, 0, 2 * Math.PI, false);
        ctx.fillStyle = 'rgba(255, 0, 0, 0.2)';
        ctx.fill();
        ctx.restore();
    };

    SignaturePad.prototype._drawCurve = function(curve, startWidth, endWidth) {
        var ctx = this._ctx,
            widthDelta = endWidth - startWidth,
            drawSteps, width, i, t, tt, ttt, u, uu, uuu, x, y;

        drawSteps = Math.floor(curve.length());
        ctx.beginPath();
        for (i = 0; i < drawSteps; i++) {
            // Calculate the Bezier (x, y) coordinate for this step.
            t = i / drawSteps;
            tt = t * t;
            ttt = tt * t;
            u = 1 - t;
            uu = u * u;
            uuu = uu * u;

            x = uuu * curve.startPoint.x;
            x += 3 * uu * t * curve.control1.x;
            x += 3 * u * tt * curve.control2.x;
            x += ttt * curve.endPoint.x;

            y = uuu * curve.startPoint.y;
            y += 3 * uu * t * curve.control1.y;
            y += 3 * u * tt * curve.control2.y;
            y += ttt * curve.endPoint.y;

            width = startWidth + ttt * widthDelta;
            this._drawPoint(x, y, width);
        }
        ctx.closePath();
        ctx.fill();
    };

    SignaturePad.prototype._strokeWidth = function(velocity) {
        return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
    };

    var Point = function(x, y, time) {
        this.x = x;
        this.y = y;
        this.time = time || new Date().getTime();
    };

    Point.prototype.velocityFrom = function(start) {
        return (this.time !== start.time) ? this.distanceTo(start) / (this.time - start.time) : 1;
    };

    Point.prototype.distanceTo = function(start) {
        return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
    };

    var Bezier = function(startPoint, control1, control2, endPoint) {
        this.startPoint = startPoint;
        this.control1 = control1;
        this.control2 = control2;
        this.endPoint = endPoint;
    };

    // Returns approximated length.
    Bezier.prototype.length = function() {
        var steps = 10,
            length = 0,
            i, t, cx, cy, px, py, xdiff, ydiff;

        for (i = 0; i <= steps; i++) {
            t = i / steps;
            cx = this._point(t, this.startPoint.x, this.control1.x, this.control2.x, this.endPoint.x);
            cy = this._point(t, this.startPoint.y, this.control1.y, this.control2.y, this.endPoint.y);
            if (i > 0) {
                xdiff = cx - px;
                ydiff = cy - py;
                length += Math.sqrt(xdiff * xdiff + ydiff * ydiff);
            }
            px = cx;
            py = cy;
        }
        return length;
    };

    Bezier.prototype._point = function(t, start, c1, c2, end) {
        return start * (1.0 - t) * (1.0 - t) * (1.0 - t) +
            3.0 * c1 * (1.0 - t) * (1.0 - t) * t +
            3.0 * c2 * (1.0 - t) * t * t +
            end * t * t * t;
    };

    return SignaturePad;
})(document);

var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
    backgroundColor: 'rgba(255, 255, 255, 0)',
    penColor: 'rgb(0, 0, 0)',
    velocityFilterWeight: .7,
    minWidth: 0.5,
    maxWidth: 2.5,
    throttle: 16, // max x milli seconds on event update, OBS! this introduces lag for event update
    minPointDistance: 3,
});
// var saveButton = document.getElementById('save'),
 var clearButton = document.getElementById('clear');
    // showPointsToggle = document.getElementById('showPointsToggle');

// saveButton.addEventListener('click', function(event) {
//     var data = signaturePad.toDataURL('image/png');
//     window.open(data);
// });
clearButton.addEventListener('click', function(event) {
    signaturePad.clear();
    document.getElementById('pharmacist_signature').value = "";
});
// showPointsToggle.addEventListener('click', function(event) { 
//     signaturePad.showPointsToggle();
//     showPointsToggle.classList.toggle('toggle');
// });


  </script>

@endsection
