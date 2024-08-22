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
    .checkIP{
      width: 20px;
    }
  </style>
  @endpush
  @section('contents')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Create Role')}}</h1>
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
                        <form class="form-material form-horizontal" action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="name">{{ __('Name Position') }}</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('Type name permission')">
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <label style="font-size: 16px;" for="">{{ __('Select Permission') }}</label>
                        @error('permissions')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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
                                                <input type="checkbox" id="view_user" name="permissions[]" value="user.view">
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
                                                <input type="checkbox" id="user_create" name="permissions[]" value="user.create">
                                                <span class="slider round"></span>
                                                </label>
                                                <label class="ml-2" for="user_create">{{ __('Create User') }}</label>
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
                                                <input type="checkbox" id="user_edit" name="permissions[]"  value="user.edit">
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
                                                <input type="checkbox" id="user_delete" name="permissions[]" value="user.delete">
                                                <span class="slider round"></span>
                                            </label>
                                            <label class="ml-2" for="user_delete">{{ __('Delete User') }}</label>
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
                                                <input type="checkbox" id="view_role" name="permissions[]" value="role.view">
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
                                                <input type="checkbox" id="role_create" name="permissions[]" value="role.create">
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
                                                <input type="checkbox" id="role_edit" name="permissions[]"  value="role.edit">
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
                                                <input type="checkbox" id="role_delete" name="permissions[]" value="role.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="customer.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="customer.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="customer.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="customer.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="contact.view">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="contact.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="contact.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="blog.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="blog.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="blog.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="blog.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="category.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="category.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="category.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="category.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="tag.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="tag.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="tag.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="tag.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="comment.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="comment.create">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="room.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="room.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="room.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="room.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="rate.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="rate.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="rate.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="rate.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="allotment.view">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="calendar.view">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="staycation.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="staycation.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="staycation.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="staycation.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="gallery.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="gallery.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="gallery.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="gallery.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="gallery-category.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="gallery-category.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="gallery-category.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="gallery-category.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="amenity.view">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="amenity.edit">
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
                                <label for="" class="mr-2 mb-3">{{ __('Service Management') }}</label>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="d-flex align-items-center">
                                            <label class="switch">
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="service.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="service.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="service.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="service.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="extra_service.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="extra_service.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="extra_service.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="extra_service.delete">
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
                                                <input type="checkbox" id="view_slider" name="permissions[]" value="slider.view">
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
                                                <input type="checkbox" id="slider_create" name="permissions[]" value="slider.create">
                                                <span class="slider round"></span>
                                                </label>
                                                <label class="ml-2" for="slider_create">{{ __('Create Slider') }}</label>
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
                                                <input type="checkbox" id="slider_edit" name="permissions[]"  value="slider.edit">
                                                <span class="slider round"></span>
                                                </label>
                                                <label class="ml-2" for="slider_edit">{{ __('Edit Slider') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <div class="d-flex align-items-center">
                                            <label class="switch">
                                                <input type="checkbox" id="slider_delete" name="permissions[]" value="slider.delete">
                                                <span class="slider round"></span>
                                            </label>
                                            <label class="ml-2" for="slider_delete">{{ __('Delete Slider') }}</label>
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="highlight.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="highlight.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="highlight.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="highlight.delete">
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
                                                <input type="checkbox" id="view_blog" name="permissions[]" value="facility.view">
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
                                                <input type="checkbox" id="blog_create" name="permissions[]" value="facility.create">
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
                                                <input type="checkbox" id="blog_edit" name="permissions[]"  value="facility.edit">
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
                                                <input type="checkbox" id="blog_delete" name="permissions[]" value="facility.delete">
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
                                                <input type="checkbox" id="booking_report" name="permissions[]" value="booking_report.view">
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
                                                <input type="checkbox" id="view_menu" name="permissions[]" value="menu.view">
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
                                                <input type="checkbox" id="menu_create" name="permissions[]" value="menu.create">
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
                                                <input type="checkbox" id="menu_edit" name="permissions[]"  value="menu.edit">
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
                                                <input type="checkbox" id="menu_delete" name="permissions[]" value="menu.delete">
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
                                                <input type="checkbox" id="view_menu_explore" name="permissions[]" value="menu.explore.view">
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
                                                <input type="checkbox" id="menu_explore_create" name="permissions[]" value="menu.explore.create">
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
                                                <input type="checkbox" id="menu_explore_edit" name="permissions[]"  value="menu.explore.edit">
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
                                                <input type="checkbox" id="menu_explore_delete" name="permissions[]" value="menu.explore.delete">
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
                                        <input type="submit" value="{{ __('Submit') }}" class="btn btn-outline btn-primary btn-lg"/>
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
