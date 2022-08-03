<!--
=========================================================
 Light Bootstrap Dashboard - v2.0.1
=========================================================

 Product Page: https://www.creative-tim.com/product/light-bootstrap-dashboard
 Copyright 2019 Creative Tim (https://www.creative-tim.com) & Updivision (https://www.updivision.com)
 Licensed under MIT (https://github.com/creativetimofficial/light-bootstrap-dashboard/blob/master/LICENSE)

 Coded by Creative Tim & Updivision

=========================================================

 The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.  -->
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.ico') }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>{{ $title }}</title>
        <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

        <!-- CSS Files -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/light-bootstrap-dashboard.css?v=2.0.0') }} " rel="stylesheet" />
        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="{{ asset('assets/css/demo.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/loading.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/bootstrap-icons.css') }}" rel="stylesheet" />
        @stack('styles')
        @yield('stylebb')
    </head>

    <body>
      
      <div id="loader_content" class="content_loading">
        <div class="overlay_loading"></div>
        <div class="text_loading text-dark text-center"><b> Cargando...</b></div>
      </div>

      <div class="wrapper @if (!auth()->check() || request()->route()->getName() == "") wrapper-full-page @endif">

          @if (auth()->check() && request()->route()->getName() != "")
              @include('layouts.navbars.sidebar')
              {{-- @include('pages/sidebarstyle') --}}
          @endif

          <div class="@if (auth()->check() && request()->route()->getName() != "") main-panel @endif">
              @include('layouts.navbars.navbar')
              @yield('content')
              @include('layouts.footer.nav')
          </div>

      </div>



    </body>
    <script type="text/javascript">
      var Protocol_ = window.location.protocol;
      var UrlHost_ = Protocol_+"//"+"{{$_SERVER["HTTP_HOST"]}}";
    </script>

        <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery.3.6.0.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
    <script src="{{ asset('assets/js/plugins/jquery.sharrre.js') }}"></script>

    <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="{{ asset('assets/js/plugins/bootstrap-switch.js') }}"></script>
    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!--  Chartist Plugin  -->
    <script src="{{ asset('assets/js/plugins/chartist.min.js') }}"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('assets/js/plugins/bootstrap-notify.js') }}"></script>
    <!-- Control Center for Light Bootstrap Dashboard: scripts for the example pages etc -->
    <script src="{{ asset('assets/js/light-bootstrap-dashboard.js?v=2.0.0') }}" type="text/javascript"></script>
    <!-- Sweetalert -->
    <script src="{{asset('assets/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script src="{{asset('assets/js/sweetalert2.all.min.js')}}"></script>
    
    <!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
    <script src="{{ asset('assets/js/demo.js') }}"></script>
    <script src="{{ asset('assets/js/lovelight.js') }}"></script>

    
    @stack('js')
    <script>
      $(document).ready(function () {

        $(".content_loading").fadeOut("slow");

        $('#facebook').sharrre({
          share: {
            facebook: true
          },
          enableHover: false,
          enableTracking: false,
          enableCounter: false,
          click: function(api, options) {
            api.simulateClick();
            api.openPopup('facebook');
          },
          template: '<i class="fab fa-facebook-f"></i> Facebook',
          url: 'https://light-bootstrap-dashboard-laravel.creative-tim.com/login'
        });

        $('#google').sharrre({
          share: {
            googlePlus: true
          },
          enableCounter: false,
          enableHover: false,
          enableTracking: true,
          click: function(api, options) {
            api.simulateClick();
            api.openPopup('googlePlus');
          },
          template: '<i class="fab fa-google-plus"></i> Google',
          url: 'https://light-bootstrap-dashboard-laravel.creative-tim.com/login'
        });

        $('#twitter').sharrre({
          share: {
            twitter: true
          },
          enableHover: false,
          enableTracking: false,
          enableCounter: false,
          buttons: {
            twitter: {
              via: 'CreativeTim'
            }
          },
          click: function(api, options) {
            api.simulateClick();
            api.openPopup('twitter');
          },
          template: '<i class="fab fa-twitter"></i> Twitter',
          url: 'https://light-bootstrap-dashboard-laravel.creative-tim.com/login'
        });
      });
    </script>
              @yield('script')

</html>
