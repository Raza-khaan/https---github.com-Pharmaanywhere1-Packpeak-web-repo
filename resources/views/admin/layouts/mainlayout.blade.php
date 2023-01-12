<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     @yield('title')
     <style type="text/css">
       .text-danger{
         color: red !important;
       }
     </style>
    @include('admin.includes.head_css')
  </head>
  <body>
       @include('admin.includes.header') 
      @include('admin.includes.sidebar')

       @yield('content') 
      
      @include('admin.includes.footer')
    
      @include('admin.includes.footer_script')

      @yield('customjs')

  </body>
</html>