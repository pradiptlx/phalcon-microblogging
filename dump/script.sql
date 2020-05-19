create table files_manager
(
    id        uniqueidentifier not null
        constraint files_manager_pk
            primary key nonclustered,
    file_name varchar(max)     not null,
    path      nvarchar(max)    not null,
    post_id   nvarchar(max)    not null
)
go

create table following
(
    id            uniqueidentifier not null
        constraint following_pk
            primary key nonclustered,
    user_id_main  nvarchar(max)    not null,
    user_id_other int              not null,
    created_at    datetime
)
go

exec sp_addextendedproperty 'MS_Description', 'Only contains user following others not the relationship', 'SCHEMA',
     'dbo', 'TABLE', 'following'
go

create unique index following_id_uindex
    on following (id)
go

create table posts
(
    id             uniqueidentifier not null
        constraint posts_pk
            primary key nonclustered,
    title          varchar(150)     not null,
    content        nvarchar(max),
    repost_counter int default 0,
    created_at     datetime         not null,
    updated_at     datetime,
    user_id        nvarchar(max)    not null,
    share_counter  int default 0,
    reply_counter  int default 0,
    isReply        tinyint
)
go

create table reply
(
    id               int identity
        constraint reply_pk
            primary key nonclustered,
    post_id          varchar(max) not null,
    original_post_id varchar(max) not null
)
go

create table reply_post
(
    id         uniqueidentifier not null
        constraint reply_post_pk
            primary key nonclustered,
    content    nvarchar(max),
    post_id    varchar(max)     not null,
    user_id    varchar(max)     not null,
    created_at datetime         not null,
    updated_at datetime
)
go

create table users
(
    id         uniqueidentifier not null
        constraint users_pk
            primary key nonclustered,
    username   varchar(50)      not null,
    fullname   varchar(50),
    email      varchar(50)      not null,
    password   nvarchar(max)    not null,
    role_id    nvarchar(max)    not null,
    created_at datetime         not null,
    updated_at datetime
)
go

create unique index users_email_uindex
    on users (email)
go

create unique index users_username_uindex
    on users (username)
go


