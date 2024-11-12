@extends('backends.master')
@section('page_title')
    Admin Dashboard
@endsection
@push('css')
    <style>
        .small-box p {
            font-size: 0.9rem;
        }

        .dashboard_event_table tr th {
            background: #d4e0ff !important;
            color: #3d95d0 !important;
            text-transform: uppercase;
        }

        table td {
            height: 75.5px
        }
    </style>
@endpush
@section('contents')
    <section class="px-3">
        <div class="py-3">
            {{-- <h2>Wellcome to , {{ session()->get('company_name') }}</h2> --}}
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div
                    class="small-box bg-white d-flex p-3 justify-content-between align-items-center dashboard_summary_box dashboard_shadow">
                    <div class="rounded-circle bg-light p-2" style="height: 70px; width: 70px;">
                        <div style="padding:7px;">
                            <svg width="44px" height="44px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="6" r="4" stroke="#3d95d0" stroke-width="1.5"/>
                            <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="#3d95d0" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M5.88915 20.5843C6.82627 20.8504 7.88256 21 9 21C12.866 21 16 19.2091 16 17C16 14.7909 12.866 13 9 13C5.13401 13 2 14.7909 2 17C2 17.3453 2.07657 17.6804 2.22053 18" stroke="#3d95d0" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704" stroke="#3d95d0" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>

                        </div>
                    </div>
                    <div class="inner text-right">
                        <h4>{{ $users->count() }}</h4>
                        <p class="m-0 text-uppercase">{{ __('Total User') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div
                    class="small-box bg-white d-flex p-3 justify-content-between align-items-center dashboard_summary_box dashboard_shadow">
                    <div class="rounded-circle bg-light p-2" style="height: 70px; width: 70px;">
                        <div style="padding:4px;">
                            @include('svgs.brand')
                        </div>
                    </div>
                    <div class="inner text-right">
                        <h4>{{ $brands->count() }}</h4>
                        <p class="m-0 text-uppercase">{{ __('Total Brands') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div
                    class="small-box bg-white d-flex p-3 justify-content-between align-items-center dashboard_summary_box dashboard_shadow">
                    <div class="rounded-circle bg-light p-2" style="height: 70px; width: 70px;">
                        <div style="padding:6px;">
                            @include('svgs.product')
                        </div>
                    </div>
                    <div class="inner text-right">
                        <h4>{{ $products->count() }}</h4>
                        <p class="m-0 text-uppercase">{{ __('Total Products') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div
                    class="small-box bg-white d-flex p-3 justify-content-between align-items-center dashboard_summary_box dashboard_shadow">
                    <div class="rounded-circle bg-light p-2" style="height: 70px; width: 70px;">
                        <div style="padding:10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 80" x="0px" y="0px"
                                style="fill:#3d95d0;">
                                <g data-name="Order-Shopping Cart-Wishlist-Paper-Commerce">
                                    <path
                                        d="M49,2H16a6,6,0,0,0-1,11.91V29h2V14H49a1,1,0,0,0,0-2,4,4,0,1,1,4-4V56a4,4,0,1,1-4-4,1,1,0,0,0,0-2H17V43H15v7.09A6,6,0,0,0,16,62H49a6,6,0,0,0,6-6V8A6,6,0,0,0,49,2ZM44.53,12H16a4,4,0,0,1,0-8H44.54a5.979,5.979,0,0,0-.01,8ZM16,60a4,4,0,0,1,0-8H44.53a5.979,5.979,0,0,0,.01,8Z" />
                                    <path
                                        d="M46,39H31V37H46a1.01,1.01,0,0,0,1-.92l1-12a1.013,1.013,0,0,0-.26-.76A1.029,1.029,0,0,0,47,23H30.41l-1.7-1.71A1.033,1.033,0,0,0,28,21H26.82A3.01,3.01,0,0,0,24,19H22a3,3,0,0,0,0,6h2a3.01,3.01,0,0,0,2.82-2h.77L29,24.41V40a1,1,0,0,0,1,1h1.18a3,3,0,1,0,5.64,0h2.36a3,3,0,1,0,5.64,0H46a1,1,0,0,1,1,1h2A3.009,3.009,0,0,0,46,39ZM43,25h2.91l-.16,2H43Zm0,4h2.58l-.17,2H43Zm0,4h2.25l-.17,2H43Zm-4-8h2v2H39Zm0,4h2v2H39Zm0,4h2v2H39Zm-4-8h2v2H35Zm0,4h2v2H35Zm0,4h2v2H35Zm-4-8h2v2H31Zm0,4h2v2H31Zm0,4h2v2H31ZM24,23H22a1,1,0,0,1,0-2h2a1,1,0,0,1,0,2ZM34,43a1,1,0,1,1,1-1A1,1,0,0,1,34,43Zm8,0a1,1,0,1,1,1-1A1,1,0,0,1,42,43Z" />
                                    <rect x="5" y="39" width="2" height="2" />
                                    <rect x="9" y="39" width="4" height="2" />
                                    <rect x="15" y="39" width="12" height="2" />
                                    <rect x="12" y="35" width="10" height="2" />
                                    <rect x="17" y="31" width="8" height="2" />
                                    <rect x="13" y="31" width="2" height="2" />
                                    <rect x="8" y="35" width="2" height="2" />
                                </g>
                            </svg>
                        </div>
                    </div>
                    <div class="inner text-right">
                        <h4>$ </h4>
                        <p class="m-0 text-uppercase">{{ __('Total Earning') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@push('js')
@endpush
