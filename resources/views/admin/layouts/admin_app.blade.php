<!DOCTYPE html>
<html lang="en">
  @include('admin.layouts.head')
  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
    @include('admin.layouts.header')
    <!-- Sidebar menu-->
    @include('admin.layouts.sidebar')
    <main class="app-content">
      @yield('content')
    </main>
	  @include('admin.layouts.footer')
    <!-- Essential javascripts for application to work-->
  </body>
</html>