<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     @yield('title')
     @include('tenant.includes.head_css')
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('tenant.includes.header')
      <!-- @include('tenant.includes.sidebar') -->

      @yield('content')
      
      @include(tenant.includes.footer')
    
      @include('tenant.includes.footer_script2')

      @yield('customjs')

  </body>
</html>