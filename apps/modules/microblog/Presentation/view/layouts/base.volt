<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/css/all.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/vendor/animate/animate.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/vendor/css-hamburgers/hamburgers.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/vendor/animsition/css/animsition.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/vendor/select2/select2.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ static_url('/vendor/daterangepicker/daterangepicker.css') }}">
    <!--===============================================================================================-->
    {% block head %}
    {% endblock %}
    <title>{% block title %}{% endblock %} - Simple Microblog</title>
</head>


<body>
<div class="container-fluid m-2">
    {% include 'layouts/navbar.volt' %}
</div>

<div class="container-fluid" id='content'>{% block content %}{% endblock %}</div>

{#        <div id='footer'>#}
{#            {% block footer %}#}
{#                &copy; Copyright 2020.#}
{#                All rights reserved.#}
{#            {% endblock %}#}
{#        </div>#}


<script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script src="{{ static_url('/vendor/select2/select2.min.js') }}"></script>
<script defer src="{{ static_url('/js/all.min.js') }}"></script> <!--load all styles -->
<script>
    $(document).ready(() => {

        setTimeout(function () {
            $('.alert').alert('close');
        }, 3000);

        $(function () {
            $('[data-toggle="popover"]').popover()
        });

        $('.popover-dismiss').popover({
            trigger: 'focus'
        });

        $('.alert').click(function () {
            $('.alert').alert('close')
        });
    });

    $('.search-ajax').select2({
        ajax: {
            url: '{{ this.url.getBaseUri() }}user/findUser',
            dataType: 'json',
            data: function (params) {
                return {
                    q: params.term, // search term
                };
            },
            processResults: function (data) {
                console.log(data);
                return {
                    results: data.results
                };
            }
        },
        minimumInputLength: 1,
        placeholder: 'Search for username',
    });

    $('.search-ajax').on('select2:select', function (e) {
        window.location.href = "{{ this.url.getBaseUri() }}user/dashboard/"+e.params.data.id;
    });
</script>
{% block js %}
{% endblock %}
</body>
</html>
