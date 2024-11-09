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
                        <div style="padding:12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                xml:space="preserve" version="1.1"
                                style="shape-rendering:geometricPrecision;text-rendering:geometricPrecision;image-rendering:optimizeQuality;"
                                viewBox="0 0 846.66 1058.325" x="0px" y="0px" fill-rule="evenodd" clip-rule="evenodd">
                                <defs>
                                    <style type="text/css">
                                        .fil0 {
                                            fill: #3d95d0;
                                            fill-rule: nonzero
                                        }
                                    </style>
                                </defs>
                                <g>
                                    <path class="fil0"
                                        d="M669.71 485.74l44.83 88.14 91.78 18.35c9.16,1.82 15.11,10.75 13.29,19.91 -0.7,3.53 -2.46,6.58 -4.88,8.9l-63.8 72.16 12.2 100.38c1.09,9.27 -5.55,17.69 -14.82,18.78 -3.44,0.4 -6.77,-0.26 -9.65,-1.73l-84 -42.76 -84.05 42.77c-8.32,4.23 -18.52,0.91 -22.75,-7.41 -1.57,-3.09 -2.1,-6.44 -1.72,-9.65l12.22 -100.34 -64.73 -73.23c-6.17,-6.97 -5.52,-17.64 1.45,-23.81 2.32,-2.05 5.05,-3.35 7.88,-3.91l91.74 -18.39 44.85 -88.16c4.23,-8.32 14.43,-11.64 22.75,-7.41 3.32,1.69 5.84,4.32 7.41,7.41zm-609.13 -262.93l725.5 0 0 -49.96c0,-13.98 -5.72,-26.7 -14.9,-35.9 -9.2,-9.18 -21.92,-14.9 -35.9,-14.9l-72.43 0 0 20.17c0,9.33 -3.8,17.8 -9.93,23.93 -6.13,6.13 -14.6,9.93 -23.93,9.93l-26.77 0c-9.33,0 -17.8,-3.8 -23.93,-9.93 -6.13,-6.13 -9.93,-14.6 -9.93,-23.93l0 -20.17 -97.79 0 0 20.17c0,9.33 -3.8,17.8 -9.93,23.93 -6.13,6.13 -14.6,9.93 -23.93,9.93l-26.77 0c-9.33,0 -17.8,-3.8 -23.93,-9.93 -6.13,-6.13 -9.93,-14.6 -9.93,-23.93l0 -20.17 -97.79 0 0 20.17c0,9.33 -3.8,17.8 -9.93,23.93 -6.13,6.13 -14.6,9.93 -23.93,9.93l-26.77 0c-9.33,0 -17.8,-3.8 -23.93,-9.93 -6.13,-6.13 -9.93,-14.6 -9.93,-23.93l0 -20.17 -72.42 0c-13.98,0 -26.7,5.72 -35.9,14.9 -9.18,9.2 -14.9,21.92 -14.9,35.9l0 49.96zm725.5 33.86l-725.5 0 0 292.16 137.16 -7.79c9.31,-0.51 17.28,6.62 17.79,15.93l0.07 147.81 297.48 0c9.35,0 16.93,7.58 16.93,16.93 0,9.35 -7.58,16.93 -16.93,16.93l-314.41 0c-4.73,0 -9,-1.94 -12.07,-5.06l-126.02 -126.01 0 120.26c0,13.94 5.74,26.65 14.94,35.85 9.17,9.25 21.88,14.95 35.86,14.95l396.31 0c9.35,0 16.93,7.58 16.93,16.93 0,9.35 -7.58,16.93 -16.93,16.93l-396.31 0c-23.21,0 -44.39,-9.54 -59.76,-24.91 -15.36,-15.28 -24.9,-36.46 -24.9,-59.75l0 -554.98c0,-23.25 9.52,-44.4 24.85,-59.74 15.41,-15.4 36.56,-24.92 59.81,-24.92l72.42 0 0 -20.16c0,-9.33 3.8,-17.8 9.93,-23.93 6.13,-6.13 14.6,-9.93 23.93,-9.93l26.77 0c9.33,0 17.8,3.8 23.93,9.93 6.13,6.13 9.93,14.6 9.93,23.93l0 20.16 97.79 0 0 -20.16c0,-9.33 3.8,-17.8 9.93,-23.93 6.13,-6.13 14.6,-9.93 23.93,-9.93l26.77 0c9.33,0 17.8,3.8 23.93,9.93 6.13,6.13 9.93,14.6 9.93,23.93l0 20.16 97.79 0 0 -20.16c0,-9.33 3.8,-17.8 9.93,-23.93 6.13,-6.13 14.6,-9.93 23.93,-9.93l26.77 0c9.33,0 17.8,3.8 23.93,9.93 6.13,6.13 9.93,14.6 9.93,23.93l0 20.16 72.43 0c23.25,0 44.4,9.52 59.74,24.85 15.4,15.41 24.92,36.56 24.92,59.81l0 380.18c0,9.35 -7.58,16.93 -16.93,16.93 -9.35,0 -16.93,-7.58 -16.93,-16.93l0 -296.36zm-703.77 324.75l99.43 99.42 0 -105.07 -99.43 5.65zm546.68 -513.39l-26.77 0 0 74.19 26.77 0 0 -74.19zm-192.28 0l-26.77 0 0 74.19 26.77 0 0 -74.19zm-192.28 0l-26.77 0 0 74.19 26.77 0 0 -74.19zm443.67 528.51l-33.47 -65.82 -33.37 65.59c-2.24,4.58 -6.52,8.11 -11.9,9.18l-71 14.19 49.53 56.03c3.61,3.54 5.61,8.66 4.97,14.07l-9.29 76.3 63.42 -32.27c4.66,-2.36 10.34,-2.54 15.34,0l63.38 32.27 -9.28 -76.34c-0.49,-4.61 0.86,-9.42 4.17,-13.16l50.31 -56.91 -71.04 -14.2c-4.9,-1 -9.33,-4.13 -11.77,-8.93z" />
                                </g>
                            </svg>

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
