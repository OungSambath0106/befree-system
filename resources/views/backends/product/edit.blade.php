@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Edit Product') }}</h1>
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
                    <form method="POST" action="{{ route('admin.product.update', $product->id) }}"
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
                                                    if (count($product['translations'])) {
                                                        $translate = [];
                                                        foreach ($product['translations'] as $t) {
                                                            if ($t->locale == $lang['code'] && $t->key == 'name') {
                                                                $translate[$lang['code']]['name'] = $t->value;
                                                            }
                                                            if ($t->locale == $lang['code'] && $t->key == 'description') {
                                                                $translate[$lang['code']]['description'] = $t->value;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <input type="hidden" name="lang[]"
                                                                    value="{{ $lang['code'] }}">
                                                                <label
                                                                    for="name_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                                                <input type="name" id="name_{{ $lang['code'] }}"
                                                                    class="form-control @error('name') is-invalid @enderror"
                                                                    name="name[]" placeholder="{{ __('Enter Name') }}"
                                                                    value="{{ $translate[$lang['code']]['name'] ?? $product['name'] }}">

                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label
                                                                    for="description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                <textarea type="text" id="description_{{ $lang['code'] }}"
                                                                    class="form-control @error('description') is-invalid @enderror" name="description[]"
                                                                    placeholder="{{ __('Enter Description') }}" value="">{{ $translate[$lang['code']]['description'] ?? $product['description'] }}</textarea>

                                                                @error('description')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
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
                        <div class="card no_translate_wrapper">
                            <div class="card-header">
                                <h3 class="card-title">{{ __('General Info') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group col-md-6 ">
                                        <label class="required_lable" for="brand">{{ __('Brand') }}</label>
                                        <select name="brand_id" id="brand"
                                            class="form-control select2 @error('brand_id') is-invalid @enderror">
                                            <option value="">{{ __('Select Brand') }}</option>
                                            @foreach ($brands as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == old('brand_id', $product->brand_id) ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        <label class="required_lable" for="qty">{{ __('Quantity') }}</label>
                                        <input type="number" name="qty" id="qty" min="0"  class="form-control @error('qty') is-invalid @enderror"
                                        step="any" value="{{ old('qty', $product->qty) }}" oninput="this.value = this.value.replace(/^0+(?!$)/, '').replace(/\..*/, '')"></input>
                                        @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="required_label" for="size">{{ __('Size') }}</label>
                                        <select name="size[]" id="size" multiple class="form-control select2 @error('size') is-invalid @enderror">
                                            @for ($size = 35; $size <= 45; $size += 0.5)
                                                <option value="{{ $size }}"
                                                    @if (is_array(old('size', $product->size)) && in_array($size, old('size', $product->size))) selected @endif>
                                                    {{ __('Size ') . $size }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('size')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6 ">
                                        <label class="required_lable" for="price">{{ __('Price') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="price" id="price" min="0" oninput="validatePriceInput(this)" onkeydown="preventMinus(event)"
                                                class="form-control @error('price') is-invalid @enderror" step="any"
                                                value="{{ old('price', $product->price) }}">
                                        </div>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="image">{{ __('Image') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="hidden" name="images"
                                                        value="{{ $product->image }}">
                                                    <input type="file" class="custom-file-input image-file-input"
                                                        id="image" name="image" accept="image/png, image/jpeg">
                                                    <label class="custom-file-label"
                                                        for="image">{{ $product->image ?? __('Choose Image') }}</label>
                                                </div>
                                            </div>
                                            <div class="preview text-center border rounded mt-2" style="height: 150px">
                                                <img src="{{ $product->image && file_exists(public_path('uploads/products/' . $product->image)) ? asset('uploads/products/' . $product->image) : asset('uploads/default.png') }}"
                                                    alt="" height="100%">
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
    <script>
        // Function to prevent negative values
        function validatePriceInput(input) {
            if (input.value < 0) {
                input.value = '';
            }
        }

        // Function to prevent typing the minus (-) key
        function preventMinus(event) {
            if (event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        }
    </script>
@endpush
