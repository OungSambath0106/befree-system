@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{__('Edit Amenity')}}</h1>
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
                        <form method="POST" action="{{ route('admin.amenities.update', $amenity->id) }}" enctype="multipart/form-data">
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
                                                            if (count($amenity['translations'])) {
                                                                $translate = [];
                                                                foreach ($amenity['translations'] as $t) {

                                                                    if ($t->locale == $lang['code'] && $t->key == "title") {
                                                                        $translate[$lang['code']]['title'] = $t->value;
                                                                    }
                                                                    if ($t->locale == $lang['code'] && $t->key == "value") {
                                                                        $translate[$lang['code']]['value'] = $t->value;
                                                                        // dd($translate[$lang['code']]['value']);
                                                                    }

                                                                }
                                                            }
                                                        ?>
                                                        <div class="tab-pane fade {{ $lang['code'] == $default_lang ? 'show active' : '' }} mt-3" id="lang_{{ $lang['code'] }}" role="tabpanel" aria-labelledby="lang_{{ $lang['code'] }}-tab">
                                                            <div class="form-group">
                                                                <input type="hidden" name="lang[]" value="{{ $lang['code'] }}">
                                                                <label for="title_{{ $lang['code'] }}">{{ __('Name') }}({{ strtoupper($lang['code']) }})</label>
                                                                <input type="text" id="title_{{ $lang['code'] }}" class="form-control @error('title') is-invalid @enderror"
                                                                    name="title[]" placeholder="{{__('Enter Title')}}" value="{{ $translate[$lang['code']]['title'] ?? $amenity['title'] }}" readonly>

                                                                @error('title')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label for="">{{ __('Amenity Detail') }}</label>
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>{{ __('Title') }}</th>
                                                                            <th>{{ __('Image') }}</th>
                                                                            <th>{{ __('Description') }}</th>
                                                                            <th>
                                                                                <button type="button"
                                                                                    class="btn btn-success btn-sm btn_add_row"
                                                                                    data-lang="{{ $lang['code'] }}">
                                                                                    <i class="fa fa-plus-circle"></i>
                                                                                </button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    @include('backends.amenity._amenity_detail_tbody')
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
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
        $('#upload_image').change(function(e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview img');

            reader.onload = function(e) {
                preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#upload_feature_image').change(function(e) {
            var reader = new FileReader();
            var preview = $(this).closest('.form-group').find('.preview_feature_image img');
            reader.onload = function(e) {

            preview.attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.btn_add_row').click(function(e) {
            var lang = $(this).data('lang');
            var tbody = $(`.product_detail_tbody_${lang}`);
            var numRows = tbody.find("tr").length;
            console.log(numRows);
            $.ajax({
                type: "get",
                url: window.location.href,
                data: {
                    "lang": lang,
                    "key": numRows
                },
                dataType: "json",
                success: function(response) {
                    // console.log(response);
                    $(tbody).append(response.tr);
                }
            });
        });


    </script>
@endpush
