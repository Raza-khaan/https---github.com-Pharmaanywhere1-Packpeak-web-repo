@extends('tenant.layouts.mainlayout')
@section('title') <title>Checking</title>

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
 <div class="content-wrapper">
      

 <div class="dashborad-header">
 	<div class="pharma-add report-add">
            <a href="{{url('/')}}/checkings" class="active family">Add New Checking</a>
            <a href="{{url('/')}}/checkings_report" class="family">Checking Report</a>
         
           
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
              <div class="box box-primary">
                <div class="box-header pre-wrp">
                <form role="form" id="addCheckings" action="{{url('save_checking')}}" method="post" enctype="multipart/form-data">

                <div class="reports-breadcrum">

<nav class="dash-breadcrumb" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('dashboard')}}"><img src="assets/images/icon-home.png"
                    alt="">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Forms</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add New Checkings</li>
    </ol>
</nav>
<a class="btn btn-md btn-dark" 
                                style="color:white;font-size:17px;text-align:center;float:right"  href="{{url('patients')}}">
                                <i class="fa fa-plus"> Add Patient</i></a>

</div>


                {{ csrf_field() }}
                <div class="report-forms">
                <div class="row">
                
            
                        <div class="col-md-6">
                        <div class="patient-information">
                            <div class="row">
                            <div class="col-md-8">
                            <h3>Checking Information</h3> 
                            </div>

                        </div>
                        
                        <div class="row">

                     
                            <div class="form-group col-md-6">
                              <label for="name">{{__('Patient Name')}}<span style="color:red">*</span></label>
                              <select  required data-placeholder="Select  Patient" name="patient_id" id="patient" class="form-control js-example-basic-multiple"  multiple="multiple">
                                <option value="">-- Select  Patient --</option>
                                @foreach($patients as $patient)

                                 @php
                                        $checkinglocations=App\Models\Tenant\Checking::where('patient_id',$patient->id)->orderBy('created_at','desc')->first();
                                        $Patientlocations=App\Models\Tenant\Patient::where('id',$patient->id)->orderBy('created_at','desc')->first();
                                        $PLocations=App\Models\Tenant\PatientLocation::where('patient_id',$patient->id)->orderBy('created_at','desc')->first();
                                        if(!empty($checkinglocations) && $checkinglocations->location!=""){
                                            $location=$checkinglocations->location;
                                        }
                                        elseif(!empty($Patientlocations) && $Patientlocations->location!=NULL){
                                            $location=$Patientlocations->location;
                                        }

                                 @endphp
                                  <option {{old('patient_id')==$patient->id?'selected':''}}
                                  data-dob="{{$patient->dob}}" data-lastPickupDate="{{$patient->latestPickups?$patient->latestPickups->created_at:''}}"
                                  data-lastPickupWeek="{{$patient->latestPickups?$patient->latestPickups->no_of_weeks:''}}"
                                  data-last_CheckingLocation="{{isset($PLocations->locations)?$PLocations->locations:''}}"
                                   data-last_CheckingDD="{{isset($patient->latestChecking->dd)?$patient->latestChecking->dd:''}}"
                                   value="{{$patient->id}}">{{$patient->first_name.' '.$patient->last_name}} ( {{$patient->dob?date("j/n",strtotime($patient->dob)):""}} ) </option>
                                @endforeach
                              </select>
                                @error('patient_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-6">
                              <label for="no_of_weeks">{{__('Number of Weeks')}}<span style="color:red">*</span></label>
                              <input required type="text"  value="{{old('no_of_weeks')}}" class="form-control @error('no_of_weeks') is-invalid @enderror" maxlength="3" onkeypress="return restrictAlphabets(event);" id="no_of_weeks" name="no_of_weeks" placeholder="No Of Weeks">
                                @error('no_of_weeks')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                            </div>


                            <div class="form-group col-md-12">
                               <label for="pharmacist_signature"  id="pharmacistSignDiv"> {{__('Pharmacist Signature')}}<span style="color:red">*</span> 
                                <button class="btn btn-secondary" type="button" id="clear">{{__('Clear')}}</button> </label>
                                        <br/>
                            
                                    <section class="signature-component">
                                        <canvas id="signature-pad"></canvas>
                                        <textarea name="pharmacist_signature"  class="@error('no_of_weeks') is-invalid @enderror" id="pharmacist_signature" style="display:none;"></textarea>
                                    </section>
                                    @error('pharmacist_signature')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                

                                <div class="signature-component btn btn-group">
                                  <!-- <button type="button" id="save">{{__('Save')}}</button> -->
                                 
                                  <!-- <button type="button" id="showPointsToggle">{{__('Show points?')}}</button> -->
                                </div>
                                   <div >{{__('Draw Your Signature')}}

                                   <small id="lblsignatureerrow" style="display:none"></small>
                                   </div>
                               
                            

                            

                            </div>
                            </div>

                        </div>
                        </div>



                        <div class="col-md-6">
                         <div class="patient-information">
                              <div class="row">
                                <div class="col-md-2" >
                                    <label for="dd"></label>
                                    <div class="form-group">
                                        <label id="ddDiv">
                                            <input onchange="checksafe()" type="checkbox"  {{old('dd')?'checked':''}} name="dd" id="dd" class=" @error('dd') is-invalid @enderror minimal" value="1"  />&nbsp;DD                                </label>
                                        </label>
                                    </div>
                                    @error('dd')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-9">
                                  <label>{{__('Location')}} </label>
                                  <div class="form-group">
                                    @foreach($locations as $location)
                                      <label>
                                      @if ($loop->first)
                                      <input id="chklocation_{{$location->id}}" checked  type="checkbox" {{ (is_array(old('location')) and in_array($location->id, old('location'))) ? ' checked' : '' }}  name="location[]" class="minimal @error('location[]') is-invalid @enderror" value="{{$location->id}}"  readonly="readonly"/>&nbsp;{{$location->name}}</label>
                                      @else
                                      <input id="chklocation_{{$location->id}}"   type="checkbox" {{ (is_array(old('location')) and in_array($location->id, old('location'))) ? ' checked' : '' }}  name="location[]" class="minimal @error('location[]') is-invalid @enderror" value="{{$location->id}}"  readonly="readonly"/>&nbsp;{{$location->name}}</label>
                                      @endif
                                          
                                      <label>
                                    @endforeach
                                    @error('location[]')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                                  </div>
                                </div>

                              </div>

                              <!-- textarea -->
                              <div class="form-group">
                                <label for="note">{{__('Notes For Patient')}}</label>
                                <textarea class="form-control @error('note') is-invalid @enderror"  style="resize: none;" name="note" id="note"   placeholder="Note for Patient ...">{{old('note')}}</textarea>
                                @error('note')
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>

                              <div class="">
                                <button type="submit" class="btn btn-primary" id="add_checking">{{__('Add Checkings')}}</button>
                                 <button type="button" onclick="Resetvalues()" class="btn btn-default" id="btn_reset">{{__('Reset')}}</button>
                              </div>

                        </div>
                                    </div>
                 </div>

                </form>
                </div><!-- /.box-header -->
              </div><!-- /.box -->
        





      </div><!-- /.content-wrapper -->





@endsection


@section('customjs')

<script src="{{ URL::asset('admin/dist/js/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js') }}"></script>

<script src="{{ URL::asset('admin/dist/js/signature/underscore-min.js') }}"></script>


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

function Resetvalues()
{
    $("#patient").val("");
    $("#patient").trigger("change");
    $("#no_of_weeks").val("");
    $("#note").val("");

    $("#pharmacist_signature").val("");
}
$(function () {
        $('#patient').select2({
            // minimumResultsForSearch: -1,
            placeholder: function(){
                $(this).data('placeholder');
            }}
        );
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

       

        $('input[name="dd"]').on('ifChanged', function(event) {
            if($(this).prop('checked')){                
                $('input[type=checkbox][value=3]').iCheck('check');
              }
              else{                
                $('input[type=checkbox][value=3]').iCheck('uncheck');
              }
        });

        $('input[type=checkbox][value=3]').on('ifChanged', function(event) {
            if($(this).prop('checked')){                
                $('input[name="dd"]').iCheck('check');
              }
              else{                
                $('input[name="dd"]').iCheck('uncheck');
              }
        });


      });

    $(document).ready(function(){
       
        $("#main-wrap").css("display", "none");
        $("#lblmainheading").html("Checkings");
        $('#patient').select2();

        $('#patient').change(function(){
        var ob=$(this).children('option:selected');
        var dob=ob.attr('data-dob');
        var lastPickupDate=ob.attr('data-lastPickupDate');
        var lastPickupWeek=ob.attr('data-lastPickupWeek');
        $('#dob').val(dob);
        $('#last_pickup_date').text(lastPickupDate);
        $('#last_pickup_week').text(lastPickupWeek);

        $('#weeks_last_picked_up').val(lastPickupWeek);
        $('#last_pick_up_date').val(lastPickupDate);
        var last_CheckingLocation=ob.attr('data-last_CheckingLocation');
        var last_CheckingDD=ob.attr('data-last_CheckingDD');
        let hasDD=false;
        if(last_CheckingLocation){
        let arr = last_CheckingLocation.split(',');
        if(arr.length){
            $('input[name="location[]"]').parent().removeClass("checked");
            for(var i=0; i < arr.length;  i++){
            $('input[name="location[]"][value='+arr[i]+']').parent().addClass("checked");
            $('input[name="location[]"][value='+arr[i]+']').attr("checked",'checked');
            if(arr[i]==3){
              hasDD=true;
            }

            }
        }
        }
        else{
        $('input[name="location[]"]').parent().removeClass("checked");
        $('input[name="location[]"]').removeAttr("checked");
        }

        if(last_CheckingDD=='1' || hasDD == true){
            $('input[name="dd"][value=1]').parent().addClass("checked");
            $('input[name="dd"][value=1]').attr("checked",'checked');

        }else{
        $('input[name="dd"]').parent().removeClass("checked");
        $('input[name="dd"]').removeAttr("checked");
        }

      });

      $('#addCheckings').submit(function(){


        var err=0;
        // $('button[type=submit]').prop('disabled',true);
        let phar_sign=$('#pharmacist_signature').val();
        if(phar_sign.length==0){
            ++err;
            $('#pharmacistSignDiv').css('color','red');

        }else{
            $('#pharmacistSignDiv').css('color','');
        }
        if(err>0){
            return false;
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
/*saveButton.addEventListener('click', function(event) {
    var data = signaturePad.toDataURL('image/png');
    window.open(data);
});*/
clearButton.addEventListener('click', function(event) {
    signaturePad.clear();
    document.getElementById('pharmacist_signature').value = "";
});
// showPointsToggle.addEventListener('click', function(event) {
//     signaturePad.showPointsToggle();
//     showPointsToggle.classList.toggle('toggle');
// });
  </script>
<script >
$(document).ready(function() {
    console.log("loaded");
    /*  Start of the Add Patient  By  Ajax */
    $("#addCheckings").on('submit', function(e) {
        // console.log($(this).serialize()+'&_token='+'{{ csrf_token() }}')


        var pharmacist_signature = $("#pharmacist_signature").val();

        if(pharmacist_signature == "")
            {
                $("#lblsignatureerrow").fadeIn();
                return;
            }
            else
            {
                $("#lblsignatureerrow").fadeOut();
            }

        e.preventDefault();
        if (confirm('Are you sure you want to save this thing into the database?')) {
          $.ajax({
                
                type: "POST",
                url: "{{url('save_checking')}}",
                data: $('#addCheckings').serialize() + '&_token=' + '{{ csrf_token() }}',
                beforeSend: function() {
                    $('.add_checking').html('<i class="fa fa-spinner fa-spin"></i>');
                },
                success: function(result) {
                    

                    window.location.href ="checkings_report";
                     
                } 
                 
            });
          console.log('Thing was saved to the database.');
        } else {
          // Do nothing!
            console.log('Thing was not saved to the database.');
            $("#addCheckings")[0].reset();
            $('input[type=checkbox]').parent()
                .removeClass("checked");
            $('#patient').val([]);
            $('#pharmacistSignDiv').html("");
            $('#pharmacist_signature').val();

             $("#patient").select2({
                placeholder: "Select a customer",
                initSelection: function(element, callback) {                   
                }
            });
                signaturePad.clear();
                document.getElementById('pharmacist_signature').value = "";
        
                                     
        }
         
    });
});
 </script>
@endsection

