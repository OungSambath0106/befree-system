@extends('backends.master')
@push('css')
    <style>
        .preview {
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
                    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <form action="{{ route('admin.setting.update.environment') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">{{ __('SMTP Settings') }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_HOST">
                                                        <label class="col-from-label text-uppercase">{{ __('Mail Host') }}</label>
                                                        <input type="text" class="form-control" name="MAIL_HOST" value="{{  env('MAIL_HOST') }}" placeholder="Mail Host">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_PORT">
                                                        <label class="col-from-label text-uppercase">{{ __('Mail Port') }}</label>
                                                        <input type="text" class="form-control" name="MAIL_PORT" value="{{  env('MAIL_PORT') }}" placeholder="Mail Port">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_USERNAME">
                                                        <label class="col-from-label text-uppercase">{{ __('Mail Username') }}</label>
                                                        <input type="text" class="form-control" name="MAIL_USERNAME" value="{{  env('MAIL_USERNAME') }}" placeholder="Mail Username">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                                                        <label class="col-from-label text-uppercase">{{ __('Mail Password') }}</label>
                                                        <input type="password" class="form-control" name="MAIL_PASSWORD" value="{{  env('MAIL_PASSWORD') }}" placeholder="Mail Password">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                                                        <label class="col-from-label text-uppercase">{{ __('MAIL ENCRYPTION') }}</label>
                                                        <input type="text" class="form-control" name="MAIL_ENCRYPTION" value="{{  env('MAIL_ENCRYPTION') }}" placeholder="MAIL ENCRYPTION">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                                                        <label class="col-from-label text-uppercase">{{ __('MAIL FROM ADDRESS') }}</label>
                                                        <input type="email" class="form-control" name="MAIL_FROM_ADDRESS" value="{{  env('MAIL_FROM_ADDRESS') }}" placeholder="MAIL FROM ADDRESS">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4">
                                                    <div class="form-group">
                                                        <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                                                        <label class="col-from-label text-uppercase">{{ __('MAIL FROM NAME') }}</label>
                                                        <input type="text" class="form-control" name="MAIL_FROM_NAME" value="{{  env('MAIL_FROM_NAME') }}" placeholder="MAIL FROM NAME">
                                                    </div>
                                                </div>
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
@endsection
