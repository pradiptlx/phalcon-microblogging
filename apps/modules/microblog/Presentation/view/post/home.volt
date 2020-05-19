{% extends 'layouts/base.volt' %}

{% block head %}
    {{ assets.outputCss('postCss') }}
{% endblock %}

{% block title %}{{ title }}{% endblock %}

{% block content %}
    {{ flashSession.output() }}
    <header class="masthead"
            style="background-image: url('{{ static_url('/img/georgiana-sparks-1KkjeJgtOxE-unsplash.jpg') }}')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>TCuit Blog</h1>
                        <span class="subheading">Simple Microblogging</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="row mx-2 my-3">
            <div class="col-6 mx-auto ">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <form action="{{ url('post/createPost') }}" method="post" enctype="multipart/form-data">
                            <input type='hidden' name='<?php echo $this->security->getTokenKey() ?>'
                                   value='<?php echo $this->security->getToken() ?>'/>
                            <div class="form-group">
                                <label for="titleArea">Title</label>
                                <input class="form-control" id="titleArea" name="title"/>
                            </div>
                            <div class="form-group">
                                <label for="contentArea">What's Happening?</label>
                                <textarea class="form-control" id="contentArea" name="content"
                                          maxlength="120"></textarea>
                            </div>

                            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-paper-plane"></i>
                                Post!
                            </button>
                            <small class="ml-3" style="font-size: small">Max. 120 Characters.</small>
                            <button id="buttonFile" type="button"
                                    class="btn btn-primary btn-sm float-right" data-toggle="modal"
                                    data-target="#modalFile"><i class="fas fa-image"
                                ></i></button>

                            <!-- Modal -->
                            <div class="modal fade" id="modalFile" data-backdrop="static" tabindex="-1" role="dialog"
                                 aria-labelledby="modalFileLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalFileLabel">Attach File</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="fileContent" id="fileContentLabel">Pick a file</label>
                                                <input type="file" name="files[]" class="form-control-file"
                                                       id="fileContent">
                                            </div>
                                            <div class="container" id="containerFileImg">
                                                <img src="#" id="fileImg" alt="#" class="w-75"/>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button id="closeFileBtn" type="button" class="btn btn-secondary">Cancel
                                            </button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Save
                                                changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="row mx-2 my-3">
            {#            <div class="card-deck">#}
            {% for index, post in posts %}
                <div class="col-6 mx-auto">
                    <div class="post-preview">
                        {% if post.file is defined %}
                            <div class="card my-2 no-gutters" style="max-width: 540px">
                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="{{ static_url(post.file.path) }}"
                                             alt="{{ post.file.filename }}" class="card-img"
                                        />
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card-body">
                                            <a href="{{ url('post/viewPost/'~post.id) }}">
                                                <h4 class="post-title">
                                                    {{ post.title }}
                                                </h4>
                                            </a>
                                            <p class="post-subtitle">
                                                {{ post.content }}
                                            </p>
                                            <small class="post-meta">Posted by
                                                <a href="{{ url('user/findUser/'~post.username) }}">{{ post.fullname }}</a>
                                                <small>{{ post.created_at }}</small></small>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-sm"
                                                    id="replyButton" data-toggle="collapse"
                                                    data-target="#replyForm_{{ post.id }}">
                                                <i class="fas fa-reply"></i> Reply
                                            </button>

                                            {% if post.replyCounter != 0 %}
                                                <button type="button" class="btn float-right btn-secondary btn-sm">
                                                    Replies <span
                                                            class="badge badge-light">{{ post.replyCounter }}</span>
                                                    <span class="sr-only">replies</span>
                                                </button>
                                            {% endif %}

                                            <div class="collapse" id="replyForm_{{ post.id }}">
                                                <div class="card card-body" style="max-width: 475px">
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
                                                                    <textarea maxlength="120" style="max-width: 80%;" name="content" id="replyContent"
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
                                    </div>
                                </div>
                            </div>

                        {% else %}
                            <div class="card my-2">
                                <div class="card-body">
                                    <a href="{{ url('post/viewPost/'~post.id) }}">
                                        <h4 class="post-title">
                                            {{ post.title }}
                                        </h4>
                                    </a>
                                    <p class="post-subtitle">
                                        {{ post.content }}
                                    </p>
                                    <small class="post-meta">Posted by
                                        <a href="{{ url('user/findUser/'~post.username) }}">{{ post.fullname }}</a>
                                        <small>{{ post.created_at }}</small></small>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-sm"
                                            id="replyButton" data-toggle="collapse"
                                            data-target="#replyForm_{{ post.id }}">
                                        <i class="fas fa-reply"></i> Reply
                                    </button>

                                    {% if post.replyCounter != 0 %}
                                        <button type="button" class="btn float-right btn-secondary btn-sm">
                                            Replies <span class="badge badge-light">{{ post.replyCounter }}</span>
                                            <span class="sr-only">replies</span>
                                        </button>
                                    {% endif %}

                                    <div class="collapse" id="replyForm_{{ post.id }}">
                                        <div class="card card-body">
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
                            </div>
                        {% endif %}


                    </div>

                </div>
            {% endfor %}

            <!-- Pager -->
            {#<div class="clearfix m-3">
                <a class="btn btn-primary float-right" href="#">Older Posts &rarr;</a>
            </div>#}
        </div>
        {% if totalPost is defined %}
            <button disabled type="button" class="btn btn-primary clearfix m-3">
                Total Post <span class="badge badge-light">{{ totalPost }}</span>
            </button>
        {% endif %}
        {#        </div>#}
    </div>

{% endblock %}

{% block js %}
    <script>
        $(document).ready(() => {
            $('#fileContent').change(() => {
                let file = $('#fileContent')[0].files[0];
                let type = file.type;
                if (type.split("/")[0] === 'image') {
                    let reader = new FileReader();
                    reader.onload = e => {
                        $('#fileImg').attr('src', e.target.result)
                            .attr('alt', "Filename: " + file.name);
                    }
                    reader.readAsDataURL(file);
                }
                console.log(file);

            })
            $('#closeFileBtn').click(function () {
                $('#fileContent').val(null);
                $('#fileImg').removeAttr('src');
                $('#modalFile').modal('hide');
            });
        });

    </script>

{% endblock %}
