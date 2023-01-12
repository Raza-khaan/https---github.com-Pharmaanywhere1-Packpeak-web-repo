@extends('admin.layouts.non_header_nav_layout')
@section('title') <title>404  Page </title> 

<style type="text/css">
[class^="icon-"], [class*=" icon-"] {
    background-image: url("public/admin/bootstrap/glyphicons/glyphicons-halflings.png");
}
</style>
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
      <div class="wrapper">
                <!-- Main content -->
        <section class="content" style="height:560px;padding-top:200px;  ">

          <div class="error-page">
            <h1 class="headline text-red"> 404</h1>
            <div class="error-content">
              <h1 class="text-red"><i class="fa fa-warning text-red"></i> Oops! Page not found.</h1>
              <p>
                <a href="{{url('/')}}">Return To Dashboard</a>
              </p>
              
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section><!-- /.content -->

       


      </div><!-- /.content-wrapper -->



    

  @endsection

 @section('customjs')

  
@endsection

