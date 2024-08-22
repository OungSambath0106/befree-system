@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Edit Staycation') }}</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.staycation.update', $staycation->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            {{-- @dump($languages) --}}
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <li class="nav-item">
                                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}"
                                                            id="lang_{{ $lang['code'] }}-tab" data-toggle="pill"
                                                            href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}"
                                                            role="tab" aria-controls="lang_{{ $lang['code'] }}"
                                                            aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <?php
                                                    if (count($staycation['translations'])) {
                                                        $translate = [];
                                                        foreach ($staycation['translations'] as $t) {
                                                            if ($t->locale == $lang['code'] && $t->key == 'title') {
                                                                $translate[$lang['code']]['title'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                        <div class="form-group">
                                                            <input type="hidden" name="lang[]"
                                                                value="{{ $lang['code'] }}">
                                                            <label
                                                                for="title_{{ $lang['code'] }}">{{ __('Title') }}({{ strtoupper($lang['code']) }})</label>
                                                            <input type="title" id="title_{{ $lang['code'] }}"
                                                                class="form-control @error('Title') is-invalid @enderror"
                                                                name="title[]" placeholder="{{ __('Enter Title') }}"
                                                                value="{{ $translate[$lang['code']]['title'] ?? $staycation['title'] }}">

                                                            @error('title')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card no_translate_wrapper">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('General Info') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="exampleInputFile">{{ __('Thumbnail') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input image-file-input"
                                                        id="exampleInputFile" name="thumbnail">
                                                    <label class="custom-file-label"
                                                        for="exampleInputFile">{{ $staycation->thumbnail ?? __('Choose Thumbnail') }}</label>
                                                </div>
                                            </div>
                                            <div class="preview text-center border rounded mt-2" style="height: 180px">
                                                <img class="py-2"
                                                    src="
                                                @if ($staycation->thumbnail && file_exists(public_path('uploads/staycation/' . $staycation->thumbnail))) {{ asset('uploads/staycation/' . $staycation->thumbnail) }}
                                                @else
                                                    {{ asset('uploads/image/default.png') }} @endif"
                                                    alt="" height="100%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Amenities') }}</label>
                                            <div class="row">
                                                {{-- @dd(json_decode($staycation->amenities, true)) --}}
                                                @if ($amenities->value)
                                                    @php
                                                        $allAmenities = $amenities->value;

                                                        $selectedAmenities = empty($staycation->amenities)
                                                            ? []
                                                            : array_map(function ($amenity) {
                                                                return json_decode($amenity, true);
                                                            }, $staycation->amenities);
                                                    @endphp

                                                    @foreach ($allAmenities as $row)
                                                        @php
                                                            $isChecked = false;
                                                            foreach ($selectedAmenities as $selectedAmenity) {
                                                                if (
                                                                    $selectedAmenity['title'] == $row['title'] &&
                                                                    $selectedAmenity['image'] == $row['image']
                                                                ) {
                                                                    $isChecked = true;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        <div class="col-12 col-md-3 mt-2">
                                                            <div
                                                                class="icheck-primary d-inline col-md-2 align-content-center">
                                                                <input type="checkbox"
                                                                    id="checkboxPrimary{{ $loop->index }}"
                                                                    name="amenities[]" {{ $isChecked ? 'checked' : '' }}
                                                                    value="{{ json_encode($row) }}">
                                                                <label for="checkboxPrimary{{ $loop->index }}">
                                                                    {{ $row['title'] }}
                                                                </label>
                                                            </div>
                                                            <img src="@if ($row['image'] && file_exists(public_path('uploads/amenity/' . $row['image']))) {{ asset('uploads/amenity/' . $row['image']) }}
                                                            @else {{ asset('uploads/image/default.png') }} @endif"
                                                                alt="Image" style="width: 23px; height: 23px">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 form-group">
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="fa fa-save"></i>
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script>
        $('.custom-file-input').change(function(e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');
            console.log(preview);
            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });
        $(document).on('click', '.nav-tabs .nav-link', function(e) {
            if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
            } else {
                $('.no_translate_wrapper').removeClass('d-none');
            }
        });
    </script>
@endpush
