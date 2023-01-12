<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     @yield('title')
    @yield('customcss')
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('tenant.includes.header')
      <!-- @include('tenant.includes.sidebar') -->

      @yield('content')
      
    @include('tenant.includes.footer')
    @yield('customjs')
  </body>
</html>