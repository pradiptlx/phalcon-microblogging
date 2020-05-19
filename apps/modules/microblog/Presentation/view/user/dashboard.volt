{% extends 'layouts/base.volt' %}

{% block head %}
    {{ assets.outputCss('dashboardCss') }}
{% endblock %}

{% block title %}{{ title }}{% endblock %}

{% block content %}
    {{ flashSession.output() }}
    <div class="container">
        <div class="row profile">
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <!-- SIDEBAR USERPIC -->
                    <div class="profile-userpic">
                        <img src="{{ static_url('/img/profilepic.jpg') }}"
                             class="img-responsive float-none" alt="">
                    </div>
                    <!-- END SIDEBAR USERPIC -->
                    <!-- SIDEBAR USER TITLE -->
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name">
                            {{ user.getFullname() }}
                        </div>
                        <div class="profile-usertitle-job">
                            @{{ user.getUsername() }}
                        </div>
                    </div>
                    <!-- END SIDEBAR USER TITLE -->
                    <!-- SIDEBAR BUTTONS -->
                    <div class="profile-userbuttons">
                        {% if self === true %}
                            <a role="button" href="{{ url('/user/logout') }}" class="btn btn-success btn-sm">
                                Logout
                            </a>
                            <button type="button" class="btn btn-danger btn-sm"
                                    data-toggle="modal" data-target="#resetPassModal">Change Password
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="resetPassModal" data-backdrop="static" tabindex="-1"
                                 role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ url('/user/resetPassword') }}" method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Reset Password</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="inputOldPassword">Old Password</label>
                                                    <input type="password" id="inputOldPassword"
                                                           name="oldPassword" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="inputNewPassword">New Password</label>
                                                    <input type="password" id="inputNewPassword"
                                                           name="newPassword" class="form-control">
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        {% else %}
                            <a tabindex="0" role="button" class="btn btn-success btn-sm text-white" data-toggle="popover"
                                    title="Feature not ready yet."
                                    data-placement="top" data-trigger="focus"
                                    data-content="I'm sorry, the feature is not ready yet because no table db implemented.">
                                Follow
                            </a>
                            <a tabindex="0" role="button" class="btn btn-danger btn-sm text-white" data-toggle="popover"
                                    title="Feature not ready yet."
                                    data-placement="top" data-trigger="focus"
                                    data-content="I'm sorry, the feature is not ready yet because no table db implemented.">
                                Message
                            </a>
                        {% endif %}
                    </div>
                    <!-- END SIDEBAR BUTTONS -->
                    <!-- SIDEBAR MENU -->
                    <div class="profile-usermenu">
                        <ul class="nav flex-column">
                            <li class="nav-item active" id="liHome">
                                <a href="#" class="nav-link" id="homeLink">
                                    <i class="fas fa-home"></i>
                                    Overview
                                </a>
                            </li>

                            {% if (self == true) %}
                                <li class="nav-item" id="liAcc">
                                    <a href="#account" class="nav-link" id="accountSettingsLink">
                                        <i class="fas fa-user-circle"></i>
                                        Account Settings
                                    </a>
                                </li>
                            {% endif %}

                        </ul>
                    </div>
                    <!-- END MENU -->
                </div>
            </div>
            <div class="col-md-9" id="home">
                <div class="profile-content">
                    {% for post in posts %}
                        <div class="card my-3 no-gutters" id="userPost">
                            <div class="card-header">
                                <a href="{{ url('/post/viewPost/'~post.id) }}">
                                    {{ post.title }}
                                </a>

                                {% if self == true %}
                                    <form class="float-right" action="{{ url('/post/'~post.id~'/deletePost') }}"
                                          method="post">
                                        <input type="hidden" name="postId" value="{{ post.id }}">
                                        <button type="submit" class="btn btn-sm btn-danger float-right"><i
                                                    class="fas fa-trash"></i></button>
                                    </form>
                                {% endif %}
                            </div>
                            <div class="card-body">
                                {{ post.content }}
                            </div>
                        </div>

                    {% endfor %}
                </div>
            </div>

            <div class="col-md-9" id="account" hidden>
                <div class="profile-content">
                    <div class="card">
                        <div class="card-header">Account Settings</div>
                        <div class="card-body">
                            <form action="{{ url('/user/accountSettings') }}" method="post">
                                <div class="form-group">
                                    <label for="usernameLabel">Username</label>
                                    <input class="form-control" name="username" id="usernameLabel"
                                           placeholder="{{ user.getUsername() }}">
                                </div>

                                <div class="form-group">
                                    <label for="fullnameLabel">Full Name</label>
                                    <input class="form-control" name="fullname" id="fullnameLabel"
                                           placeholder="{{ user.getFullname() }}">
                                </div>

                                <div class="form-group">
                                    <label for="emailLabel">Email address</label>
                                    <input type="email" class="form-control" name="email" id="emailLabel"
                                           placeholder="{{ user.getEmail() }}">
                                </div>

                                <div class="form-group">
                                    <label for="inputOldPassword">Old Password</label>
                                    <input type="password" id="inputOldPassword" class="form-control"
                                           name="oldPassword">
                                </div>

                                <div class="form-group">
                                    <label for="inputNewPassword">New Password</label>
                                    <input type="password" id="inputNewPassword" class="form-control"
                                           name="newPassword">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">Change Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block js %}

    <script>
        $(document).ready(() => {

            let accSet = $('#accountSettingsLink');
            let homeSet = $('#homeLink');

            accSet.click(function () {
                $('#home').attr('hidden', true);
                $('#account').attr('hidden', false);
                $('#liAcc').attr('class', 'active');
                $('#liHome').removeAttr('class', 'active');
            });

            homeSet.click(function () {
                $('#home').attr('hidden', false);
                $('#account').attr('hidden', true);
                $('#liHome').attr('class', 'active');
                $('#liAcc').removeAttr('class', 'active');
            });
        });
    </script>

{% endblock %}
