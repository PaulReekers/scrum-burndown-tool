<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header col-md-3">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand pull-left" href="{{ url('/') }}">
                <img src="/images/burndown-logo.svg" class="img-responsive" alt="Burndown">
            </a>


        </div>

        @if (! empty($chart))
            <div class="burndown-title col-md-3 col-md-offset-1">
                {{ $chart->sprintname }}
            </div>
        @endif

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right pull-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/board">Boards</a>
                            </li>
                            <li>
                                <a href="/register">Register new user</a>
                            </li>
                            <li>
                                <a href="/password/change">Change password</a>
                            </li>
                            <li>
                                <a href="{{ url('/logout') }}"
                                    onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
