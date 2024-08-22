@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{__('Edit Rate Plan')}}</h1>
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
                        <form method="POST" action="{{ route('admin.rate_plan.update', $ratePlan->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            {{-- <input type="hidden" name="room_id" value="{{ $room_id }}"> --}}
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
                                                            if (count($ratePlan['translations'])) {
                                                                $translate = [];
                                                                foreach ($ratePlan['translations'] as $t) {

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
                                                                <input type="title" id="title_{{ $lang['code'] }}" class="form-control @error('title') is-invalid @enderror"
                                                                    name="title[]" placeholder="{{__('Enter Title')}}" value="{{ $translate[$lang['code']]['title'] ?? $ratePlan['title'] }}">

                                                                @error('name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="description_{{ $lang['code'] }}">{{ __('Description') }}({{ strtoupper($lang['code']) }})</label>
                                                                <textarea id="description_{{ $lang['code'] }}" class="form-control @error('description') is-invalid @enderror"
                                                                    name="description[]" rows="3">{{ $translate[$lang['code']]['description'] ?? $ratePlan['description'] }}</textarea>
                                                                    @error('description')
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
                                        <input type="hidden" name="room_id" value="{{ $ratePlan->room_id }}">
                                        <div class="form-group col-md-6">
                                            <label for="price">{{__('Price')}}</label>
                                            <input type="text" id="price" class="form-control @error('price') is-invalid @enderror"
                                                name="price" placeholder="{{__('Enter Price')}}" value="{{ old('price', $ratePlan->price) }}">
                                            @error('price')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="" for="type">{{__('Type')}}</label>
                                            <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
                                                <option value="room" {{ old('type', $ratePlan->type) === 'room' ? 'selected' : '' }}>{{__('Room')}}</option>
                                                <option value="package" {{ old('type', $ratePlan->type) === 'package' ? 'selected' : '' }}>{{__('Package')}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12 special_package mt-3">
                                            <label for="">{{__('Special Package')}}</label>
                                            <div class="row">  
                                                @if ($amenities->value)
                                                @php
                                                    $allAmenities = $amenities->value;
                                                    
                                                    $selectedAmenities = empty($ratePlan->special_package) ? [] : array_map(function ($amenity) {
                                                        return json_decode($amenity, true);
                                                    }, $ratePlan->special_package);
                                                @endphp
                                                @foreach ($allAmenities as $row)
                                                    @php
                                                        $isChecked = false;
                                                        foreach ($selectedAmenities as $selectedAmenity) {
                                                            if ($selectedAmenity['title'] == $row['title'] && $selectedAmenity['image'] == $row['image']) {
                                                                $isChecked = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    <div class="col-12 col-md-2 mt-2">
                                                        <div class="icheck-primary d-inline align-content-center">
                                                            <input type="checkbox" id="checkboxPrimary{{ $loop->index }}"  name="special_package[]" {{ $isChecked ? 'checked' : '' }} value="{{ json_encode($row) }}">
                                                            <label for="checkboxPrimary{{ $loop->index }}">
                                                                {{ $row['title'] }}
                                                            </label>
                                                        </div>
                                                        <img src="{{ asset('uploads/amenity/' . $row['image']) }}" alt="Image" style="width: 23px; height: 23px">
                                                    </div>
                                                @endforeach
                                                @endif
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
    $(document).ready(function(){
        checkType();
        $("#type").change(function(){
            checkType();
        });
        function checkType() {
            if ($("#type").val() === "package") {
                $(".special_package").show();
            } else {
                $(".special_package").hide();
            }
        }
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
