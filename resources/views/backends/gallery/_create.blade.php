@push('css')
@endpush
<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('New Gallery') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="{{ route('admin.gallery.store') }}" class="submit-form" enctype="multipart/form-data"
            method="post">
            <div class="modal-body">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <!-- <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            {{-- @dump($languages) --}}
                            @foreach (json_decode($language, true) as $lang)
                                @if ($lang['status'] == 1)
                                    <li class="nav-item">
                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}"
                                            id="lang_{{ $lang['code'] }}-tab" data-toggle="pill"
                                            href="#lang_{{ $lang['code'] }}" role="tab"
                                            aria-controls="lang_{{ $lang['code'] }}"
                                            aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['code']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                    </li>
                                @endif
                            @endforeach

                        </ul> -->
                        {{-- <div class="tab-content" id="custom-content-below-tabContent">
                            @foreach (json_decode($language, true) as $lang)
                                @if ($lang['status'] == 1)
                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }}"
                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                        <div class="form-group">
                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                            <label
                                                for="description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                            <textarea name="description[]" id="description_{{ $lang['code'] }}"
                                                class="form-control @error('description') is-invalid @enderror" {{ $lang['code'] == 'en' ? 'required' : '' }}>{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div> --}}
                        <div class="tab-content">
                            <div class="form-group">
                                <label class="required_lable" for="category">{{ __('Category') }}</label>
                                <select name="category_id[]" id="category" multiple
                                        class="form-control select2 @error('category_id') is-invalid @enderror">
                                    <option value="">{{ __('Select category') }}</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}"
                                            {{ (is_array(old('category_id')) && in_array($item->id, old('category_id'))) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">{{ __('Image') }}</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="hidden" name="image_names" class="image_names_hidden">
                                        <input type="file" class="custom-file-input" id="exampleInputFile"
                                            name="image" accept="image/png, image/jpeg">
                                        <label class="custom-file-label"
                                            for="exampleInputFile">{{ __('Choose file') }}</label>
                                    </div>
                                </div>
                                <div class="preview preview-multiple text-center border rounded mt-2"
                                    style="height: 277px">
                                    <img src="{{ asset('uploads/image/default.png') }}" alt=""
                                        style="height: 100% !important">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary submit">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    $('.custom-file-input').change(function(e) {
        compressor.compress([...e.target.files], {
            size: 4,
            quality: 0.75,
        }).then((output) => {
            var extension = output[0].ext;
            console.log(extension);
            var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
            var formData = new FormData();

            var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
            var container = $(this).closest('.form-group').find('.preview');
            if (container.find('img').attr('src') === `{{ asset('uploads/default-profile.png') }}`) {
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
                        var temp_file = response.temp_files;
                        var img_container = $('<div></div>').addClass('img_container').css(
                            'height', '100%');
                        var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +
                            '/' + temp_file).css('height', '100%');
                        img_container.append(img);
                        container.append(img_container);

                        var new_file_name = temp_file;
                        console.log(new_file_name);

                        image_names_hidden.val(new_file_name);
                    }
                }
            });
        });
    });
</script>
