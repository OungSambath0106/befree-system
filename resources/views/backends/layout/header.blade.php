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
            {{-- <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a> --}}
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

        <!-- Messages Dropdown Menu -->
        {{-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../../dist/img/user1-128x128.jpg" alt="User Avatar"
                            class="img-size-50 mr-3 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../../dist/img/user8-128x128.jpg" alt="User Avatar"
                            class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../../dist/img/user3-128x128.jpg" alt="User Avatar"
                            class="img-size-50 img-circle mr-3">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li> --}}
        <!-- Notifications Dropdown Menu -->
        {{-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> --}}
        <li class="nav-item dropdown">
            <a class="nav-link" href="{{ route('homepage') }}" target="_blank">
                {{-- <i class=" far fa-internet-explorer"></i> --}}
                @include('svgs.internet')
                {{ __('Go to website') }}
            </a>
        </li>

        {{-- notification --}}
        {{-- <li class="nav-item dropdown notification_wrapper">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge number_notification">{{ $notifications->total() }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" bis_skin_checked="1"
                style="left: inherit; right: 0px;">
                <span class="dropdown-item dropdown-header">{{ $notifications->total() }} {{ __('Notifications') }}</span>
                    <div class="dropdown-divider" bis_skin_checked="1"></div>

                @forelse ($notifications as $notification)
                    <div class="dropdown-divider" bis_skin_checked="1"></div>
                    <a href="{{ route('admin.notification.index') }}" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> {{ $notification->title }}
                        <br>
                        <span>{{ $notification->message }}</span>
                        <span class="float-right text-muted text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                    </a>
                @empty
                    <div class="dropdown-divider" bis_skin_checked="1"></div>
                    <a href="#" class="dropdown-item dropdown-footer">
                        {{ __('No notification') }}
                    </a>
                @endforelse

                @if ($notifications->total() > 0)
                    <div class="dropdown-divider" bis_skin_checked="1"></div>
                    <a href="{{ route('admin.notification.index') }}" class="dropdown-item dropdown-footer">{{ __('See All Notifications') }}</a>
                @endif
            </div>
        </li> --}}

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
