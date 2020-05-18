<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="{{ url('home') }}">TCuit</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="{{ url('home') }}">Home </a>
            </li>
            {#            <li class="nav-item">#}
            {#                <a class="nav-link" href="/user/dashboard">Profiles</a>#}
            {#            </li>#}
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {% if username is defined %}
                        {{ username }}
                    {% else %}
                        Users
                    {% endif %}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href={{ url('user/dashboard') }}>Dashboard</a>
                    {% if user_id is defined %}
                        <a class="dropdown-item" href="{{ url('user/logout') }}">Logout</a>
                    {% endif %}
                </div>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <select class="search-ajax" style="width: 30vh">
            </select>
        </form>
    </div>
</nav>


