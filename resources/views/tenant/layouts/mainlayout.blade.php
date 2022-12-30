<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="_token" content="{{ csrf_token() }}">
     @yield('title')
    @include('tenant.includes.head_css')
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('tenant.includes.header')
      @include('tenant.includes.sidebar')

      @yield('content')
      
      @include('tenant.includes.footer')
    
      @include('tenant.includes.footer_script')

      @yield('customjs')

  </body>
</html>