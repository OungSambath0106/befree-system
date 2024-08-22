<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item">
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        <!-- Language Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="flag-icon flag-icon-{{ ($current_locale == 'en') ? 'gb' : $current_locale }}"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">
                @foreach($available_locales as $locale_name => $available_locale)
                    @if($available_locale === $current_locale)
                        <a href="{{ route('change_language', $available_locale) }}" class="dropdown-item text-capitalize active">
                            <i class="flag-icon flag-icon-{{ ($available_locale == 'en') ? 'gb' : $available_locale }} mr-2"></i> {{ $locale_name }}
                        </a>
                    @else
                        <a href="{{ route('change_language', $available_locale) }}" class="dropdown-item text-capitalize">
                            <i class="flag-icon flag-icon-{{ ($available_locale == 'en') ? 'gb' : $available_locale }} mr-2"></i> {{ $locale_name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </li>

        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="                            
                @if (auth()->user()->image && file_exists(public_path('uploads/users/' . auth()->user()->image)))
                 {{ asset('uploads/users/'. auth()->user()->image) }}
                @else
                 {{ asset('uploads/default-profile.png') }}
                @endif" class="user-image img-circle elevation-2"
                    alt="User Image">
                {{-- <span class="d-none d-md-inline">veha</span> --}}
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                <!-- User image -->
                <li class="user-header text-white" style="background-color: #d0803d !important;">
                    {{-- <img src="{{ asset('uploads/default-profile.png') }}" class="img-circle elevation-2"
                        alt="User Image"> --}}
                        <img class="img-circle elevation-2" src="
                            @if (auth()->user()->image && file_exists(public_path('uploads/users/' . auth()->user()->image)))
                                {{ asset('uploads/users/'. auth()->user()->image) }}
                            @else
                                {{ asset('uploads/default-profile.png') }}
                            @endif
                            " alt="" height="100%">

                    <p>
                        <small>{{ Auth::user()->name }}</small>
                    </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <a href="{{ route('admin.show_info',auth()->user()->id) }}" class="btn btn-info  btn-flat"><i class="fa fa-user mr-2"></i>Profile</a>
                    <a href="{{ route('logout') }}" class="btn  btn-danger btn-flat float-right"><i class="fas fa-sign-out-alt mr-2"></i>Sign out</a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
