{% extends 'layouts/base.volt' %}


{% block head %}
    {{ assets.outputCss('headerCss') }}
{% endblock %}

{% block title %}{{title}}{% endblock %}

{% block content %}

    {{ flashSession.output() }}

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post" action="{{ url('microblog/user/login') }}">
					<span class="login100-form-title p-b-34">
						Account Login
					</span>

                    <div class="wrap-input100 rs1-wrap-input100 validate-input m-b-20" data-validate="Type user name">
                        <input id="first-name" class="input100" type="text" name="username" placeholder="User name">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 rs2-wrap-input100 validate-input m-b-20" data-validate="Type password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn my-2" type="submit">
                            Sign in
                        </button>
                    </div>

                    {#<div class="w-full text-center p-t-27 p-b-239">
						<span class="txt1">
							Forgot
						</span>

                        <a href="{{ url('user/forgot') }}" class="txt2">
                            User name / password?
                        </a>
                    </div>#}

                    <div class="container-login100-form-btn">
                        <a href="{{ url('user/register')}}" class="login100-form-btn my-3 mx-0">
                            Sign Up
                        </a>
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
