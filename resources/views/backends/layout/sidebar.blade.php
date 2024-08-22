<style>
</style>
<aside class="main-sidebar elevation-4 sidebar-light-info" style="">
    <!-- Brand Logo -->
    @php
        $setting = App\Models\BusinessSetting::all();
        $web_header_logo = $setting->where('type', 'web_header_logo')->first()->value ?? '';
    @endphp
    <a href="{{ route('admin.highlight.index') }}" class="brand-link" style="">
        <img src="@if ($web_header_logo && file_exists('uploads/business_settings/' . $web_header_logo)) {{ asset('uploads/business_settings/' . $web_header_logo) }}
        @else
            {{ asset('uploads/image/default.png') }} @endif"
            alt="AdminLTE Logo" class="brand-image"
            style="width: 100%;
      object-fit: contain;margin-left: 0; height: 100px;max-height: 72px;">
        {{-- <img src="
      @if ($web_header_logo && file_exists('uploads/business_settings/' . $web_header_logo))
          {{ asset('uploads/business_settings/'. $web_header_logo) }}
      @else
            {{ asset('uploads/company-logo.png') }}
      @endif
      " alt="" class="brand-image" style="width: 100%;
      object-fit: contain;margin-left: 0; height: 100px;max-height: 72px;">  --}}
        {{-- <span class="brand-text font-weight-light">ONLINE SHOP</span> --}}
    </a>



    <!-- Sidebar -->
    <div class="sidebar sidebar-light-primary os-theme-dark">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link @if (request()->routeIs('admin.dashboard')) active @endif">
                        @include('svgs.dashboard')
                        <p>
                            {{ __('Dashboard') }}

                        </p>
                    </a>
                </li> --}}

                @if (auth()->user()->can('highlight.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.highlight.index') }}"
                            class="nav-link @if (request()->routeIs('admin.highlight.*')) active @endif">
                            <i class="nav-icon fas fa-ad"></i>
                            {{-- @include('svgs.blog') --}}
                            <p>
                                {{ __('Highlight') }}
                            </p>
                        </a>
                    </li>
                @endif

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

                {{-- @if (auth()->user()->can('customer.view') || auth()->user()->can('contact.view'))
                    <li class="nav-item @if (request()->routeIs('admin.customer*', 'admin.contact*')) menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin.customer*', 'admin.contact*')) active @endif">
                            @include('svgs.customer')
                            <p>
                                {{ __('Customer') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                            @php
                                $contact_count = \App\helpers\GlobalFunction::countNotification('ContactUs');
                            @endphp
                            <span
                                class=" badge badge-danger contact_badge @if ($contact_count == 0) d-none @endif ">{{ \App\helpers\GlobalFunction::countNotification('ContactUs') }}</span>

                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('customer.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.customer.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.customer*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Customers') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('contact.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.contact.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.contact*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>{{ __('Customer Contact') }}</p>
                                        @php
                                            $contact_count = \App\helpers\GlobalFunction::countNotification(
                                                'ContactUs',
                                            );
                                        @endphp

                                        <span
                                            class=" badge badge-danger contact_badge @if ($contact_count == 0) d-none @endif">{{ \App\helpers\GlobalFunction::countNotification('ContactUs') }}</span>

                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif --}}

                {{-- @if (auth()->user()->can('room.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.room.index') }}"
                            class="nav-link @if (request()->routeIs('admin.room.*')) active @endif">
                            @include('svgs.bed')
                            <p>
                                {{ __('Room') }}
                            </p>
                        </a>
                    </li>
                @endif --}}

                @if (auth()->user()->can('staycation.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.staycation.index') }}"
                            class="nav-link @if (request()->routeIs('admin.staycation.*')) active @endif">
                            {{-- <i class="nav-icon fa-solid fa-hotel"></i> --}}
                            @include('svgs.blog')
                            <p>
                                {{ __('Staycation') }}
                            </p>
                        </a>
                    </li>
                @endif

                {{-- <@if (auth()->user()->can('menu.view') || auth()->user()->can('explore.menu.view'))
                <li class="nav-item @if (request()->routeIs('admin.menu*', 'admin.explore_menu*')) menu-is-opening menu-open  @endif">
                    <a href="#" class="nav-link @if (request()->routeIs('admin.menu*', 'admin.explore_menu*')) active @endif">
                        @include('svgs.menu')
                        <p>
                            {{ __('Menu Setup') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->can('menu.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.menu.index') }}" class="nav-link @if (request()->routeIs('admin.menu*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>
                                    {{ __('Menu') }}
                                </p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('explore.menu.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.explore_menu.index') }}" class="nav-link @if (request()->routeIs('admin.explore_menu*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>{{ __('Menu Explore') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.section_title.index') }}" class="nav-link @if (request()->routeIs('admin.section_title*')) active @endif">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            {{ __('Section Title') }}
                        </p>
                    </a>
                </li> --}}
                {{-- @if (auth()->user()->can('menu.view') || auth()->user()->can('explore.menu.view'))
                <li class="nav-item @if (request()->routeIs('admin.pages*', 'admin.section_title*')) menu-is-opening menu-open  @endif">
                    <a href="#" class="nav-link @if (request()->routeIs('admin.pages*', 'admin.section_title*')) active @endif">

                        @include('svgs.menu')
                        <p>
                            {{ __('Section Title Setup') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->can('menu.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.pages.index') }}" class="nav-link @if (request()->routeIs('admin.pages*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>
                                    {{ __('Page') }}
                                </p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('explore.menu.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.section_title.index') }}" class="nav-link @if (request()->routeIs('admin.section_title*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>{{ __('Sections') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif --}}
                {{-- @if (auth()->user()->can('slider.view'))
                <li class="nav-item">
                    <a href="{{ route('admin.slider.index') }}" class="nav-link @if (request()->routeIs('admin.slider*')) active @endif">
                        @include('svgs.slider')
                        <p>
                            {{ __('Slider') }}
                        </p>
                    </a>
                </li>
                @endif --}}

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.room.availability.index') }}" class="nav-link @if (request()->routeIs('admin.room.availability*')) active @endif">
                        <i class="nav-icon fa-solid fa-calendar"></i>
                        <p>
                            {{ __('Room Calendar') }}
                        </p>
                    </a>
                </li> --}}
                @if (auth()->user()->can('gallery.view') || auth()->user()->can('gallery-category.view'))
                    <li class="nav-item @if (request()->routeIs('admin.gallery*', 'admin.gallery-category*')) menu-is-opening menu-open @endif">
                        <a href="#" class="nav-link @if (request()->routeIs('admin.gallery*', 'admin.gallery-category*')) active @endif">
                            @include('svgs.gallery')
                            <p>
                                {{ __('Gallery') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (auth()->user()->can('gallery.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.gallery.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.gallery*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Galleries') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->can('gallery-category.view'))
                                <li class="nav-item">
                                    <a href="{{ route('admin.gallery-category.index') }}"
                                        class="nav-link @if (request()->routeIs('admin.gallery-category*')) active @endif">
                                        <i class="fa-solid fa-circle nav-icon"></i>
                                        <p>
                                            {{ __('Gallery Categories') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->can('amenity.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.amenities.index') }}"
                            class="nav-link @if (request()->routeIs('admin.amenities*')) active @endif">
                            {{-- <i class="nav-icon fas fa-briefcase"></i> --}}
                            @include('svgs.amenity')
                            <p>
                                {{ __('Amenities') }}
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (auth()->user()->can('service.view') || auth()->user()->can('extra_service.view'))
                <li class="nav-item @if (request()->routeIs('admin.services*', 'admin.extra-service*')) menu-is-opening menu-open  @endif">
                    <a href="#" class="nav-link @if (request()->routeIs('admin.services*', 'admin.extra-service*')) active @endif">
                        @include('svgs.service')
                        <p>
                            {{ __('Service Setup') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->can('service.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.services.index') }}" class="nav-link @if (request()->routeIs('admin.services*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>
                                    {{ __('Service') }}
                                </p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('extra_service.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.extra-service.index') }}" class="nav-link @if (request()->routeIs('admin.extra-service*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>{{ __('Extra Service') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif --}}
                @if (auth()->user()->can('facility.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.facility.index') }}"
                            class="nav-link @if (request()->routeIs('admin.facility*')) active @endif">
                            {{-- <i class="nav-icon fas fa-swimming-pool"></i> --}}
                            @include('svgs.facility')
                            <p>
                                {{ __('Facilities') }}
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (auth()->user()->can('blog.view') || auth()->user()->can('category.view') || auth()->user()->can('tag.view') || auth()->user()->can('comment.view'))
                 <li class="nav-item @if (request()->routeIs('admin.blog*', 'admin.comment*')) menu-is-opening menu-open @endif">
                    <a href="#" class="nav-link @if (request()->routeIs('admin.blog*', 'admin.comment*')) active @endif">
                        @include('svgs.blog')
                        <p>
                            {{ __('Blog Setup') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        @php
                            $comment_count = \App\helpers\GlobalFunction::countNotification('Comment')
                        @endphp
                            <span class=" badge badge-danger comment_badge @if ($comment_count == 0)d-none @endif">{{ \App\helpers\GlobalFunction::countNotification('Comment') }}</span>

                    </a>
                    <ul class="nav nav-treeview">
                        @if (auth()->user()->can('blog.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog.index') }}" class="nav-link @if (request()->routeIs('admin.blog.*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>{{ __('Blogs') }}</p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('category.view'))
                        <li class="nav-item">
                            <a href="{{ route('admin.blog-category.index') }}" class="nav-link @if (request()->routeIs('admin.blog-category*')) active @endif">
                                <i class="fa-solid fa-circle nav-icon"></i>
                                <p>{{ __('Category') }}</p>
                            </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('tag.view'))
                        <li class="nav-item">
                        <a href="{{ route('admin.blog-tag.index') }}" class="nav-link @if (request()->routeIs('admin.blog-tag*')) active @endif">
                            <i class="fa-solid fa-circle nav-icon"></i>
                            <p>
                                {{ __('Blog Tags') }}
                            </p>
                        </a>
                        </li>
                        @endif
                        @if (auth()->user()->can('comment.view'))
                        <li class="nav-item">
                        <a href="{{ route('admin.comment.index') }}" class="nav-link @if (request()->routeIs('admin.comment*')) active @endif">
                            <i class="fa-solid fa-circle nav-icon"></i>
                            <p>
                                {{ __('Comments') }}
                            </p>
                            @php
                                $comment_count = \App\helpers\GlobalFunction::countNotification('Comment')
                            @endphp

                                <span class=" badge badge-danger comment_badge @if ($comment_count == 0)d-none @endif">{{ \App\helpers\GlobalFunction::countNotification('Comment') }}</span>

                        </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif --}}
                {{-- @if (auth()->user()->can('comment.view'))
                    <li class="nav-item">
                        <a href="{{ route('admin.comment.index') }}"
                            class="nav-link @if (request()->routeIs('admin.comment*')) active @endif">
                            <i class="far fa-comments nav-icon"></i>
                            <p>
                                {{ __('Comments') }}
                            </p>
                            @php
                                $comment_count = \App\helpers\GlobalFunction::countNotification('Comment');
                            @endphp

                            <span
                                class=" badge badge-danger comment_badge @if ($comment_count == 0) d-none @endif">{{ \App\helpers\GlobalFunction::countNotification('Comment') }}</span>
                        </a>
                    </li>
                @endif --}}

                {{-- @if (auth()->user()->can('booking_report'))
                    <li class="nav-item">
                        <a href="{{ route('admin.booking_report') }}"
                            class="nav-link @if (request()->routeIs('admin.booking_report*')) active @endif">
                            @include('svgs.report')
                            <p>
                                {{ __('Booking Report') }}
                            </p>
                            @php
                                $transaction_count = \App\helpers\GlobalFunction::countNotification('Transaction');
                            @endphp

                            <span
                                class=" badge badge-danger booking_badge @if ($transaction_count == 0) d-none @endif">{{ \App\helpers\GlobalFunction::countNotification('Transaction') }}</span>

                        </a>
                    </li>
                @endif --}}

                {{-- <li class="nav-item">
                    <a href="{{ route('admin.booking_report') }}" class="nav-link @if (request()->routeIs('admin.booking_report*')) active @endif">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            {{ __('Booking Report') }}
                        </p>
                    </a>
                </li> --}}

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
