@extends('backends.master')
@push('css')
    <style>
        .preview {
            margin-block: 12px;
            text-align: center;
        }
        .video-preview {
            margin-block: 12px;
            text-align: center;
        }
        .tab-pane {
            margin-top: 20px
        }
    </style>
@endpush
@section('contents')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>{{ __('Business Setting') }}</h3>
                </div>
                <div class="col-sm-6" style="text-align: right">
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    @include('backends.setting.partials.tab')
                </div>
                <div class="">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                            aria-labelledby="custom-tabs-four-home-tab">
                            <form action="{{ route('admin.setting.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Company Information') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'company',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                // dd($t);
                                                                                if ($t->locale == $lang['code'] && $t->key == 'company_name') {
                                                                                    $translate[$lang['code']]['company_name'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'company_address') {
                                                                                    $translate[$lang['code']]['company_address'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'copy_right_text') {
                                                                                    $translate[$lang['code']]['copy_right_text'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'company_short_description') {
                                                                                    $translate[$lang['code']]['company_short_description'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'company_description') {
                                                                                    $translate[$lang['code']]['company_description'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key=='company_sub_title'){
                                                                                    $translate[$lang['code']]['company_sub_title'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="company_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="company_name_{{ $lang['code'] }}">{{ __('Company Name') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <input type="text"
                                                                                        name="company_name[]"
                                                                                        id="company_name_{{ $lang['code'] }}"
                                                                                        class="form-control"
                                                                                        value="{{ $translate[$lang['code']]['company_name'] ?? $company_name }}">
                                                                                </div>
                                                                            </div>
                                                                            @if ($lang['code'] == 'en')
                                                                                {{-- <div class="col-12 col-sm-6 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="phone">{{ __('Phone') }}</label>
                                                                                        <input type="text" name="phone"
                                                                                            id="phone"
                                                                                            class="form-control"
                                                                                            value="{{ $phone }}">
                                                                                    </div>
                                                                                </div> --}}
                                                                                <div class="col-12 col-sm-6 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="">{{ __('Email') }}</label>
                                                                                        <input type="text" name="email"
                                                                                            id="email"
                                                                                            class="form-control"
                                                                                            value="{{ $email }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-6 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label for="">{{ __('Sales Email') }}</label>
                                                                                        <input type="email" name="sales_email" id="sales_email"
                                                                                            class="form-control" value="{{ $sales_email }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-sm-6 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="">{{ __('Whatsapp Number') }}</label>
                                                                                        <input type="text"
                                                                                            name="whatsapp_number"
                                                                                            id="whatsapp_number"
                                                                                            class="form-control"
                                                                                            value="{{ $whatsapp_number }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="col-12 col-sm-6 col-md-4">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="copy_right_text_{{ $lang['code'] }}">{{ __('Copyright Text') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <input type="text"
                                                                                        name="copy_right_text[]"
                                                                                        id="copy_right_text_{{ $lang['code'] }}"
                                                                                        class="form-control"
                                                                                        value="{{ $translate[$lang['code']]['copy_right_text'] ?? $copy_right_text }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="company_address_{{ $lang['code'] }}">{{ __('Company Address') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <input type="text"
                                                                                        name="company_address[]"
                                                                                        id="company_address_{{ $lang['code'] }}"
                                                                                        class="form-control"
                                                                                        value="{{ $translate[$lang['code']]['company_address'] ?? $company_address }}">

                                                                                </div>
                                                                            </div>
                                                                            @if ($lang['code'] == 'en')
                                                                                <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="copy_right_text">{{ __('Link Google Map') }}</label>
                                                                                        <input type="text"
                                                                                            name="link_google_map"
                                                                                            id="link_google_map"
                                                                                            class="form-control"
                                                                                            value="{{ $link_google_map }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            {{-- <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="company_short_description_{{ $lang['code'] }}">{{ __('Company Short Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="company_short_description[]" id="company_short_description_{{ $lang['code'] }}" class="form-control"
                                                                                        rows="3">{{ $translate[$lang['code']]['company_short_description'] ?? $company_short_description }}</textarea>
                                                                                </div>
                                                                            </div> --}}
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="company_description_{{ $lang['code'] }}">{{ __('Company Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="company_description[]" id="company_description_{{ $lang['code'] }}"
                                                                                        class="form-control value_summernote" rows="6">{{ $translate[$lang['code']]['company_description'] ?? $company_description }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            {{-- <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="company_sub_title_{{ $lang['code'] }}">{{ __('Phoum Chaufea Sub Title') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="company_sub_title[]" id="company_sub_title_{{ $lang['code'] }}"
                                                                                        class="form-control home_sub_summernote" rows="3">{{ $translate[$lang['code']]['company_sub_title'] ?? $company_sub_title }}</textarea>
                                                                                </div>
                                                                            </div> --}}
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card" hidden>
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Home Slider') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'home_slider',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                // dd($t);
                                                                                if ($t->locale == $lang['code'] && $t->key == 'slider_title') {
                                                                                    $translate[$lang['code']]['slider_title'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'slider_description') {
                                                                                    $translate[$lang['code']]['slider_description'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="home_slider_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="slider_title_{{ $lang['code'] }}">{{ __('Title') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <input type="text"
                                                                                        name="slider_title[]"
                                                                                        id="slider_title{{ $lang['code'] }}"
                                                                                        class="form-control"
                                                                                        value="{{ $translate[$lang['code']]['slider_title'] ?? $slider_title }}">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="slider_description_{{ $lang['code'] }}">{{ __('Slider Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="slider_description[]" id="slider_description_{{ $lang['code'] }}"
                                                                                        class="form-control home_slider_summernote" rows="7">{{ $translate[$lang['code']]['slider_description'] ?? $slider_description }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('History Of Phoum Chaufea') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'history_of_chaufea',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                // dd($t);
                                                                                if ($t->locale == $lang['code'] && $t->key == 'history_of_chaufea') {
                                                                                    $translate[$lang['code']]['history_of_chaufea'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="history_of_chaufea_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="history_of_chaufea_{{ $lang['code'] }}">{{ __('History Of Phoum Chaufea') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="history_of_chaufea[]" id="history_of_chaufea_{{ $lang['code'] }}"
                                                                                        class="form-control history_of_chaufea_summernote" rows="7">{{ $translate[$lang['code']]['history_of_chaufea'] ?? $history_of_chaufea }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Short Description of Foundation') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'foundation',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            foreach ($setting['translations'] as $t) {
                                                                                if ($t->locale == $lang['code'] && $t->key == 'foundation') {
                                                                                    $translate[$lang['code']]['foundation'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="foundation_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="foundation_{{ $lang['code'] }}">{{ __('Short Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="foundation[]" id="foundation_{{ $lang['code'] }}"
                                                                                        class="form-control" rows="12">{{ $translate[$lang['code']]['foundation'] ?? $foundation }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Contact Us') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'contact_us',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {

                                                                            foreach ($setting['translations'] as $t) {
                                                                                if ($t->locale == $lang['code'] && $t->key == 'contact_description') {
                                                                                    $translate[$lang['code']]['contact_description'] = $t->value;
                                                                                }
                                                                                if ($t->locale == $lang['code'] && $t->key == 'getInTouch') {
                                                                                    $translate[$lang['code']]['getInTouch'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="contact_us_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            @if ($lang['code'] == 'en')
                                                                                <div class="col-12 col-sm-12 col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label
                                                                                            for="contact_us_phone_number">{{ __('Phone Number') }}</label>
                                                                                        <input type="text"
                                                                                            name="contact_us_phone_number"
                                                                                            id="contact_us_phone_number"
                                                                                            class="form-control"
                                                                                            value="{{ $contact_us_phone_number }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="contact_description_{{ $lang['code'] }}">{{ __('Contact Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="contact_description[]" id="contact_description_{{ $lang['code'] }}"
                                                                                        class="form-control value_summernote" rows="7">{{ $translate[$lang['code']]['contact_description'] ?? $contact_description }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="getInTouch_{{ $lang['code'] }}">{{ __('Get In Touch') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="getInTouch[]" id="contact_description_{{ $lang['code'] }}" class="form-control value_summernote"
                                                                                        rows="7">{{ $translate[$lang['code']]['getInTouch'] ?? $getInTouch }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-group">
                                                            <label
                                                                for="auto_reply">{{ __('Auto Reply Message') }}</label>
                                                            <textarea name="auto_reply" id="contact_auto_reply" class="form-control value_summernote"
                                                                rows="7">{!! $auto_reply !!}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="image4">{{ __('Image') }}</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="image4_names"
                                                                        class="image4_names_hidden">
                                                                    <input type="file" class="custom-file-input_image4"
                                                                        id="image4" name="image"
                                                                        accept="image/png, image/gif, image/jpeg">
                                                                    <label class="custom-file-label"
                                                                        for="image4">{{ $image4 ?? __('Choose file') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                                style="height: 150px">
                                                                <div class="update_image">
                                                                    <div class="img_container">
                                                                        <img src="
                                                                    @if ($image4 && file_exists('uploads/business_settings/' . $image4)) {{ asset('uploads/business_settings/' . $image4) }}
                                                                    @else
                                                                        {{ asset('uploads/image/default.png') }} @endif
                                                                    "
                                                                            alt="" height="100%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Extra Service') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'extra_service',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                if ($t->locale == $lang['code'] && $t->key == 'extra_service_description') {
                                                                                    $translate[$lang['code']]['extra_service_description'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="extra_service_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="contact_description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="extra_service_description[]" id="extra_service_description_{{ $lang['code'] }}"
                                                                                        class="form-control value_summernote" rows="6">{{ $translate[$lang['code']]['extra_service_description'] ?? $extra_service_description }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('About Us') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'about_us',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                if ($t->locale == $lang['code'] && $t->key == 'about_us_description') {
                                                                                    $translate[$lang['code']]['about_us_description'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="about_us_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="short_description_{{ $lang['code'] }}">{{ __('Short Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <input type="text"
                                                                                        name="about_us_description[]"
                                                                                        id="short_description_{{ $lang['code'] }}"
                                                                                        class="form-control"
                                                                                        placeholder="{{ __('Short Description') }}"
                                                                                        value="{{ $translate[$lang['code']]['about_us_description'] ?? $about_us_description }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Booking Policy') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        @include('backends.setting.partials._lang_tab', [
                                                            'tab_id' => 'booking_policy',
                                                        ])
                                                        <div class="tab-content" id="custom-content-below-tabContent">
                                                            @foreach (json_decode($language, true) as $key => $lang)
                                                                @if ($lang['status'] == 1)
                                                                    <?php
                                                                    $translate = [];
                                                                    foreach ($settings as $setting) {
                                                                        if (count($setting['translations'])) {
                                                                            // dd($setting['translations']);

                                                                            foreach ($setting['translations'] as $t) {
                                                                                if ($t->locale == $lang['code'] && $t->key == 'booking_policy') {
                                                                                    $translate[$lang['code']]['booking_policy'] = $t->value;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                                        id="booking_policy_lang_{{ $lang['code'] }}"
                                                                        role="tabpanel"
                                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                                        <input type="hidden" name="lang[]"
                                                                            value="{{ $lang['code'] }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-12">
                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="booking_policy_{{ $lang['code'] }}">{{ __('Booking Policy') }}({{ strtoupper($lang['code']) }})</label>
                                                                                    <textarea name="booking_policy[]" id="booking_policy_{{ $lang['code'] }}" class="form-control value_summernote"
                                                                                        rows="6">{{ $translate[$lang['code']]['booking_policy'] ?? $booking_policy }}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('Bank Info') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="account_holder">{{ __('Account Holder') }}</label>
                                                    <input type="text" name="account_holder" id="account_holder" class="form-control" value="{{ $account_holder }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="account_number">{{ __('Account Number') }}</label>
                                                    <input type="text" name="account_number" id="account_number" class="form-control" value="{{ $account_number }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="bank">{{ __('Bank') }}</label>
                                                    <input type="text" name="bank" id="bank" class="form-control" value="{{ $bank }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="swift_code">{{ __('Swift Code') }}</label>
                                                    <input type="text" name="swift_code" id="swift_code" class="form-control" value="{{ $swift_code }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="bank_address">{{ __('Bank Address') }}</label>
                                                    <input type="text" name="bank_address" id="bank_address" class="form-control" value="{{ $bank_address }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="account_holder_address">{{ __('Account Holder address') }}</label>
                                                    <textarea name="account_holder_address" id="account_holder_address" class="form-control" rows="3">{{ $account_holder_address }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}

                                        <!--<div class="card home_images_wrapper " hidden>
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Website Image') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="form-group col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="image1">{{ __('Image') }}</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="image1_names"
                                                                        class="image1_names_hidden">
                                                                    <input type="file"
                                                                        class=" custom-file-input_image1" id="image1"
                                                                        name="image"
                                                                        accept="image/png, image/gif, image/jpeg">
                                                                    <label class="custom-file-label"
                                                                        for="image1">{{ $image1 ?? __('Choose file') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                                style="height: 150px">
                                                                <div class="update_image">
                                                                    <div class="img_container">
                                                                        <img src="
                                                                    @if ($image1 && file_exists('uploads/business_settings/' . $image1)) {{ asset('uploads/business_settings/' . $image1) }}
                                                                    @else
                                                                        {{ asset('uploads/image/default.png') }} @endif
                                                                    "
                                                                            alt="" height="100%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="image2">{{ __('Image') }}</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="image2_names"
                                                                        class="image2_names_hidden">
                                                                    <input type="file" class="custom-file-input_image2"
                                                                        id="image2" name="image"
                                                                        accept="image/png, image/gif, image/jpeg">
                                                                    <label class="custom-file-label"
                                                                        for="image2">{{ $image2 ?? __('Choose file') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                                style="height: 150px">
                                                                <div class="update_image">
                                                                    <div class="img_container">
                                                                        <img src="
                                                                    @if ($image2 && file_exists('uploads/business_settings/' . $image2)) {{ asset('uploads/business_settings/' . $image2) }}
                                                                    @else
                                                                        {{ asset('uploads/image/default.png') }} @endif
                                                                    "
                                                                            alt="" height="100%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="image3">{{ __('Image') }}</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="image3_names"
                                                                        class="image3_names_hidden">
                                                                    <input type="file"
                                                                        class=" custom-file-input_image3" id="image3"
                                                                        name="image"
                                                                        accept="image/png, image/gif, image/jpeg">
                                                                    <label class="custom-file-label"
                                                                        for="image3">{{ $image3 ?? __('Choose file') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                                style="height: 150px">
                                                                <div class="update_image">
                                                                    <div class="img_container">
                                                                        <img src="
                                                                    @if ($image3 && file_exists('uploads/business_settings/' . $image3)) {{ asset('uploads/business_settings/' . $image3) }}
                                                                    @else
                                                                        {{ asset('uploads/image/default.png') }} @endif
                                                                    "
                                                                            alt="" height="100%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="form-group col-md-6 col-12">
                                                        <div class="form-group">
                                                            <label for="image4">{{ __('Image') }}</label>
                                                            <div class="input-group">
                                                                <div class="custom-file">
                                                                    <input type="hidden" name="image4_names"
                                                                        class="image4_names_hidden">
                                                                    <input type="file" class="custom-file-input_image4"
                                                                        id="image4" name="image"
                                                                        accept="image/png, image/gif, image/jpeg">
                                                                    <label class="custom-file-label"
                                                                        for="image4">{{ $image4 ?? __('Choose file') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                                style="height: 150px">
                                                                <div class="update_image">
                                                                    <div class="img_container">
                                                                        <img src="
                                                                    @if ($image4 && file_exists('uploads/business_settings/' . $image4)) {{ asset('uploads/business_settings/' . $image4) }}
                                                                    @else
                                                                        {{ asset('uploads/image/default.png') }} @endif
                                                                    "
                                                                            alt="" height="100%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>-->

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Contact') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Phone Number') }}</th>
                                                                <th>
                                                                    {{ __('Icon') }}
                                                                    <br>
                                                                    <span class="text-info text-xs">{{ __('Recommend svg icon') }}</span>
                                                                </th>
                                                                <th>{{ __('Link') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>
                                                                    <button type="button" class="btn btn-success btn-sm btn_add_contact">
                                                                        <i class="fa fa-plus-circle"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        @include('backends.setting.partials._contact_tbody')
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Social Media') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Title') }}</th>
                                                                <th>
                                                                    {{ __('Icon') }}
                                                                    <br>
                                                                    <span class="text-info text-xs">{{ __('Recommend svg icon') }}</span>
                                                                </th>
                                                                <th>{{ __('Link') }}</th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>
                                                                    <button type="button" class="btn btn-success btn-sm btn_add_social_media">
                                                                        <i class="fa fa-plus-circle"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        @include('backends.setting.partials._social_media_tbody')
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Payment') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>{{ __('Title') }}</th>
                                                                <th>
                                                                    {{ __('Icon') }}
                                                                    <br>
                                                                    <span class="text-info text-xs">{{ __('Recommend svg icon') }}</span>
                                                                </th>
                                                                <th>{{ __('Status') }}</th>
                                                                <th>
                                                                    <button type="button" class="btn btn-success btn-sm btn_add_payment">
                                                                        <i class="fa fa-plus-circle"></i>
                                                                    </button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        @include('backends.setting.partials._payment_tbody')
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Short Video') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="video_trailer">{{ __('Short Video') }} <small class="text-danger"> ( {{ __('Max Size Upload: 2MB') }} )</small></label>
                                                            <div class="video-preview">
                                                                <video src="{{ asset('uploads/business_settings/' . $video_trailer) }}"  width="auto" height="170px" controls></video>
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="customFileUpload" name="video_trailer" accept="video/*" value="{{ $video_trailer }}">
                                                                <label class="custom-file-label"
                                                                    for="customFile">{{ __('Choose file') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="full_video">{{ __('Link Full Video') }}</label>
                                                            <input type="text" class="form-control" name="link_full_video" value="{{ $link_full_video }}" placeholder="Link Full Video">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">{{ __('Website Logo setup') }}</h3>
                                            </div>
                                            <div class="card-body">
                                                {{-- <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="timezone">{{ __('Timezone') }}</label>
                                                        <select name="timezone" id="timezone" class="form-control select2">
                                                            <option value="">{{ __('Please Select') }}</option>
                                                            @foreach (config('list.all_timezone') as $value => $name)
                                                                <option value="{{ $value }}" {{ $timezone == $value ? 'selected' : '' }}>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="currency">{{ __('Currency') }}</label>
                                                       <select name="currency" id="currency" class="form-control select2">
                                                            <option value="">{{ __('Please Select') }}</option>
                                                            @foreach (config('list.currency_list') as $item)
                                                                <option value="{{ $item['code'] }}" {{ $item['code'] == $currency ? 'selected' : '' }}>{{ $item['symbol'] . ' - ' . $item['name'] }}</option>
                                                            @endforeach
                                                       </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="web_header_logo">{{ __('Website logo') }}</label>
                                                            <div class="preview">
                                                                <img src="
                                                            @if ($web_header_logo && file_exists('uploads/business_settings/' . $web_header_logo)) {{ asset('uploads/business_settings/' . $web_header_logo) }}
                                                            @else
                                                                {{ asset('uploads/image/default.png') }} @endif
                                                            "
                                                                    alt="" height="150px" width="190px">
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="customFile" name="web_header_logo">
                                                                <label class="custom-file-label"
                                                                    for="customFile">{{ __('Choose file') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-4">
                                                    <div class="form-group">
                                                        <label for="web_banner_logo">{{ __('Website banner logo') }}</label>
                                                        <div class="preview">
                                                            <img src="
                                                            @if ($web_banner_logo && file_exists('uploads/business_settings/' . $web_banner_logo))
                                                                {{ asset('uploads/business_settings/'. $web_banner_logo) }}
                                                            @else
                                                                {{ asset('uploads/image/default.png') }}
                                                            @endif
                                                            " alt="" height="120px">
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="customFile" name="web_banner_logo">
                                                            <label class="custom-file-label" for="customFile">{{ __('Choose file') }}</label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="fav_icon">{{ __('Fav icon') }}</label>
                                                            <div class="preview">
                                                                <img src="
                                                            @if ($fav_icon && file_exists('uploads/business_settings/' . $fav_icon)) {{ asset('uploads/business_settings/' . $fav_icon) }}
                                                            @else
                                                                {{ asset('uploads/image/default.png') }} @endif
                                                            "
                                                                    alt="" height="150px" width="190px">
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="customFile" name="fav_icon">
                                                                <label class="custom-file-label"
                                                                    for="customFile">{{ __('Choose file') }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-4">
                                                        <div class="form-group">
                                                            <label for="video_trailer">{{ __('Video Trailer') }}</label>
                                                            <div class="video-preview">
                                                                <video src="{{ asset('uploads/business_settings/' . $video_trailer) }}"  width="auto" height="145px" controls></video>
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input"
                                                                    id="customFile" name="video_trailer" value="{{ $video_trailer }}" >
                                                                <label class="custom-file-label"
                                                                    for="customFile">{{ __('Choose file') }}</label>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <button type="submit" class="btn btn-primary float-right">
                                                    <i class="fas fa-save"></i>
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
@endsection
@push('js')
    <script>
        $('.home_slider_summernote').summernote({
            placeholder: '{{ 'Type something' }}',
            tabsize: 2,
            height: 150,

        });
        $('.home_sub_summernote').summernote({
            placeholder: '{{ 'Type something' }}',
            tabsize: 2,
            height: 120,

        });
        $('.value_summernote').summernote({
            placeholder: '{{ 'Type something' }}',
            tabsize: 2,
            height: 250,

        });
        $('.foundation_summernote').summernote({
            placeholder: '{{ 'Type something' }}',
            tabsize: 2,
            height: 230,
        })
        $('.history_of_chaufea_summernote').summernote({
            placeholder: '{{ 'Type something' }}',
            tabsize: 2,
            height: 400,
        });
        $('.btn_add_contact').click(function(e) {
            var tbody = $('.contact_tbody');
            var numRows = tbody.find("tr").length;
            $.ajax({
                type: "get",
                url: window.location.href,
                data: {
                    "type": "key_contact",
                    "key": numRows
                },
                dataType: "json",
                success: function(response) {
                    $(tbody).append(response.tr);
                }
            });
        });
        $('.btn_add_social_media').click(function(e) {
            var tbody = $('.tbody');
            var numRows = tbody.find("tr").length;
            $.ajax({
                type: "get",
                url: window.location.href,
                data: {
                    "type": "key_social",
                    "key": numRows
                },
                dataType: "json",
                success: function(response) {
                    $(tbody).append(response.tr);
                }
            });
        });
        $('.btn_add_payment').click(function(e) {
            var tbody = $('.payment_tbody');
            var numRows = tbody.find("tr").length;
            $.ajax({
                type: "get",
                url: window.location.href,
                data: {
                    "type": "key_payment",
                    "key": numRows
                },
                dataType: "json",
                success: function(response) {
                    $(tbody).append(response.tr);
                }
            });
        });

        const compressor = new window.Compress();
        $('.custom-file-input_image1').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
                container.find('.update_image').empty();
                if (container.find('img').attr('src') === `{{ asset('uploads/image/default.png') }}`) {
                    container.empty();
                }
                formData.append('image', files);

                $.ajax({
                    url: "{{ route('save_temp_file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 0) {
                            toastr.error(response.msg);
                        }
                        if (response.status == 1) {
                            container.empty();
                            var temp_files = response.temp_files;
                            for (var i = 0; i < temp_files.length; i++) {
                                var temp_file = temp_files[i];
                                var img_container = $('<div></div>').addClass('img_container');
                                var img = $('<img>').attr('src',
                                    "{{ asset('uploads/temp') }}" + '/' + temp_file);
                                img_container.append(img);
                                container.append(img_container);
                                // $(selector).replaceWith(newContent);

                                var new_file_name = temp_file;
                                console.log(new_file_name);

                                image_names_hidden.val(new_file_name);
                            }
                        }
                    }
                });
            });
        });
        $('.custom-file-input_image2').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
                container.find('.update_image').empty();
                if (container.find('img').attr('src') === `{{ asset('uploads/image/default.png') }}`) {
                    container.empty();
                }
                formData.append('image', files);

                $.ajax({
                    url: "{{ route('save_temp_file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 0) {
                            toastr.error(response.msg);
                        }
                        if (response.status == 1) {
                            container.empty();
                            var temp_files = response.temp_files;
                            for (var i = 0; i < temp_files.length; i++) {
                                var temp_file = temp_files[i];
                                var img_container = $('<div></div>').addClass('img_container');
                                var img = $('<img>').attr('src',
                                    "{{ asset('uploads/temp') }}" + '/' + temp_file);
                                img_container.append(img);
                                container.append(img_container);
                                // $(selector).replaceWith(newContent);

                                var new_file_name = temp_file;
                                console.log(new_file_name);

                                image_names_hidden.val(new_file_name);
                            }
                        }
                    }
                });
            });
        });
        $('.custom-file-input_image3').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
                container.find('.update_image').empty();
                if (container.find('img').attr('src') === `{{ asset('uploads/image/default.png') }}`) {
                    container.empty();
                }
                formData.append('image', files);

                $.ajax({
                    url: "{{ route('save_temp_file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 0) {
                            toastr.error(response.msg);
                        }
                        if (response.status == 1) {
                            container.empty();
                            var temp_files = response.temp_files;
                            for (var i = 0; i < temp_files.length; i++) {
                                var temp_file = temp_files[i];
                                var img_container = $('<div></div>').addClass('img_container');
                                var img = $('<img>').attr('src',
                                    "{{ asset('uploads/temp') }}" + '/' + temp_file);
                                img_container.append(img);
                                container.append(img_container);
                                // $(selector).replaceWith(newContent);

                                var new_file_name = temp_file;
                                console.log(new_file_name);

                                image_names_hidden.val(new_file_name);
                            }
                        }
                    }
                });
            });
        });
        $('.custom-file-input_image4').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
                container.find('.update_image').empty();
                if (container.find('img').attr('src') === `{{ asset('uploads/image/default.png') }}`) {
                    container.empty();
                }
                formData.append('image', files);

                $.ajax({
                    url: "{{ route('save_temp_file') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 0) {
                            toastr.error(response.msg);
                        }
                        if (response.status == 1) {
                            container.empty();
                            var temp_files = response.temp_files;
                            for (var i = 0; i < temp_files.length; i++) {
                                var temp_file = temp_files[i];
                                var img_container = $('<div></div>').addClass('img_container');
                                var img = $('<img>').attr('src',
                                    "{{ asset('uploads/temp') }}" + '/' + temp_file);
                                img_container.append(img);
                                container.append(img_container);
                                // $(selector).replaceWith(newContent);

                                var new_file_name = temp_file;
                                console.log(new_file_name);

                                image_names_hidden.val(new_file_name);
                            }
                        }
                    }
                });
            });
        });
        // $('#custom-tabs-for-webcontent-tab').click(function (e) {
        //     e.preventDefault();
        //     $.ajax({
        //         type: "get",
        //         url: $(this).data('href'),
        //         // data: "data",
        //         dataType: "json",
        //         success: function (response) {
        //             // console.log(response);

        //         }
        //     });
        // });

        $(document).on('click', 'button[type=submit]', function(e) {
            e.preventDefault();
            // alert('okk');
            $('.home_images_wrapper input[type=file]').attr('disabled', true);
            $(this).closest('form').submit();
        });
    </script>
@endpush
