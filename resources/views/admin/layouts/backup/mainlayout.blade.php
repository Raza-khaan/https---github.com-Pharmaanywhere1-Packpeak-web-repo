<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
     @yield('title')
    @include('admin.includes.head_css')
  </head>
  <body class="wysihtml5-supported skin-blue">
      @include('admin.includes.header')
      @include('admin.includes.sidebar')

      @yield('content')
      
      @include('admin.includes.footer')
    
      @include('admin.includes.footer_script')

      @yield('customjs')

  </body>
</html>