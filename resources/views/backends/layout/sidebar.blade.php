<style>
</style>
<aside class="main-sidebar elevation-4 sidebar-light-info" style="">
    <!-- Brand Logo -->
    @php
        $setting = App\Models\BusinessSetting::all();
        $web_header_logo = $setting->where('type', 'web_header_logo')->first()->value ?? '';
    @endphp
    <a href="{{ route('admin.dashboard') }}" class="brand-link" style="">
        <img src="@if ($web_header_logo && file_exists('uploads/business_settings/' . $web_header_logo)) {{ asset('uploads/business_settings/' . $web_header_logo) }}
        @else
            {{ asset('uploads/image/default.png') }} @endif"
            alt="AdminLTE Logo" class="brand-image"
            style="width: 100%;
      object-fit: contain;margin-left: 0; height: 100px;max-height: 72px;">
    </a>



    <!-- Sidebar -->
    <div class="sidebar sidebar-light-primary os-theme-dark">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif">
                        @include('svgs.dashboard')
                        <p>
                            {{ __('Dashboard') }}

                        </p>
                    </a>
                </li>

                @if (auth()->user()->can('user.view') || auth()->user()->can('role.view'))
                    <li class="nav-item @if (request()->routeIs('admin.user*', 'admin.roles*')) menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin.user*', 'admin.roles*')) active @endif">
                            {{-- <i class="nav-icon fa fa-users"></i> --}}
                            @include('svgs.users')
                            <p>
                                {{ __('User Management') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('user.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.user.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.user*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Users') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('role.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.roles*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Role') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (auth()->user()->can('banner.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.baner-slider.index') }}"
                            class="nav-link @if (request()->routeIs('admin.baner-slider*')) active @endif">
                            @include('svgs.gallery')
                            <p>
                                {{ __('Baner Slider') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('onboard.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.onboard.index') }}"
                            class="nav-link @if (request()->routeIs('admin.onboard*')) active @endif">
                            @include('svgs.gallery')
                            <p>
                                {{ __('OnBoard') }}
                            </p>
                        </a>
                    </li>
                @endif
                
                @if (auth()->user()->can('promotion.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.promotion.index') }}"
                            class="nav-link @if (request()->routeIs('admin.promotion*')) active @endif">
                            @include('svgs.gallery')
                            <p>
                                {{ __('Promotion') }}
                            </p>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->can('setting.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.setting.index') }}"
                            class="nav-link @if (request()->routeIs('admin.setting*')) active @endif">
                            {{-- <i class="nav-icon fas fa-cog"></i> --}}
                            @include('svgs.setting')
                            <p>
                                {{ __('Setting') }}
                            </p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
