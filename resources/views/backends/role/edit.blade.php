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
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Customer') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('customer.view', $role_permissions)) checked @endif value="customer.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Customer') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('customer.create', $role_permissions)) checked @endif value="customer.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Customer') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('customer.edit', $role_permissions)) checked @endif  value="customer.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Customer') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('customer.delete', $role_permissions)) checked @endif value="customer.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Customer') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('contact.view', $role_permissions)) checked @endif value="contact.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Contact') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('contact.edit', $role_permissions)) checked @endif  value="contact.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Reply Contact') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('contact.delete', $role_permissions)) checked @endif value="contact.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Contact') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Blog Management') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('blog.view', $role_permissions)) checked @endif value="blog.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View blog') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('blog.create', $role_permissions)) checked @endif  value="blog.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create blog') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('blog.edit', $role_permissions)) checked @endif  value="blog.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit blog') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('blog.delete', $role_permissions)) checked @endif value="blog.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete blog') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('category.view', $role_permissions)) checked @endif value="category.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('category.create', $role_permissions)) checked @endif value="category.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Category') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('category.edit', $role_permissions)) checked @endif  value="category.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('category.delete', $role_permissions)) checked @endif value="category.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('tag.view', $role_permissions)) checked @endif value="tag.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Tag') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('tag.create', $role_permissions)) checked @endif value="tag.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Tag') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('tag.edit', $role_permissions)) checked @endif  value="tag.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Tag') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('tag.delete', $role_permissions)) checked @endif value="tag.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Tag') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]"  @if (in_array('comment.view', $role_permissions)) checked @endif value="comment.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Comment') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('comment.create', $role_permissions)) checked @endif value="comment.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Reply Comment') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Room Management') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('room.view', $role_permissions)) checked @endif value="room.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Room') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('room.create', $role_permissions)) checked @endif value="room.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Room') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('room.edit', $role_permissions)) checked @endif  value="room.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Room') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('room.delete', $role_permissions)) checked @endif value="room.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Room') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('rate.view', $role_permissions)) checked @endif value="rate.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Rate Plan') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('rate.create', $role_permissions)) checked @endif value="rate.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Rate Plan') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('rate.edit', $role_permissions)) checked @endif  value="rate.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Rate Plan') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('rate.delete', $role_permissions)) checked @endif value="rate.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Rate Plan') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('allotment.view', $role_permissions)) checked @endif value="allotment.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Room Allotment') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('calendar.view',$role_permissions)) checked @endif value="calendar.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Room Calendar') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Staycation Management') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('staycation.view', $role_permissions)) checked @endif value="staycation.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Staycation') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('staycation.create', $role_permissions)) checked @endif value="staycation.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Staycation') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('staycation.edit', $role_permissions)) checked @endif  value="staycation.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Staycation') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('staycation.delete', $role_permissions)) checked @endif value="staycation.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Staycation') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Gallery') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" value="gallery.view" @if (in_array('gallery.view', $role_permissions)) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Gallery') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('gallery.create', $role_permissions)) checked @endif  value="gallery.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Gallery') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('gallery.edit', $role_permissions)) checked @endif  value="gallery.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Gallery') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('gallery.delete', $role_permissions)) checked @endif value="gallery.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Gallery') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Gallery Category') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" value="gallery-category.view" @if (in_array('gallery-category.view', $role_permissions)) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Gallery Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('gallery-category.create', $role_permissions)) checked @endif  value="gallery-category.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Gallery Category') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('gallery-category.edit', $role_permissions)) checked @endif  value="gallery-category.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Gallery Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('gallery-category.delete', $role_permissions)) checked @endif value="gallery-category.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Gallery Category') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Amenities') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('amenity.view', $role_permissions)) checked @endif value="amenity.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Amenities') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('amenity.edit', $role_permissions)) checked @endif  value="amenity.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Amenities') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Service') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('service.view', $role_permissions)) checked @endif value="service.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('service.create', $role_permissions)) checked @endif value="service.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Service') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('service.edit', $role_permissions)) checked @endif  value="service.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('service.delete', $role_permissions)) checked @endif value="service.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]" @if (in_array('extra_service.view', $role_permissions)) checked @endif value="extra_service.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Extra Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('extra_service.create', $role_permissions)) checked @endif value="extra_service.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Extra Service') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('extra_service.edit', $role_permissions)) checked @endif  value="extra_service.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Extra Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('extra_service.delete', $role_permissions)) checked @endif value="extra_service.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Extra Service') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Slider Setup') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_slider" name="permissions[]" @if (in_array('slider.view', $role_permissions)) checked @endif value="slider.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_slider">{{ __('View slider') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="slider_create" name="permissions[]" @if (in_array('slider.create', $role_permissions)) checked @endif value="slider.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="slider_create">{{ __('Create slider') }}</label>
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
                                                        <input type="checkbox" id="slider_edit" name="permissions[]" @if (in_array('slider.edit', $role_permissions)) checked @endif  value="slider.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="slider_edit">{{ __('Edit slider') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="slider_delete" name="permissions[]" @if (in_array('slider.delete', $role_permissions)) checked @endif value="slider.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="slider_delete">{{ __('Delete slider') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Highlight') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]"  @if (in_array('highlight.view', $role_permissions)) checked @endif value="highlight.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Highlight') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('highlight.create', $role_permissions)) checked @endif  value="highlight.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Highlight') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('highlight.edit', $role_permissions)) checked @endif  value="highlight.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Highlight') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('highlight.delete', $role_permissions)) checked @endif value="highlight.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Highlight') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Manage Facility') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_blog" name="permissions[]"  @if (in_array('facility.view', $role_permissions)) checked @endif value="facility.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_blog">{{ __('View Facility') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="blog_create" name="permissions[]" @if (in_array('facility.create', $role_permissions)) checked @endif  value="facility.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_create">{{ __('Create Facility') }}</label>
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
                                                        <input type="checkbox" id="blog_edit" name="permissions[]" @if (in_array('facility.edit', $role_permissions)) checked @endif  value="facility.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="blog_edit">{{ __('Edit Facility') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="blog_delete" name="permissions[]" @if (in_array('facility.delete', $role_permissions)) checked @endif value="facility.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="blog_delete">{{ __('Delete Facility') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Report') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="booking_report" name="permissions[]" @if (in_array('booking_report.view', $role_permissions)) checked @endif value="booking_report.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="booking_report">{{ __('View booking report') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="view_item_order" name="permissions[]" value="item_order.view">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="view_item_order">{{ __('View item order') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <hr>
                                </div>
        
                                <div>
                                    <div class="d-flex">
                                        <label for="" class="mr-2 mb-3">{{ __('Menu') }}</label>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_menu" name="permissions[]" @if (in_array('menu.view', $role_permissions)) checked @endif value="menu.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_menu">{{ __('View menu') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="menu_create" name="permissions[]"  @if (in_array('menu.create', $role_permissions)) checked @endif  value="menu.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="menu_create">{{ __('Create menu') }}</label>
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
                                                        <input type="checkbox" id="menu_edit" name="permissions[]" @if (in_array('menu.edit', $role_permissions)) checked @endif  value="menu.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="menu_edit">{{ __('Edit menu') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="menu_delete" name="permissions[]" @if (in_array('menu.delete', $role_permissions)) checked @endif value="menu.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="menu_delete">{{ __('Delete menu') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="view_menu_explore" name="permissions[]" @if (in_array('menu.explore.view', $role_permissions)) checked @endif value="menu.explore.view">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="view_menu_explore">{{ __('View menu explore') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
        
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <label class="switch">
                                                        <input type="checkbox" id="menu_explore_create" name="permissions[]"  @if (in_array('menu.explore.create', $role_permissions)) checked @endif value="menu.explore.create">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="menu_explore_create">{{ __('Create menu explore') }}</label>
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
                                                        <input type="checkbox" id="menu_explore_edit" name="permissions[]" @if (in_array('menu.explore.edit', $role_permissions)) checked @endif  value="menu.explore.edit">
                                                        <span class="slider round"></span>
                                                        </label>
                                                        <label class="ml-2" for="menu_explore_edit">{{ __('Edit menu explore') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <div class="input-group mb-3">
                                                    <div class="d-flex align-items-center">
                                                    <label class="switch">
                                                        <input type="checkbox" id="menu_explore_delete" name="permissions[]" @if (in_array('menu.explore.delete', $role_permissions)) checked @endif value="menu.explore.delete">
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <label class="ml-2" for="menu_explore_delete">{{ __('Delete menu explore') }}</label>
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
