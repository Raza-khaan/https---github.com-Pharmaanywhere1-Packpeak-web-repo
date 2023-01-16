@extends('admin.layouts.mainlayout')
@section('title') <title>Audits</title>

@endsection
@section('content')

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

#dropContainer {
    border: 2px dashed #CCCCCC;
    border-radius: 7px;
    opacity: 1;
    text-align: center;
    height: 200px;
    text-align: left !important;
    padding: 60px !important;
    /* width: 500px; */
    /* position: relative; */
    /* margin-top: 60px; */
    /* left: 20px; */
}

.back {
    border: 1px solid #CCCCCC !important;
    border-radius: 7px !important;
    opacity: 1;
    top: 60px !important;
    /* position: relative !important; */
}
</style>


<!-- Content Wrapper. Contains page content -->
<div class="dash-wrap">
    <div class="dashborad-header">
        <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
        <h2>Bulk Import Patients</h2>
        <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
        <div class="user-menu">

            <div class="profile">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <img src="assets/images/user.png" alt=""> <span>Amir Eid</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">My Profile</a>
                        <a class="dropdown-item" href="#">Setting</a>
                        <a class="dropdown-item" href="#">Logout</a>
                    </div>
                </div>
                <p class="online"><span></span>Online</p>
            </div>
        </div>
    </div>

    <div class="pharma-register">
        <h2>Bulk Import Patients</h2>
    </div>
    <form role="form" action="{{url('admin/save_audit')}}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="reports-breadcrum m-0">

            <nav class="dash-breadcrumb" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><img src="assets/images/icon-home.png"
                                alt="">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Forms</li>
                    <li class="breadcrumb-item active" aria-current="page">General Elements</li>
                </ol>
            </nav>

        </div>
        <!-- Main content -->
        <div class="row">
            <div class="col-md-6 m-auto">
                <!-- general form elements -->
                <div class="update-information">
                    <div class="notes">
                        <h3 class="text-center">Bulk Import Patients</h3>

                        @if(Session::has('msg'))
                        {!! Session::get("msg") !!}
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
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn back" style=""> ← Go Back</button>
                            </div>
                            <hr>
                            <div class="mt-4"></div>
                            <div class="form-group col-md-12">
                                <label for="dob" style="">Upload File</label>
                                <div id="dropContainer">
                                    <p><b>Drop Files Here or </b>
                                        <input type="file" id="fileInput"
                                            style="background-color:#fff !important;border:none !important;font-size:16px !important;height:30px !important;">
                                        <small style="">Only PDF,Excel and CSV files are allowed to upload</small>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group col-md-12 text-center">
                                <button type="button" class="btn btn-md btn-primary">Import</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div> <!-- /.row -->

@endsection


@section('customjs')


@endsection