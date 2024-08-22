@push('css')
@endpush
<div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('Edit Gallery') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="{{ route('admin.gallery.update', $gallery->id) }}" class="submit-form" enctype="multipart/form-data" method="post">
            <div class="modal-body">
                @csrf
                @method('PUT')
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

                        </ul>
                        <div class="tab-content" id="custom-content-below-tabContent">
                            @foreach (json_decode($language, true) as $lang)
                                @if ($lang['status'] == 1)
                                    <?php
                                    if (count($gallery['translations'])) {
                                        $translate = [];
                                        foreach ($gallery['translations'] as $t) {
                                            if ($t->locale == $lang['code'] && $t->key == 'description') {
                                                $translate[$lang['code']]['description'] = $t->value;
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }}"
                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                        <div class="form-group">
                                            <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                            <label
                                                for="description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                                <textarea id="description_{{ $lang['code'] }}" class="form-control @error('description') is-invalid @enderror"
                                                name="description[]" rows="3">{{ $translate[$lang['code']]['description'] ?? $gallery['description'] }}</textarea>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div> -->
                        <div class="tab-content">
                            <div class="form-group">
                                <label class="required_lable" for="category">{{ __('Category') }}</label>
                                <select name="category_id[]" id="category" multiple
                                    class="form-control select2 @error('category_id') is-invalid @enderror">
                                    <option value="">{{ __('Select category') }}</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}"
                                            {{ (is_array(old('category_id', $category_selects)) && in_array($item->id, old('category_id', $category_selects))) || $item->id == old('category_id', $category_selects) ? 'selected' : '' }}>
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
                                <label for="exampleInputFile">{{__('Image')}}</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="hidden" name="image_names" class="image_names_hidden">
                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="image" accept="image/png, image/jpeg">
                                        <label class="custom-file-label" for="exampleInputFile">{{ $gallery->image ?? __('Choose file') }}</label>
                                    </div>
                                </div>
                                <div class="preview text-center border rounded mt-2" style="height: 248px">
                                    <img src="
                                    @if ($gallery->image && file_exists(public_path('uploads/gallery/' . $gallery->image)))
                                        {{ asset('uploads/gallery/'. $gallery->image) }}
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="submit" class="btn btn-primary submit">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>

{{-- @push('js') --}}
<script>
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

</script>
{{-- @endpush --}}
