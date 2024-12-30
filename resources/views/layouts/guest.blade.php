<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ url('front/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ url('front/img/favicon.png') }}">
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="{{ url('front/css/nucleo-icons.css') }}" rel="stylesheet" />
        <link href="{{ url('front/css/nucleo-svg.css') }}" rel="stylesheet" />
        <!-- Font Awesome Icons -->
        <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- CSS Files -->
        <link id="pagestyle" href="{{ url('front/css/soft-ui-dashboard.css') }}" rel="stylesheet" />
    </head>
    <body>
    <nav class="navbar navbar-expand-lg top-0 z-index-3 w-100 shadow-none my-2 mt-2">
        <div class="container">



            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
            </button>

            <div class="collapse navbar-collapse" id="navigation">
                <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                    <li class="nav-item">
                        <a class="nav-link me-2" href="{{ route('register') }}">
                            <i class="fas fa-user-circle opacity-6  me-1"></i>
                            Sign Up
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link me-2" href="{{ route('login') }}">
                            <i class="fas fa-key opacity-6  me-1"></i>
                            Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="main-content  mt-0">
        <section class="min-vh-100 mb-3">

            <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('{{ url("front/img/Signup-banner.jpg") }}');">
                <span class="mask bg-gradient-dark opacity-6"></span>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center mx-auto">
                            <h1 class="text-white mb-2 mt-8">Welcome To E-Jang</h1>
                            </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row mt-lg-n10 mt-md-n11 mt-n10">
                    <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                        <div class="card z-index-0">


                            <div class="card-body">
                                {{ $slot }}


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
        <footer class="footer py-5 bs-user-black">



            <div class="container">



                <!-- Footer CP row html starts here -->
                <section class="cp_btm_sec">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="mb-0 text-light text-center">
                                    © Retirement Actuarial Services. All Rights Reserved.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Footer CP row html ends here -->

            </div>

        </footer>
        <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    </main>



    <script src="{{ url('front/js/core/popper.min.js') }}"></script>
    <script src="{{ url('front/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ url('front/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ url('front/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ url('front/js/soft-ui-dashboard.min.js') }}"></script>




    </body>
</html>
