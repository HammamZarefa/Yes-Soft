<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $general->title }}</title>
    <meta content="{{ $general->meta_desc }}" name="description">
    <meta content="{{ $general->keyword }}" name="keywords">
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <!-- Favicons -->
    <link href="{{ asset('storage/'.$general->favicon) }}" rel="icon">
    <link href="{{ asset('storage/'.$general->favicon) }}" rel="apple-touch-icon">
    <link href="{{asset('front/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/ui.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/base.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/style-blue.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('front/css/font-awesome.min.css')}}" rel="stylesheet">

    <!-- Slider -->
    <link href="{{asset('front/rs-plugin/css/settings.css')}}" rel="stylesheet"/>
    <link href="{{asset('front/showbizpro/css/settings.css')}}" rel="stylesheet" media="screen"/>

    <!-- Favicon -->
    <link href="{{asset('front/images/favicon.ico')}}" rel="shortcut icon">



    <!-- JQUERY -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.js"></script>

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="{{asset('front/css/nill-box-menu-ie8.css')}}">
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{asset('front/js/jquery.placeholder.min.js')}}"></script>
    <![endif]-->

    <!-- Favicon -->
    <link href="images/favicon.ico" rel="shortcut icon">

@yield('meta')

<!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">




{{-- Sharethis --}}
{!! $general->sharethis !!}

<!-- =======================================================
  * Template Name: Company - v2.1.0
  * Template URL: https://bootstrapmade.com/company-free-html-bootstrap-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>



<!-- Navigation Menu -->
<div class="container-fluid navigation-menu">
    <div class="container">
        <div class="row">

            <!-- Logo -->
            <div class="col-xs-12 col-sm-12 col-md-2">
                <!-- Logo Normal -->
                <div class="logo hidden-xs hidden-sm">
                    <a href="/" class="">
                        <img src="{{ asset('storage/'.$general->logo) }}" alt="Logo"/>
                    </a>
                </div>
                <!-- Logo Retina -->
                <div class="logo-xtwo hidden-lg hidden-md">
                    <a href="/" class="">
                        <img src="{{ asset('storage/'.$general->logo) }}" alt="Logo"/>
                    </a>
                </div>
            </div>

            <!-- Menu -->
            <div class="col-xs-12 col-sm-12 col-md-10">

                <ul class="nill-box-menu nill-box-menu-anim-flip nill-box-menu-response-to-icons">
                    <!-- Home Page -->
                    <li class="hidden-xs">
                        <a href="/">
                            <i class="fa fa-single fa-home"></i>
                        </a>
                    </li>

                    <!-- switcher -->
                    <li class="switcher">
                        <a href="#">
                            <i class="fa fa-bars"></i>Menu</a>
                    </li>
                    <!--/ switcher -->


                    <!-- PRODUCTS -->
                    <li aria-haspopup="true">
                        <a>
                            <i class="fa fa-shopping-cart"></i>
                            <i class="fa fa-indicator fa-chevron-down"></i>Products</a>
                        <div class="grid-container3">
                            <ul>
                                @foreach($product as $products)
                                    <li aria-haspopup="true">
                                        <a href="{{route('productshow',$products->name)}}" class="yeloIconBg">
                                            <i class="fa fa-puzzle-piece"></i>{{ $products->name }}<br>
                                            <span>{{ $products->link }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <!--/ PRODUCTS -->

                    <!-- SERVICE -->
                    <li aria-haspopup="true">
                        <a>
                            <i class="fa fa-lightbulb-o"></i>
                            <i class="fa fa-indicator fa-chevron-down"></i>Service</a>
                        <div class="grid-container3">
                            <ul>
                                @foreach($services as $service)
                                    <li aria-haspopup="true">
                                        <a href="{{route('serviceshow',$service->slug)}}" class="cloudIconBg">
                                            <i class="fa fa-lightbulb-o"></i>{{$service->title}}<br>
                                            <span>{{$service->desc}}</span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </li>

                    <!-- Blog -->
                    <li aria-haspopup="true">
                        <a>
                            <i class="fa fa-calendar-o"></i>
                            <i class="fa fa-indicator fa-chevron-down"></i>Blog</a>
                        <div class="grid-container3">
                            <ul>
                                @foreach($lpost as $lpost)
                                    <li aria-haspopup="true">
                                        <a class="helpdeskIconBg" href="{{route('blogshow',$lpost->slug)}}">
                                            <i class="fa fa-calendar-o"></i> {{$lpost->title}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>

                    <!-- PORTFOLIO -->
                    <li aria-haspopup="true">
                        <a>
                            <i class="fa fa-briefcase"></i>
                            <i class="fa fa-indicator fa-chevron-down"></i>PORTFOLIO</a>
                        <div class="grid-container3">
                            <ul>
                                @foreach($portfolios as $portfolio)
                                    <li aria-haspopup="true">
                                        <a class="helpdeskIconBg" href="{{route('portfolioshow',$portfolio->slug)}}">
                                            <i class="fa fa-file"></i> {{$portfolio->slug}}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>


                    <!-- contact form -->
                    <li aria-haspopup="true" class="right">
                        <a href="#_">
                            <i class="fa fa-single fa-envelope"></i>
                        </a>
                        <div class="grid-container5">
                            <form action="#">
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-user"></i>
                                            <input type="text" placeholder="Your Email">
                                        </label>
                                    </section>
                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-user"></i>
                                            <textarea class="form-control" rows="4"> your message</textarea>
                                        </label>
                                    </section>
                                    <button type="submit" class="btn btn-primary button">Send A Message</button>
                                </fieldset>
                            </form>
                        </div>
                    </li>
                    <!--/ search form -->
                    <!-- search form -->
                    <li aria-haspopup="true" class="right">
                        <a href="#_">
                            <i class="fa fa-single fa-search"></i>
                        </a>
                        <div class="grid-container5">
                            <form action="#">
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-search"></i>
                                            <input type="text" placeholder="Search Site...">
                                        </label>
                                    </section>
                                    <button type="submit" class="btn btn-primary button">Search</button>
                                </fieldset>
                            </form>
                        </div>
                    </li>
                    <!--/ search form -->
                    <!-- login form -->
                    <li aria-haspopup="true" class="right">
                        <a href="#_">
                            <i class="fa fa-single fa-user"></i>
                        </a>
                        <div class="grid-container4">
                            <form action="#">
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-user"></i>
                                            <input type="text" placeholder="Login or E-mail">
                                        </label>
                                    </section>

                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-lock"></i>
                                            <input type="password" placeholder="Password">
                                        </label>
                                    </section>

                                    <button type="submit" class="btn btn-primary button">Login</button>
                                </fieldset>
                            </form>
                        </div>
                    </li>
                    <!--/ login form -->

                    <!-- search form -->
                    <li aria-haspopup="true" class="visible-xs">
                        <a href="#_">
                            <i class="fa fa-single fa-search li-last"></i>
                        </a>
                        <div class="grid-container5">
                            <form action="#">
                                <fieldset>
                                    <section>
                                        <label class="input">
                                            <i class="fa fa-append fa-search"></i>
                                            <input type="text" placeholder="Search Site...">
                                        </label>
                                    </section>
                                    <button type="submit" class="btn btn-primary button">Search</button>
                                </fieldset>
                            </form>
                        </div>
                    </li>
                </ul>

            </div>

        </div>
    </div>
</div>


<div class="clearfix nav-finish"></div>
<!-- End Header -->

@yield('content')

<!-- ======= Footer ======= -->
<footer class="footer">
    <div class="footer__center container">

        <div class="footer__container adjustbxflex row">
            <div class="footer__wrap"><a class="footer__logo" href="/"><img class="some-icon lazy_a"
                                                                            data-original="{{ asset('storage/'.$general->logo) }}"
                                                                            alt="Yes Soft"
                                                                            src="{{ asset('storage/'.$general->logo) }}"
                                                                            style=""></a>
                <div class="footer__text">{{$about->subject}}</div>
                <div class="footer__social">
                    <a class="footer__link" href="{{$general->facebook}}" target="_blank">
                        <img class="lazy_a" data-original="{{ asset('front/img/icons/facebook.svg') }}"
                             alt="Yes Soft Social Media" src="{{ asset('front/img/icons/facebook.svg') }}">
                        {{--<i class="fab fa-facebook-f fa-fw fa-2x"></i>--}}
                    </a>
                    <a class="footer__link" href="{{$general->twitter}}" target="_blank">
                        <img class="lazy_a" data-original="{{ asset('front/img/icons/Twitter.svg') }}"
                             alt="Yes Soft Social Media" src="{{ asset('front/img/icons/Twitter.svg') }}">
                    </a>
                    <a class="footer__link" href="{{$general->youtube}}" target="_blank">
                        <img class="lazy_a" data-original="{{ asset('front/img/icons/Youtube.svg') }}"
                             alt="Yes Soft Social Media" src="{{ asset('front/img/icons/Youtube.svg') }}">
                    </a>
                    <a class="footer__link" href="{{$general->linkedin}}" target="_blank">
                        <img class="lazy_a" data-original="{{ asset('front/img/icons/linkedin.svg') }}"
                             alt="Yes Soft Social Media" src="{{ asset('front/img/icons/linkedin.svg') }}">
                    </a>
                </div>
            </div>
            <div class="footer__row">
                <div class="footer__col">
                    <div class="footer__category">{{__('message.Products')}}</div>
                    <div class="footer__menu">
                        @foreach($product as $products)
                            <a class="footer__item" href="{{ $products->link }}">{{ $products->name }}</a>
                        @endforeach
                    </div>
                </div>
                {{--<div class="footer__col">--}}
                {{--<div class="footer__category">The Beta Lab</div>--}}
                {{--<div class="footer__menu"><a class="footer__item" href="/bulbul/">Bulbul</a><a class="footer__item" href="/flightmaphome/">Flightmap</a><a class="footer__item" href="/fugu/">Fugu</a><a class="footer__item" href="/husky/">Husky</a></div>--}}
                {{--</div>--}}
                <div class="footer__col">
                    <div class="footer__category">{{__('message.Quick Links')}}</div>
                    <div class="footer__menu">
                        <a class="footer__item" href="{{route('contact')}}">Contact Us</a>
                        <a class="footer__item" href="/services/">Services</a>
                        <a class="footer__item" href="{{route('about')}}">About Us</a>
                        <a class="footer__item" href="{{route('blog')}}">Blogs</a>
{{--                        @foreach($link as $link)--}}
{{--                            <a class="footer__item" href="{{$link->link}}" target="_blank">{{$link->name}}</a>--}}
{{--                        @endforeach--}}
                    </div>
                </div>

                <div class="footer__col">
                    <div class="footer__category">{{__('message.Quick Links')}} </div>
                    <div class="footer__menu">
                        <div class="addjwset">
                            <p class="addressSetjw opShow">
                                <span>Ger</span>
                                <i>{{$general->address1}}</i>
                            </p>
                            <p class="addressSetjw opShow">
                                <span>Brazil</span>
                                <i>{{$general->address1}}</i>
                            </p>
                            <p class="addressSetjw opShow">
                                <span>UAE</span>
                                <i>{{$general->address1}}</i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row adjustbxflex aligncenterflex copyRightjws">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 orderm2">
                <div class="footer__copyright">© 2022 YES SOFT. All rights reserved.</div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 orderm1">
                <p><a href="/terms-and-conditions/" class="footer__item">Terms &amp; Conditions</a> | <a
                        href="/privacy-policy/" class="footer__item">Privacy Policy</a></p>
            </div>
        </div>

    </div>
</footer><!-- End Footer -->
<!-- Slider Options -->
<script type="text/javascript">
    var revapi;
    jQuery(document).ready(function () {

        revapi = jQuery('.tp-banner').revolution({
            delay: 90000,
            startwidth: 1170,
            startheight: 500,
            hideThumbs: 10,
            fullScreen: "on",
            fullScreenAlignForce: "on",
            fullScreenOffsetContainer: ".navigation-menu"
        });

        revapi = jQuery('.tp-banner-two').revolution({
            delay: 9000000,
            startwidth: 1200,
            startheight: 356,

            hideThumbs: 200,

            thumbWidth: 100,
            thumbHeight: 50,
            thumbAmount: 3,

            navigationType: "none", // use none, bullet or thumb

            soloArrowLeftHalign: "left", // left,center,right
            soloArrowLeftValign: "center", // top,center,bottom
            soloArrowLeftHOffset: 0, // offset position from aligned position
            soloArrowLeftVOffset: 0, // offset position from aligned position

            soloArrowRightHalign: "right", // left,center,right
            soloArrowRightValign: "center", // top,center,bottom
            soloArrowRightHOffset: 0, // offset position from aligned position
            soloArrowRightVOffset: 0, // offset position from aligned position

            touchenabled: "on",
            onHoverStop: "on"
        });
    }); //ready
</script>
<!-- Portfolio Filter -->
<script type="text/javascript">
    $(function () {
        $('#Grid').mixitup();
    });
</script>
<!-- Carusel Slider -->
<script type="text/javascript">
    jQuery(document).ready(function () {

        jQuery('#example6').showbizpro({
            dragAndScroll: "off",
            visibleElementsArray: [4, 4, 1, 1],
            carousel: "on",
            entrySizeOffset: 0,
            allEntryAtOnce: "off",
            rewindFromEnd: "off",
            autoPlay: "off",
            delay: 2000,
            speed: 500
        });

        jQuery('#example7').showbizpro({
            dragAndScroll: "off",
            visibleElementsArray: [2, 2, 1, 1],
            carousel: "on",
            entrySizeOffset: 0,
            allEntryAtOnce: "off",
            rewindFromEnd: "off",
            autoPlay: "off",
            delay: 2000,
            speed: 500
        });
    });
</script>


<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('front/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('front/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('front/vendor/jquery-sticky/jquery.sticky.js') }}"></script>
<script src="{{ asset('front/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('front/vendor/venobox/venobox.min.js') }}"></script>
<script src="{{ asset('front/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('front/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('front/vendor/aos/aos.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('front/js/main.js') }}"></script>
<!-- Load JS here for greater good -->
<script src="{{ asset('front/js/jquery-ui-1.10.3.custom.min.js') }}"></script>
<script src="{{ asset('front/js/jquery.ui.touch-punch.min.js') }}"></script>
<script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('front/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('front/js/bootstrap-switch.js') }}"></script>
<script src="{{ asset('front/js/flatui-checkbox.js') }}"></script>
<script src="{{ asset('front/js/flatui-radio.js') }}"></script>
<script src="{{ asset('front/js/jquery.tagsinput.js') }}"></script>
<script src="{{ asset('front/js/jquery.placeholder.js') }}"></script>
<script src="{{ asset('front/js/bootstrap-typeahead.js') }}"></script>
<script src="{{ asset('front/js/application.js') }}"></script>
<script src="{{ asset('front/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('front/js/toucheffects.js') }}"></script>
<script src="{{ asset('front/js/jquery.mixitup.js') }}"></script>
<script src="{{ asset('front/rs-plugin/js/jquery.themepunch.plugins.min.js') }}"></script>
<script src="{{ asset('front/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>
<script src="{{ asset('front/showbizpro/js/jquery.themepunch.showbizpro.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('front/js/jquery.scrollUp.js') }}"></script>
<script src="{{ asset('front/js/theme.js') }}"></script>


{!! $general->tawkto !!}
@stack('scripts')
</body>
</html>
