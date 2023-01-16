@extends('tenant.layouts.mainlayout')
@section('title') <title>User  Details </title>
<style>

img.margin.borderclass {
    border: 2px solid #3c8dbc;
}





</style>

@endsection



@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        @if(Session::get('phrmacy')->roll_type=='admin') Pharmacy Admin  @elseif(Session::get('phrmacy')->roll_type=='technician') Technician @endif Profile
      </h1>

      <!-- <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{url('technician')}}">Technician</a></li>
            <li class="active" ><a href="{{url('Profile/'.Session::get('phrmacy')->id)}}">Driver details</a></li>
      </ol> -->

      @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="row">

        <!-- /.col -->
        <div class="col-md-6">
            @if(Session::has('msg'))
              {!!  Session::get("msg") !!}
           @endif
            <div class="box box-primary" style="min-height:445px;">
            <div class="box-header with-border">
              <!-- <h3 class="box-title">Basic Info</h3> -->

            </div>
            <div>
              <div class="timeline-body pre-wrp">
                <form class="form-group" action="{{url('update_profile')}}" method="post">
                  @csrf
                  <fieldset>
                    <legend>&nbsp;&nbsp;Update Information</legend>

                     <div class="col-md-6">
                        <div class="form-group">
                          <label>First Name</label>
                          <input type="text" name="first_name" onkeypress="return restrictNumerics(event);" value="{{$user_data->first_name}}" class="form-control" placeholder="First Name">

                        </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                          <label>Last Name</label>
                          <input type="text" name="last_name" onkeypress="return restrictNumerics(event);" value="{{$user_data->last_name}}" class="form-control" placeholder="Enter Last Name">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                          <label>Username</label>
                          <input type="text"  value="{{$user_data->username}}" readonly class="form-control" placeholder="Enter Username">
                        </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                          <label>Pin</label>
                          <input type="text" name="pin" onkeypress="return restrictAlphabets(event);" maxlength="4" minlength="4" value="{{$user_data->pin}}" class="form-control" placeholder="Enter Pin">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                          <label>Email</label>
                          <input type="email"   value="{{$user_data->email?$user_data->email:''}}" readonly class="form-control" >
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                          <label>Phone</label>
                          <input type="text" name="phone" onkeypress="return restrictAlphabets(event);" value="{{$user_data->phone?$user_data->phone:'04'}}" maxlength="10" class="form-control" placeholder="Enter Phone Number">
                        </div>
                     </div>

                     <div class="col-md-12">
                        <div class="form-group">
                          <label for="sign">{{__('Signature')}} </label>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                              <section class="signature-component">
                                  <canvas id="signature-pad"  ></canvas>
                                  <input type="hidden" id="sign"    name="sign"    value="" />
                              </section>
                            </div>

                            <div class="col-md-5">
                            <div class="signature-component btn btn-group">
                              <button type="button" id="signature-pad-clear">Clear</button>
                            </div>
                            <div >Draw Your Signature</div>
                            @if($user_data->sign!="" || $user_data->sign!=NULL)
                              <img src="{{$user_data->sign}}" style="width:200px;"/>
                            @endif

                            </div>

                        </div> <!-- END OF  THE ROW -->

                     </div> <!-- End of the col-md-12 -->

                     <div class="col-md-6">
                        <div class="form-group">
                          <label for="dob">{{__('Date Of Birth')}} </label>
                          <input type="text"  class="form-control"   name="dob" value="{{\Carbon\Carbon::createFromFormat('Y-m-d',$user_data->dob?$user_data->dob:'2020-01-01')->format('d/m/Y')}}" id="dob" placeholder="Date Of Birth" max="{{Carbon\Carbon::now()->format('d/m/Y')}}">
                        </div>
                     </div>
                     <div class="col-md-offset-6 col-md-3">
                       <div class="form-group">
                          <label></label>
                          <button class="btn btn-primary btn-block">Update</button>
                        </div>
                     </div>
                   </fieldset>
                </form>
                @if(Session::get('phrmacy')->roll_type=='admin')

                <form class="form-group" id="update_access" action="javascript:void(0);">
                  <fieldset>
                    <legend>&nbsp;&nbsp;Update Information</legend>
                     <div class="accessalert"></div>
                     <div class="col-md-6">
                        <div class="form-group">
                          <label>App Logout Time (Minute)</label>
                          <input type="text" name="app_logout_time" id="app_logout_time" value="{{$accessLevel->app_logout_time}}" class="form-control" placeholder="Time (minut)">
                        </div>
                     </div>
                     <div class="col-md-6">
                       <div class="form-group">
                          <label>Cycle (in Weeks)</label>
                          <input type="text" name="default_cycle" id="default_cycle" value="{{$accessLevel->default_cycle}}" class="form-control" placeholder="Enter cycle">

                        </div>
                     </div>
                     <div class="col-md-offset-6 col-md-3">
                       <div class="form-group">
                          <label></label>
                          <button class="btn btn-primary btn-block">Update</button>
                        </div>
                     </div>
                   </fieldset>
                </form>
                @endif


              </div>
             </div>
            </div>
        </div>

        <div class="col-md-6">
           @if(Session::has('msgp'))
              {!!  Session::get("msgp") !!}
           @endif

          <div class="box box-primary" style="min-height:245px;">
            <div class="box-header with-border">
              <!-- <h3 class="box-title">About</h3> -->
            </div>
            <div>
              <div class="timeline-body pre-wrp">
                  <form class="form-group" action="{{url('update_password')}}" method="post">
                    @csrf
                    <fieldset>
                      <legend>&nbsp;&nbsp;Change Password</legend>
                       <div class="col-md-6">

                       <div class="form-group">
                       <label>old Password</label>
                          <div class="input-group">
                            <input type="password" name="old_password" id="password" class="form-control" placeholder="Enter Old Password">
                            <div class="input-group-addon">
                            <i class="fa fa-eye" id="togglePassword"></i>
                            </div>
                          </div>
                      </div>


                       </div>
                       <div class="col-md-6">
                         <div class="form-group">
                            <label>New Password</label>
                            <div class="input-group">
                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter New Password">
                            <div class="input-group-addon">
                            <i class="fa fa-eye" id="toggleNewPassword"></i>
                            </div>
                            </div>
                          </div>
                       </div>
                       <div class="col-md-6">
                         <div class="form-group">
                            <label>Confirm Password</label>
                            <div class="input-group">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                            <div class="input-group-addon">
                            <i class="fa fa-eye" id="toggleConfirmPassword"></i>
                            </div>
                            </div>
                          </div>
                       </div>
                       <div class="col-md-3">
                         <div class="form-group">
                            <label></label>
                            <button class="btn btn-primary btn-block">Update</button>
                          </div>
                       </div>
                     </fieldset>
                  </form>
              </div>
            </div>
          </div>
             @if(Session::get('phrmacy')->roll_type=='admin')
             <div class="box box-primary" style="min-height:245px;">
            <div class="box-header with-border">
              <h3 class="box-title">Paitent SMS Setting</h3>
            </div>
            <div>
                <div class="box-body pre-wrp-in table-responsive">

                  <table id="tbDatatableP"  data-model='Patient'   class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th  >{{__('First Name')}}</th>
                        <th>{{__('Date of birth')}}</th>
                        <th>{{__('Pharmacy Notify')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($patient_reports as $patient_report)
                      <tr>
                        <td>{{ $patient_report->first_name}} {{ $patient_report->last_name}}</td>
                        <td>{{ date("j/n/Y",strtotime($patient_report->dob))}}</td>
                       <td>

                              <input type="checkbox" class="custom-control-input" id="customCheck1" {{ $patient_report->sms_allowed==1?'checked':''}} data-pid="{{$patient_report->id}}">



                        </td>
                      </tr>
                      @endforeach

                    </tbody>

                  </table>



                </div><!-- /.box-body -->


            </div>
          </div>
        @endif
        </div>

        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->





@endsection


@section('customjs')
<script>

    $(function () {

           $('#dob').datepicker({
            format: "dd/mm/yyyy",
            endDate: new Date(),
            autoclose: true
          });
          $('#dob').on('keyup keypress keydown', function(e){
              e.preventDefault();
          });

        $('#update_access').submit(function(e){
             if($('#app_logout_time').val() && $('#default_cycle').val()){
                   $.ajax({
                  type: "POST",
                  url: "{{url('update_access')}}",
                  data: {'app_logout_time':$('#app_logout_time').val(),'default_cycle':$('#default_cycle').val(),"_token":"{{ csrf_token() }}"},
                  beforeSend: function() {
                    $('.loader_company').html('<i class="fa fa-spinner fa-spin"></i>');
                  },
                  success: function(result){
                    console.log(result);
                    if(result=='200'){
                       $('.accessalert').html('<div class="alert alert-success"> Data Updated <strong>.</strong></div>');
                    }
                    else if(result=='401'){
                      $('.accessalert').html('<div class="alert alert-danger"> somthing went wrong!.  try  again</div>');
                    }
                    else{
                      $('.accessalert').html('<div class="alert alert-danger">'+result+'</div>');
                    }


                  },
                  error:function(result){
                     console.log(result);
                  }
                  });
             }
             else{
              $('.accessalert').html('<div class="alert alert-danger">fields are required</div>');
             }
        });
    });

    function restrictNumerics(e){
        var x=e.which||e.keycode;
        if((x>=65 && x<=90) || x==8 ||
        (x>=97 && x<=122)|| x==95 || x==32)
        return true;
        else
        return false;
      }


      //     restrict Alphabets
    function restrictAlphabets(e){
      var x=e.which||e.keycode;
      if((x>=48 && x<=57) )
      return true;
      else
      return false;
     }


</script>
<script src="{{ URL::asset('admin/dist/js/signature/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js')}}"></script>

<script src="{{ URL::asset('admin/dist/js/signature/underscore-min.js')}}"></script>

<script id="INLINE_PEN_JS_ID">
    /*!
 * Modified
 * Signature Pad v1.5.3
 * https://github.com/szimek/signature_pad
 *
 * Copyright 2016 Szymon Nowak
 * Released under the MIT license
 */
var SignaturePad = function (document) {
  "use strict";

  var log = console.log.bind(console);

  var SignaturePad = function (canvas, options) {
    var self = this,
    opts = options || {};

    this.velocityFilterWeight = opts.velocityFilterWeight || 0.7;
    this.minWidth = opts.minWidth || 0.5;
    this.maxWidth = opts.maxWidth || 2.5;
    this.dotSize = opts.dotSize || function () {
      return (self.minWidth + self.maxWidth) / 2;
    };
    this.penColor = opts.penColor || "black";
    this.backgroundColor = opts.backgroundColor || "rgba(0,0,0,0)";
    this.throttle = opts.throttle || 0;
    this.throttleOptions = {
      leading: true,
      trailing: true };

    this.minPointDistance = opts.minPointDistance || 0;
    this.onEnd = opts.onEnd;
    this.onBegin = opts.onBegin;

    this._canvas = canvas;
    this._ctx = canvas.getContext("2d");
    this._ctx.lineCap = 'round';
    this.clear();

    // we need add these inline so they are available to unbind while still having
    //  access to 'self' we could use _.bind but it's not worth adding a dependency
    this._handleMouseDown = function (event) {
      if (event.which === 1) {
        self._mouseButtonDown = true;
        self._strokeBegin(event);
      }
    };

    var _handleMouseMove = function (event) {
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

    this._handleMouseUp = function (event) {
      if (event.which === 1 && self._mouseButtonDown) {
        self._mouseButtonDown = false;
        self._strokeEnd(event);
        console.log($(self._canvas).attr('id'))
        if($(self._canvas).attr('id')=='signature-pad') {
           document.getElementById('sign').value = signaturePad.toDataURL();
           console.log(signaturePad.toDataURL())
        }

      }
    };

    this._handleTouchStart = function (event) {
      if (event.targetTouches.length == 1) {
        var touch = event.changedTouches[0];
        self._strokeBegin(touch);
      }
    };

    var _handleTouchMove = function (event) {
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

    this._handleTouchEnd = function (event) {
      var wasCanvasTouched = event.target === self._canvas;
      if (wasCanvasTouched) {
        event.preventDefault();
        self._strokeEnd(event);
      }
    };

    this._handleMouseEvents();
    this._handleTouchEvents();
  };

  SignaturePad.prototype.clear = function () {
    var ctx = this._ctx,
    canvas = this._canvas;

    ctx.fillStyle = this.backgroundColor;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    this._reset();
  };

  SignaturePad.prototype.showPointsToggle = function () {
    this.arePointsDisplayed = !this.arePointsDisplayed;
  };

  SignaturePad.prototype.toDataURL = function (imageType, quality) {
    var canvas = this._canvas;
    return canvas.toDataURL.apply(canvas, arguments);
  };

  SignaturePad.prototype.fromDataURL = function (dataUrl) {
    var self = this,
    image = new Image(),
    ratio = window.devicePixelRatio || 1,
    width = this._canvas.width / ratio,
    height = this._canvas.height / ratio;

    this._reset();
    image.src = dataUrl;
    image.onload = function () {
      self._ctx.drawImage(image, 0, 0, width, height);
    };
    this._isEmpty = false;
  };

  SignaturePad.prototype._strokeUpdate = function (event) {
    var point = this._createPoint(event);
    if (this._isPointToBeUsed(point)) {
      this._addPoint(point);
    }
  };

  var pointsSkippedFromBeingAdded = 0;
  SignaturePad.prototype._isPointToBeUsed = function (point) {
    // Simplifying, De-noise
    if (!this.minPointDistance)
    return true;

    var points = this.points;
    if (points && points.length) {
      var lastPoint = points[points.length - 1];
      if (point.distanceTo(lastPoint) < this.minPointDistance) {
        // log(++pointsSkippedFromBeingAdded);
        return false;
      }
    }
    return true;
  };

  SignaturePad.prototype._strokeBegin = function (event) {
    this._reset();
    this._strokeUpdate(event);
    if (typeof this.onBegin === 'function') {
      this.onBegin(event);
    }
  };

  SignaturePad.prototype._strokeDraw = function (point) {
    var ctx = this._ctx,
    dotSize = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;

    ctx.beginPath();
    this._drawPoint(point.x, point.y, dotSize);
    ctx.closePath();
    ctx.fill();
  };

  SignaturePad.prototype._strokeEnd = function (event) {
    var canDrawCurve = this.points.length > 2,
    point = this.points[0];

    if (!canDrawCurve && point) {
      this._strokeDraw(point);
    }
    if (typeof this.onEnd === 'function') {
      this.onEnd(event);
    }
  };

  SignaturePad.prototype._handleMouseEvents = function () {
    this._mouseButtonDown = false;

    this._canvas.addEventListener("mousedown", this._handleMouseDown);
    this._canvas.addEventListener("mousemove", this._handleMouseMove);
    document.addEventListener("mouseup", this._handleMouseUp);
  };

  SignaturePad.prototype._handleTouchEvents = function () {
    // Pass touch events to canvas element on mobile IE11 and Edge.
    this._canvas.style.msTouchAction = 'none';
    this._canvas.style.touchAction = 'none';

    this._canvas.addEventListener("touchstart", this._handleTouchStart);
    this._canvas.addEventListener("touchmove", this._handleTouchMove);
    this._canvas.addEventListener("touchend", this._handleTouchEnd);
  };

  SignaturePad.prototype.on = function () {
    this._handleMouseEvents();
    this._handleTouchEvents();
  };

  SignaturePad.prototype.off = function () {
    this._canvas.removeEventListener("mousedown", this._handleMouseDown);
    this._canvas.removeEventListener("mousemove", this._handleMouseMove);
    document.removeEventListener("mouseup", this._handleMouseUp);

    this._canvas.removeEventListener("touchstart", this._handleTouchStart);
    this._canvas.removeEventListener("touchmove", this._handleTouchMove);
    this._canvas.removeEventListener("touchend", this._handleTouchEnd);
  };

  SignaturePad.prototype.isEmpty = function () {
    return this._isEmpty;
  };

  SignaturePad.prototype._reset = function () {
    this.points = [];
    this._lastVelocity = 0;
    this._lastWidth = (this.minWidth + this.maxWidth) / 2;
    this._isEmpty = true;
    this._ctx.fillStyle = this.penColor;
  };

  SignaturePad.prototype._createPoint = function (event) {
    var rect = this._canvas.getBoundingClientRect();
    return new Point(
    event.clientX - rect.left,
    event.clientY - rect.top);

  };

  SignaturePad.prototype._addPoint = function (point) {
    var points = this.points,
    c2,c3,
    curve,tmp;

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

  SignaturePad.prototype._calculateCurveControlPoints = function (s1, s2, s3) {
    var dx1 = s1.x - s2.x,
    dy1 = s1.y - s2.y,
    dx2 = s2.x - s3.x,
    dy2 = s2.y - s3.y,

    m1 = {
      x: (s1.x + s2.x) / 2.0,
      y: (s1.y + s2.y) / 2.0 },

    m2 = {
      x: (s2.x + s3.x) / 2.0,
      y: (s2.y + s3.y) / 2.0 },


    l1 = Math.sqrt(1.0 * dx1 * dx1 + dy1 * dy1),
    l2 = Math.sqrt(1.0 * dx2 * dx2 + dy2 * dy2),

    dxm = m1.x - m2.x,
    dym = m1.y - m2.y,

    k = l2 / (l1 + l2),
    cm = {
      x: m2.x + dxm * k,
      y: m2.y + dym * k },


    tx = s2.x - cm.x,
    ty = s2.y - cm.y;

    return {
      c1: new Point(m1.x + tx, m1.y + ty),
      c2: new Point(m2.x + tx, m2.y + ty) };

  };

  SignaturePad.prototype._addCurve = function (curve) {
    var startPoint = curve.startPoint,
    endPoint = curve.endPoint,
    velocity,newWidth;

    velocity = endPoint.velocityFrom(startPoint);
    velocity = this.velocityFilterWeight * velocity +
    (1 - this.velocityFilterWeight) * this._lastVelocity;

    newWidth = this._strokeWidth(velocity);
    this._drawCurve(curve, this._lastWidth, newWidth);

    this._lastVelocity = velocity;
    this._lastWidth = newWidth;
  };

  SignaturePad.prototype._drawPoint = function (x, y, size) {
    var ctx = this._ctx;

    ctx.moveTo(x, y);
    ctx.arc(x, y, size, 0, 2 * Math.PI, false);
    this._isEmpty = false;
  };

  SignaturePad.prototype._drawMark = function (x, y, size) {
    var ctx = this._ctx;

    ctx.save();
    ctx.moveTo(x, y);
    ctx.arc(x, y, size, 0, 2 * Math.PI, false);
    ctx.fillStyle = 'rgba(255, 0, 0, 0.2)';
    ctx.fill();
    ctx.restore();
  };

  SignaturePad.prototype._drawCurve = function (curve, startWidth, endWidth) {
    var ctx = this._ctx,
    widthDelta = endWidth - startWidth,
    drawSteps,width,i,t,tt,ttt,u,uu,uuu,x,y;

    drawSteps = Math.floor(curve.length());
    ctx.beginPath();
    for (i = 0; i < drawSteps; i++) {if (window.CP.shouldStopExecution(0)) break;
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
    }window.CP.exitedLoop(0);
    ctx.closePath();
    ctx.fill();
  };

  SignaturePad.prototype._strokeWidth = function (velocity) {
    return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
  };

  var Point = function (x, y, time) {
    this.x = x;
    this.y = y;
    this.time = time || new Date().getTime();
  };

  Point.prototype.velocityFrom = function (start) {
    return this.time !== start.time ? this.distanceTo(start) / (this.time - start.time) : 1;
  };

  Point.prototype.distanceTo = function (start) {
    return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
  };

  var Bezier = function (startPoint, control1, control2, endPoint) {
    this.startPoint = startPoint;
    this.control1 = control1;
    this.control2 = control2;
    this.endPoint = endPoint;
  };

  // Returns approximated length.
  Bezier.prototype.length = function () {
    var steps = 10,
    length = 0,
    i,t,cx,cy,px,py,xdiff,ydiff;

    for (i = 0; i <= steps; i++) {if (window.CP.shouldStopExecution(1)) break;
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
    }window.CP.exitedLoop(1);
    return length;
  };

  Bezier.prototype._point = function (t, start, c1, c2, end) {
    return start * (1.0 - t) * (1.0 - t) * (1.0 - t) +
    3.0 * c1 * (1.0 - t) * (1.0 - t) * t +
    3.0 * c2 * (1.0 - t) * t * t +
    end * t * t * t;
  };

  return SignaturePad;
}(document);

var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
  backgroundColor: 'rgba(255, 255, 255, 0)',
  penColor: 'rgb(0, 0, 0)',
  velocityFilterWeight: .7,
  minWidth: 0.5,
  maxWidth: 2.5,
  throttle: 16, // max x milli seconds on event update, OBS! this introduces lag for event update
  minPointDistance: 3 });








// var saveButton = document.getElementById('signature-pad-save'),
var clearButton = document.getElementById('signature-pad-clear');

clearButton.addEventListener('click', function (event) {
  signaturePad.clear();
});

 /* END OF SECOND SIGNATURE PAD  */

 $('#tbDatatableP').dataTable({
            "responsive": true,
            "bSort": true,
            "lengthChange": true,
            'order': [[1, 'asc']],
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],    // page length options

        });
    $(function () {
      $('input').iCheck('destroy');
$('body').on('click',"#customCheck1", function(){
  pId= $(this).data('pid');
  status=(this.checked)?1:0;
  console.log(pId, status);
  $.ajax({
            type: "POST",
            url: "{{url('updateSMSSetting')}}",
            data: {'id':pId,'status':status,"_token":"{{ csrf_token() }}"},
            success: function(result){
              // console.log(result)
              $('.totalPickupOfMonth').html('This Month Pickup :'+result);
            }
          });
 })
});
  </script>

@endsection
