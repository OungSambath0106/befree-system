<!doctype html>
<html lang="en">

<head>
    <title>Phoum Chaufea</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('backend/login-form/css/style.css') }}">

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> --}}
    <style>
        .custom-logo {
            width: 150px;
        }
        @media screen and (max-width: 768px) {
            .custom-logo {
                width: 100px;
            }
        }
    </style>
</head>

<body>
    <section class="ftco-section h-100vh">
        <div class="container h-100 d-flex justify-content-center align-items-center w-100">
                <div style="width: min(100%, 750px) !important;" class="row py-4 align-items-center justify-content-center bg-white rounded shadow-sm">
                        <div class="col-md-4 col-lg-4">
                            <div class="px-3">
                                @php
                                    $business = \App\Models\BusinessSetting::first();
                                    $data['web_header_logo'] = @$business->where('type', 'web_header_logo')->first()->value??'';
                                @endphp
                                <h2 class="heading-section d-flex justify-content-center">
                                    <img src="
                                        @if ($data['web_header_logo'] && file_exists('uploads/business_settings/'. $data['web_header_logo']))
                                            {{ asset('uploads/business_settings/'. $data['web_header_logo']) }}
                                        @else
                                            {{ asset('uploads/image/default.png') }}
                                        @endif
                                        " class="custom-logo" alt="Phoum Chaufea">
                                </h2>
                            </div>
                        </div>
                        <div class="col-md-7 col-lg-7">
                            <div class="">

                                <div class="login-wrap">
                                    <div class="d-flex justify-content-center">
                                        <div class="">
                                            <h3 class="text-uppercase" style="margin: 0;color:#d0803d;">{{ __('Login') }}</h3>
                                        </div>
                                    </div>
                                    <br>
                                    <form method="POST" action="{{ route('login') }}" class="signin-form">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label class="label" for="name">{{ __('Email') }}</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="Email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-5">
                                            <label class="label" for="password">{{ __('Password') }}</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        {{-- <div class="form-group d-md-flex">
                                            <div class="w-50 text-left">
                                                <label class="checkbox-wrap checkbox-primary mb-0">Remember Me
                                                    <input type="checkbox" name="remember" id="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div> --}}

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block text-white rounded float-right"
                                                style="margin-bottom: 20px; background-color: #d0803d;">{{ __('Log In') }}</button>
                                        </div>
                                        <br>

                                    </form>
                                </div>
                            </div>
                        </div>
                </div>

        </div>
    </section>

    <script src="{{ asset('backend/login-form/js/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/login-form/js/popper.js') }}"></script>
    <script src="{{ asset('backend/login-form/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/login-form/js/main.js') }}"></script>

</body>

</html>
