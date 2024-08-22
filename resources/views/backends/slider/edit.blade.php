@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{__('Edit Slider')}}</h1>
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
                        <form method="POST" action="{{ route('admin.slider.update', $slider->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- general form elements -->
                            <div class="card card-primary">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                                @foreach (json_decode($language, true) as $lang)
                                                    @if ($lang['status'] == 1)
                                                        <li class="nav-item">
                                                            <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}" id="lang_{{ $lang['code'] }}-tab" data-toggle="pill" href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}" role="tab" aria-controls="lang_{{ $lang['code'] }}" aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                @foreach (json_decode($language, true) as $lang)
                                                    @if ($lang['status'] == 1)
                                                        <?php
                                                            if (count($slider['translations'])) {
                                                                $translate = [];
                                                                foreach ($slider['translations'] as $t) {

                                                                    if ($t->locale == $lang['code'] && $t->key == "name") {
                                                                        $translate[$lang['code']]['name'] = $t->value;
                                                                    }
                                                                    if ($t->locale == $lang['code'] && $t->key == "short_des") {
                                                                        $translate[$lang['code']]['short_des'] = $t->value;
                                                                    }

                                                                }
                                                            }
                                                        ?>
                                                        <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3" id="lang_{{ $lang['code'] }}" role="tabpanel" aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                            <div class="form-group">
                                                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                                                <label for="name_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                                                <input type="name" id="name_{{ $lang['code'] }}" class="form-control @error('name') is-invalid @enderror"
                                                                    name="name[]" placeholder="{{__('Enter Name')}}" value="{{ $translate[$lang['code']]['name'] ?? $slider['name'] }}">

                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="short_des_{{ $lang['code'] }}">{{ __('Short Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                <input type="short_des" id="short_des_{{ $lang['code'] }}" class="form-control @error('short_des') is-invalid @enderror"
                                                                 name="short_des[]" placeholder="{{__('Enter Short Description')}}" value="{{ $translate[$lang['code']]['short_des'] ?? $slider['short_des'] }}">
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
                                        <div class="form-group col-12">
                                            <label for="">{{__('Page')}}</label>
                                            <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                                                <option value="home" {{ $slider->type == 'home' ? 'selected' : '' }}>{{__('Home')}}</option>
                                                <option value="company_info" {{ $slider->type == 'company_info' ? 'selected' : '' }}>{{__('Home Chaufea')}}</option>
                                                <option value="villas" {{ $slider->type == 'villas' ? 'selected' : '' }}>{{__('Villas')}}</option>
                                                <option value="experience" {{ $slider->type == 'experience' ? 'selected' : '' }}>{{__('Experience')}}</option>
                                                <option value="offers" {{ $slider->type == 'offers' ? 'selected' : '' }}>{{__('Offers')}}</option>
                                                <option value="the_property" {{ $slider->type == 'the_property' ? 'selected' : '' }}>{{__('The Property')}}</option>
                                                <option value="gallery" {{ $slider->type == 'gallery' ? 'selected' : '' }}>{{__('Gallery')}}</option>
                                                <option value="foundation" {{ $slider->type == 'foundation' ? 'selected' : '' }}>{{__('Foundation')}}</option>
                                                <option value="about" {{ $slider->type == 'about' ? 'selected' : '' }}>{{__('About Us')}}</option>
                                                <option value="contact" {{ $slider->type == 'contact' ? 'selected' : '' }}>{{__('Contact Us')}}</option>
                                                <option value="booking_history" {{ $slider->type == 'booking_history' ? 'selected' : '' }}>{{__('Booking History')}}</option>
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputFile">{{__('Image')}}</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="hidden" name="image_names" class="image_names_hidden">
                                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                                        <label class="custom-file-label" for="exampleInputFile">{{ $slider->image ?? __('Choose file') }}</label>
                                                    </div>
                                                </div>
                                                <div class="preview text-center border rounded mt-2" style="height: 150px">
                                                    <img src="
                                                    @if ($slider->image && file_exists(public_path('uploads/sliders/' . $slider->image)))
                                                        {{ asset('uploads/sliders/'. $slider->image) }}
                                                    @else
                                                        {{ asset('uploads/image/default.png') }}
                                                    @endif
                                                    " alt="" height="100%">
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
                                        {{__('Save')}}
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
const compressor = new window.Compress();
        $('.custom-file-input').change(function (e) {
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
                                var img_container = $('<div></div>').addClass('img_container').css('height', '100%');
                                var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +'/'+ temp_file).css('height', '100%');
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

        $(document).on('click', '.nav-tabs .nav-link', function (e) {
            if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
            } else {
                $('.no_translate_wrapper').removeClass('d-none');
            }
        });
    </script>
@endpush
