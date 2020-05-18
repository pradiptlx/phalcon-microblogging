{% extends 'layouts/base.volt' %}

{% block head %}
    {{ assets.outputCss('postCss') }}
{% endblock %}

{% block title %}{{ title }}{% endblock %}

{% block content %}
    {{ flashSession.output() }}
    {% if post.file is defined %}
        <header class="masthead" style="background-image: url({{ static_url(post.file.path) }})">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        {% if username === post.username %}
                            <button type="button"
                                    data-toggle="modal" data-target="#deletePost"
                                    class="btn btn-danger float-right">
                                Delete Post
                            </button>
                        {% endif %}
                        <div class="post-heading">
                            <h1>{{ post.title }}</h1>
                            {#                        <h2 class="subheading">Problems look mighty small from 150 miles up</h2>#}
                            <span class="meta">Posted by
              <a href="{{ url('user/findUser/'~post.username) }}">{{ post.fullname }}</a>
              on {{ post.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="deletePost" data-backdrop="static" tabindex="-1"
                 role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ url('/post/'~post.id~'/deletePost') }}" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Are you sure</h5>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="postId" value="{{ post.id }}">
                                This action is irreversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-danger">Sure</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </header>
    {% else %}
        <header class="masthead">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-10 mx-auto">
                        {% if username === post.username %}
                            <button type="button"
                                    data-toggle="modal" data-target="#deletePost"
                                    class="btn btn-danger float-right">
                                Delete Post
                            </button>
                        {% endif %}
                        <div class="post-heading">
                            <h1>{{ post.title }}</h1>
                            {#                        <h2 class="subheading">Problems look mighty small from 150 miles up</h2>#}
                            <span class="meta">Posted by
              <a href="{{ url('user/findUser/'~post.username) }}">{{ post.fullname }}</a>
              on {{ post.created_at }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="deletePost" data-backdrop="static" tabindex="-1"
                 role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ url('/post/'~post.id~'/deletePost') }}" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Are you sure</h5>
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="postId" value="{{ post.id }}">
                                This action is irreversible.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-danger">Sure</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </header>
    {% endif %}

    <article>
        <div class="container">
            <div class="row">
                <div class="col-6 my-3 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <p>{{ post.content }}</p>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            Reply
                            <button type="button" class="btn btn-secondary btn-sm float-right"
                                    data-toggle="modal" data-target="#newReply">
                                <i class="fas fa-plus-square"></i> Create
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="newReply" data-backdrop="static" tabindex="-1"
                                 role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{ url("/post/"~post.id~"/replyPost") }}"
                                          method="post">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">New Reply</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="titleInput">Title</label>
                                                    <input class="form-control" id="titleInput" name="title"/>
                                                </div>
                                                <div class="form-group">
                                                    <textarea maxlength="120" name="content" id="replyContent"
                                                              placeholder="What's on your mind?"></textarea>
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
                        </div>
                        <div class="card-body">
                            {% if replies is defined %}
                                {% for reply in replies %}
                                    <div class="card my-3 reply" id="{{ reply.postId|upper }}">
                                        <a class="card-header" href="#">
                                            {{ reply.repTitle }}
                                        </a>
                                        <div class="card-body">
                                            {{ reply.repContent }}
                                        </div>
                                        <div class="card-footer">
                                            <small> By: {{ reply.repFullname }} at {{ reply.repCreatedDate }}</small>
                                            <button type="button" class="float-right btn btn-sm"
                                                    id="replyButton" data-toggle="collapse"
                                                    data-target="#replyForm_{{ reply.repId }}"><i
                                                        class="fas fa-reply"></i> Reply
                                            </button>

                                            <div class="collapse" id="replyForm_{{ reply.repId }}">
                                                <div class="card card-body">
                                                    <form action="{{ url("/post/"~reply.postId~"/replyPost") }}"
                                                          method="post">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">New
                                                                    Reply</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="titleInput">Title</label>
                                                                    <input class="form-control" id="titleInput"
                                                                           name="title"/>
                                                                </div>
                                                                <div class="form-group">
                                                    <textarea maxlength="120" name="content" id="replyContent"
                                                              placeholder="What's on your mind?"></textarea>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">
                                                                    Close
                                                                </button>
                                                                <button type="submit" class="btn btn-primary">Submit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {% endfor %}
                            {% else %}
                                <div id="noReplyFound">No reply found</div>
                            {% endif %}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </article>

{% endblock %}

{% block js %}

    <script>
        $('.reply').onfocus(function () {
            alert('asd');
        });
    </script>

{% endblock %}
