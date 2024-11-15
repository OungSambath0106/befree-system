@extends('backends.master')
@push('css')
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(18px);
            -ms-transform: translateX(18px);
            transform: translateX(18px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 22px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .checkIP {
            width: 20px;
        }
    </style>
@endpush
@section('contents')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Edit Role') }}</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-material form-horizontal" action="{{ route('admin.roles.update', $role->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-12">
                                        @if($role->name == 'customer' || $role->name == 'partner')
                                            <Label class="border p-2">{{ $role->name }}</Label>
                                        @endif
                                        @if($role->name != 'customer' && $role->name != 'partner')
                                            <div class="form-group">
                                                <label for="name">@lang('Name Position')</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" value="{{ $role->name }}" name="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        placeholder="@lang('Type name permission')">
                                                    @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <label style="font-size: 16px;" for="">{{ __('Select Permission') }}</label>
                                <hr>
                                <br>

                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('User Setup') }}</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                            <input type="checkbox" id="view_user" name="permissions[]"
                                                                @if (in_array('user.view', $role_permissions)) checked @endif
                                                                value="user.view">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="view_user">{{ __('View User') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                            <input type="checkbox" id="user_create" name="permissions[]"
                                                                @if (in_array('user.create', $role_permissions)) checked @endif
                                                                value="user.create">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2"
                                                            for="user_create">{{ __('Create User') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <!-- Rounded switch -->

                                                        <label class="switch">
                                                            <input type="checkbox" id="user_edit" name="permissions[]"
                                                                @if (in_array('user.edit', $role_permissions)) checked @endif
                                                                value="user.edit">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="user_edit">{{ __('Edit User') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                            <input type="checkbox" id="user_delete" name="permissions[]"
                                                                @if (in_array('user.delete', $role_permissions)) checked @endif
                                                                value="user.delete">
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2"
                                                            for="user_delete">{{ __('Delete User') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_role" name="permissions[]" @if (in_array('role.view', $role_permissions)) checked @endif value="role.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_role">{{ __('View Role') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="role_create" name="permissions[]" @if (in_array('role.create', $role_permissions)) checked @endif value="role.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="role_create">{{ __('Create Role') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <!-- Rounded switch -->

                                                        <label class="switch">
                                                        <input type="checkbox" id="role_edit" name="permissions[]" @if (in_array('role.edit', $role_permissions)) checked @endif value="role.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="role_edit">{{ __('Edit Role') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="role_delete" name="permissions[]" @if (in_array('role.delete', $role_permissions)) checked @endif value="role.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="role_delete">{{ __('Delete Role') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <hr>
                                </div>

                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Banner') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_banner" name="permissions[]" @if (in_array('banner.view', $role_permissions)) checked @endif value="banner.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_banner">{{ __('View Banner') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="banner_create" name="permissions[]"  @if (in_array('banner.create', $role_permissions)) checked @endif  value="banner.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="banner_create">{{ __('Create Banner') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <!-- Rounded switch -->
        
                                                        <label class="switch">
                                                        <input type="checkbox" id="banner_edit" name="permissions[]" @if (in_array('banner.edit', $role_permissions)) checked @endif  value="banner.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="banner_edit">{{ __('Edit Banner') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="banner_delete" name="permissions[]" @if (in_array('banner.delete', $role_permissions)) checked @endif value="banner.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="banner_delete">{{ __('Delete Banner') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('OnBoard') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_onboard" name="permissions[]" @if (in_array('onboard.view', $role_permissions)) checked @endif value="onboard.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_onboard">{{ __('View Onboard') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="onboard_create" name="permissions[]"  @if (in_array('onboard.create', $role_permissions)) checked @endif  value="onboard.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="onboard_create">{{ __('Create Onboard') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <!-- Rounded switch -->
        
                                                        <label class="switch">
                                                        <input type="checkbox" id="onboard_edit" name="permissions[]" @if (in_array('onboard.edit', $role_permissions)) checked @endif  value="onboard.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="onboard_edit">{{ __('Edit Onboard') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="onboard_delete" name="permissions[]" @if (in_array('onboard.delete', $role_permissions)) checked @endif value="onboard.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="onboard_delete">{{ __('Delete Onboard') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
        
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Promotion') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_promotion" name="permissions[]" @if (in_array('promotion.view', $role_permissions)) checked @endif value="promotion.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_promotion">{{ __('View Promotion') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="promotion_create" name="permissions[]"  @if (in_array('promotion.create', $role_permissions)) checked @endif  value="promotion.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="promotion_create">{{ __('Create Promotion') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <!-- Rounded switch -->
        
                                                        <label class="switch">
                                                        <input type="checkbox" id="promotion_edit" name="permissions[]" @if (in_array('promotion.edit', $role_permissions)) checked @endif  value="promotion.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="promotion_edit">{{ __('Edit Promotion') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="promotion_delete" name="permissions[]" @if (in_array('promotion.delete', $role_permissions)) checked @endif value="promotion.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="promotion_delete">{{ __('Delete Promotion') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-3 col-form-label"></label>
                                            <div class="col-md-8">
                                                <input type="submit" value="{{ __('Submit') }}"
                                                    class="btn btn-outline btn-primary btn-lg" />
                                                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline btn-danger btn-lg">{{ __('Cancel') }}</a>
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
