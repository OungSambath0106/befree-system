@extends('backends.master')
@section('contents')
    <!-- Content Wrapper. Contains page content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{__('Add New Customer')}}</h1>
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
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <!-- /.card-header -->
                            <form method="POST" action="{{ route('admin.customer.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 ">
                                            <label class="required_label">{{__('First Name')}}</label>
                                            <input type="name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}"
                                                name="first_name" placeholder="{{__('Enter First Name')}}">
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_label">{{__('Last Name')}}</label>
                                            <input type="name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}"
                                                name="last_name" placeholder="{{__('Enter Last Name')}}" >
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_label">{{__('Phone Number')}}</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                                                name="phone" placeholder="{{__('Enter Phone Number')}}" >
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_label">{{__('Email')}}</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                                name="email" placeholder="{{__('Enter Email')}}" >
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="required_label">{{__('Password')}}</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" value=""
                                                name="password" placeholder="{{__('Enter Password')}}" >
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="form-group">
                                                <label for="exampleInputFile">{{__('Image')}}</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="hidden" name="image_names" class="image_names_hidden">
                                                        <input type="file" class="custom-file-input" id="exampleInputFile" name="image" accept="image/png, image/jpeg">
                                                        <label class="custom-file-label" for="exampleInputFile">{{ __('Choose file') }}</label>
                                                    </div>
                                                </div>
                                                <span class="text-info text-xs">{{ __('Recommend size 512 x 512 px') }}</span>
                                                <div class="preview preview-multiple text-center border rounded mt-2" style="height: 150px">
                                                    <img src="{{ asset('uploads/default-profile.png') }}" alt="" height="100%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
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
        $('.custom-file-input').change(function (e) {
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
                            var img_container = $('<div></div>').addClass('img_container');
                            var img = $('<img>').attr('src', "{{ asset('uploads/temp') }}" +'/'+ temp_file);
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
@endpush
