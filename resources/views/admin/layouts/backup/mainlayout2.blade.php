<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     @yield('title')
    @yield('customcss')
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('admin.includes.header')
      @include('admin.includes.sidebar')

      @yield('content')
      
    @include('admin.includes.footer')
    @yield('customjs')
  </body>
</html>