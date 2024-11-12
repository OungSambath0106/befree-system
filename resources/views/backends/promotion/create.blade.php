@extends('backends.master')
@section('contents')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered li:first-child.select2-search.select2-search--inline .select2-search__field {
            height: 29px !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Add New Promotion') }}</h1>
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
                    <form method="POST" action="{{ route('admin.promotion.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                            {{-- @dump($language) --}}
                                            @foreach (json_decode($language, true) as $lang)
                                                @if ($lang['status'] == 1)
                                                    <li class="nav-item">
                                                        <a class="nav-link text-capitalize {{ $lang['code'] == $default_lang ? 'active' : '' }}"
                                                            id="lang_{{ $lang['code'] }}-tab" data-toggle="pill"
                                                            href="#lang_{{ $lang['code'] }}" data-lang="{{ $lang['code'] }}"
                                                            role="tab" aria-controls="lang_{{ $lang['code'] }}"
                                                            aria-selected="false">{{ \App\helpers\AppHelper::get_language_name($lang['name']) . '(' . strtoupper($lang['code']) . ')' }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="custom-content-below-tabContent">
                                            @foreach (json_decode($language, true) as $key => $lang)
                                                @if ($lang['status'] == 1)
                                                    <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3"
                                                        id="lang_{{ $lang['code'] }}" role="tabpanel"
                                                        aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                        <div class="row">
                                                            <div class="form-group col-md-12">
                                                                <input type="hidden" name="lang[]"
                                                                    value="{{ $lang['code'] }}">
                                                                <label for="title_{{ $lang['code'] }}">{{ __('Title') }}
                                                                    ({{ strtoupper($lang['code']) }})
                                                                </label>
                                                                <input type="text" id="title_{{ $lang['code'] }}"
                                                                    class="form-control @error('title') is-invalid @enderror"
                                                                    name="title[]" placeholder="{{ __('Enter Title') }}"
                                                                    value="">
                                                                @error('title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label
                                                                    for="description_{{ $lang['code'] }}">{{ __('Description') }}
                                                                    ({{ strtoupper($lang['code']) }})</label>
                                                                <textarea type="text"
                                                                    id="description_{{ $lang['code'] }}"
                                                                    class="form-control @error('description') is-invalid @enderror"
                                                                    name="description[]"
                                                                    placeholder="{{ __('Enter Description') }}"
                                                                    value=""></textarea>
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
                                    <div class="form-group col-md-6">
                                        <label class="required_label" for="promotion_type">{{ __('Promotion Type') }}</label>
                                        <select name="promotion_type" id="promotion_type" class="form-control select2 @error('Promotion_type') is-invalid @enderror" onchange="togglePromotionFields()">
                                            <option value="brand" selected>{{ __('By Brand') }}</option>
                                            <option value="product">{{ __('By Product') }}</option>
                                        </select>
                                        @error('promotion_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6" id="product_field">
                                        <label class="required_label" for="product">{{ __('Promotion by Product') }}</label>
                                        <select name="products[]" id="product" multiple class="form-control select2 @error('product') is-invalid @enderror">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6" id="brand_field" style="display: none;">
                                        <label class="required_label" for="brand">{{ __('Promotion by Brand') }}</label>
                                        <select name="brands[]" id="brand" multiple class="form-control select2 @error('brand') is-invalid @enderror">
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="required_label" for="discount_type">{{ __('Discount Type') }}</label>
                                        <select name="discount_type" id="discount_type" class="form-control select2 @error('discount_type') is-invalid @enderror" onchange="toggleDiscountFields()">
                                            <option value="percent" selected>{{ __('Percent') }}</option>
                                            <option value="amount">{{ __('Amount') }}</option>
                                        </select>
                                        @error('discount_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6" id="percent_field">
                                        <label class="required_label" for="percent_input">{{ __('Discount Percent') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <input type="number" name="percent" id="percent_input" min="0" oninput="validateDiscountInput(this)" onkeydown="preventMinus(event)"
                                                class="form-control @error('percent') is-invalid @enderror" step="any"
                                                value="{{ old('percent') }}">
                                        </div>

                                        @error('percent')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6" id="amount_field" style="display: none;">
                                        <label class="required_label" for="amount_input">{{ __('Discount Amount') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="amount" id="amount_input" min="0" oninput="validateDiscountInput(this)" onkeydown="preventMinus(event)"
                                                class="form-control @error('amount') is-invalid @enderror" step="any"
                                                value="{{ old('amount') }}">
                                        </div>

                                        @error('amount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="required_label">{{__('Start Date')}}</label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}"
                                            name="start_date" >
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="required_label">{{__('End Date')}}</label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}"
                                            name="end_date" >
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputFile">{{ __('Banner') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="hidden" name="banners"
                                                        class="banner_hidden">
                                                    <input type="file" class="custom-file-input header-file-input"
                                                        id="exampleInputFile" name="banner"
                                                        accept="image/png, image/jpeg">
                                                    <label class="custom-file-label"
                                                        for="exampleInputFile">{{ __('Choose Banner') }}</label>
                                                </div>
                                            </div>
                                            <div class="preview preview-multiple text-center border rounded mt-2"
                                                style="height: 150px">
                                                <img src="{{ asset('uploads/defualt.png') }}" alt=""
                                                    height="100%">
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
        const compressor = new window.Compress();
        $('.header-file-input').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
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
                            var temp_file = response.temp_files;
                            var img_container = $('<div></div>').addClass('img_container');
                            var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +
                                '/' + temp_file);
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
        $('.footer-file-input').change(function(e) {
            compressor.compress([...e.target.files], {
                size: 4,
                quality: 0.75,
            }).then((output) => {
                var files = Compress.convertBase64ToFile(output[0].data, output[0].ext);
                var formData = new FormData();

                var image_names_hidden = $(this).closest('.custom-file').find('input[type=hidden]');
                var container = $(this).closest('.form-group').find('.preview');
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
                            var temp_file = response.temp_files;
                            var img_container = $('<div></div>').addClass('img_container');
                            var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +
                                '/' + temp_file);
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


        $(document).on('click', '.nav-tabs .nav-link', function(e) {
            if ($(this).data('lang') != 'en') {
                $('.no_translate_wrapper').addClass('d-none');
            } else {
                $('.no_translate_wrapper').removeClass('d-none');
            }
        });
    </script>
    <script>
        function validateDiscountInput(input) {
            if (input.value < 0) {
                input.value = '';
            }
        }

        function preventMinus(event) {
            if (event.key === '-' || event.key === '+') {
                event.preventDefault();
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.brand-checkbox').forEach(function (brandCheckbox) {
                brandCheckbox.addEventListener('change', function () {
                    const brandId = this.getAttribute('data-brand');
                    const checked = this.checked;

                    document.querySelectorAll(`.product-checkbox.brand-${brandId}`).forEach(function (productCheckbox) {
                        productCheckbox.checked = checked;
                    });
                });
            });
        });
    </script>
    <script>
        function toggleDiscountFields() {
            var discountType = document.getElementById('discount_type').value;
            var amountField = document.getElementById('amount_field');
            var percentField = document.getElementById('percent_field');

            if (discountType === 'percent') {
                percentField.style.display = 'block';
                amountField.style.display = 'none';
            } else {
                percentField.style.display = 'none';
                amountField.style.display = 'block';
            }
        }
        window.onload = toggleDiscountFields;
    </script>
    <script>
        function togglePromotionFields() {
            var promotionType = document.getElementById('promotion_type').value;
            var brandField = document.getElementById('brand_field');
            var productField = document.getElementById('product_field');

            if (promotionType === 'brand') {
                brandField.style.display = 'block';
                productField.style.display = 'none';
            } else {
                brandField.style.display = 'none';
                productField.style.display = 'block';
            }
        }
        window.onload = togglePromotionFields;
    </script>
@endpush
