{% extends 'layouts/base.volt' %}

{% block title %}{{title}}{% endblock %}

{% block head %}
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/vendor/animsition/css/animsition.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/vendor/daterangepicker/daterangepicker.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="/css/login/util.css">
    <link rel="stylesheet" type="text/css" href="/css/login/main.css">
{% endblock %}

{% block content %}
    {{ flashSession.output() }}
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" action="/user/register" enctype="multipart/form-data" method="post">
					<span class="login100-form-title p-b-34">
                        Create Account
					</span>

                    <div class="form-group">
                        <label for="usernameLabel">Username</label>
                        <input class="form-control" name="username" id="usernameLabel" placeholder="dexfrost">
                    </div>

                    <div class="form-group">
                        <label for="fullnameLabel">Full Name</label>
                        <input class="form-control" name="fullname" id="fullnameLabel"  placeholder="Dexfrost">
                    </div>

                    <div class="form-group">
                        <label for="emailLabel">Email address</label>
                        <input type="email" class="form-control" name="email" id="emailLabel" placeholder="name@example.com">
                    </div>

                    <div class="form-group">
                        <label for="passwordLabel">Password</label>
                        <input class="form-control" type="password" name="password" id="passwordLabel">
                    </div>

                    <div class="w-full text-center">
                        <button type="submit" class="text-3">Sign Up</button>
                    </div>
                </form>

                <div class="login100-more" style="background-image: url('/img/georgiana-sparks-1KkjeJgtOxE-unsplash.jpg');"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block js %}
    <script src="{{ static_url('/vendor/select2/select2.min.js') }}"></script>
    <script>
        $(".selection-2").select2({
            minimumResultsForSearch: 20,
            dropdownParent: $('#dropDownSelect1')
        });
    </script>
    <script src="{{ static_url('/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ static_url('/vendor/select2/select2.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ static_url('/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ static_url('/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ static_url('/vendor/countdowntime/countdowntime.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ static_url('/js/login/main.js') }}"></script>

{% endblock %}
