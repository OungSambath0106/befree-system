@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{__('Edit Blog')}}</h1>
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
                        <form method="POST" action="{{ route('admin.blog.update', $blog->id) }}" enctype="multipart/form-data">
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
                                                            <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}" id="lang_{{ $lang['code'] }}-tab" data-toggle="pill" href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}" role="tab" aria-controls="lang_{{ $lang['code'] }}" aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                            </ul>
                                            <div class="tab-content" id="custom-content-below-tabContent">
                                                @foreach (json_decode($language, true) as $lang)
                                                    @if ($lang['status'] == 1)
                                                        <?php
                                                            if (count($blog['translations'])) {
                                                                $translate = [];
                                                                foreach ($blog['translations'] as $t) {

                                                                    if ($t->locale == $lang['code'] && $t->key == "title") {
                                                                        $translate[$lang['code']]['title'] = $t->value;
                                                                    }
                                                                    if ($t->locale == $lang['code'] && $t->key == "description") {
                                                                        $translate[$lang['code']]['description'] = $t->value;
                                                                    }

                                                                }
                                                            }
                                                        ?>
                                                        <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3" id="lang_{{ $lang['code'] }}" role="tabpanel" aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                            <div class="form-group">
                                                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                                                <label for="title_{{ $lang['code'] }}">{{ __('Title') }}({{ strtoupper($lang['code']) }})</label>
                                                                <input type="title" id="title_{{ $lang['code'] }}" class="form-control @error('Title') is-invalid @enderror"
                                                                    name="title[]" placeholder="{{__('Enter Title')}}" value="{{ $translate[$lang['code']]['title'] ?? $blog['title'] }}">

                                                                @error('title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                <textarea id="description_{{ $lang['code'] }}" class="form-control summernote @error('description') is-invalid @enderror"
                                                                  name="description[]" rows="3">{{ $translate[$lang['code']]['description'] ?? $blog['description'] }}</textarea>
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
                                        <div class="form-group col-md-6" hidden>
                                            <label class="required_label" for="category">{{__('Category')}}</label>
                                            <select name="category_id" id="category_id" class="form-control select2 @error('category_id') is-invalid @enderror">
                                                @foreach ($categories as $id => $title)
                                                    <option value="{{ $id }}" {{ $id == $blog->category_id ? 'selected' : '' }}>{{ $title }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6" hidden>
                                            <label class="required_label" for="category">{{__('Tags')}}</label>
                                            <select name="tage[]" id="tage" class="form-control select2 @error('tage') is-invalid @enderror" multiple>
                                                @forelse ($tages as $id => $title)
                                                <option value="{{ $id }}"
                                                    {{ ($blog->tage && in_array($id, $blog->tage)) ? 'selected' : '' }}>
                                                    {{ $title }}
                                                </option>
                                                @empty
                                                    <option value="{{ $id }}">
                                                        {{ $title }}
                                                    </option>
                                                @endforelse
                                            </select>
                                            @error('tage')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-group">
                                                <label for="thumbnail">{{__('Thumbnail')}}</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="hidden" name="thumbnail_names" class="thumbnail_names_hidden">
                                                        <input type="file" class=" custom-file-input_thumbnail" id="thumbnail" name="image" accept="image/png, image/gif, image/jpeg">
                                                        <label class="custom-file-label" for="thumbnail">{{ $room->thumbnail ?? __('Choose file') }}</label>
                                                    </div>
                                                </div>
                                                {{-- <span class="text-info text-xs">{{ __('Recommend size 512 x 512 px') }}</span> --}}
                                                <div class="preview preview-multiple text-center border rounded mt-2" style="height: 150px">
                                                    <div class="update_image">
                                                        <div class="img_container">
                                                            <img src="
                                                            @if ($blog->thumbnail && file_exists(public_path('uploads/blogs/' . $blog->thumbnail)))
                                                                {{ asset('uploads/blogs/'. $blog->thumbnail) }}
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
        $('.custom-file-input_thumbnail').change(function (e) {
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
                                var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +'/'+ temp_file);
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
