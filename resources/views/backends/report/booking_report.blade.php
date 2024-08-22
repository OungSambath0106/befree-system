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
                <h3>{{ __('Booking Report') }}</h3>
            </div>
            <div class="col-sm-6" style="text-align: right">
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h3 class="card-title">{{ __('Booking List') }}</h3>
                            </div>

                            {{-- <div class="col-sm-6">
                                @if (auth()->user()->can('room.create'))
                                <a class="btn btn-primary float-right" href="{{ route('admin.room.create') }}">
                                    <i class=" fa fa-plus-circle"></i>
                                    {{ __('Add New') }}
                                </a>
                                @endif
                            </div> --}}
                        </div>
                    </div>
                    <!-- /.card-header -->

                    {{-- table --}}
                    @include('backends.report.partials._booking_report_table')

                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade modal_form" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

@endsection
@push('js')
<script>

    $(document).on('click','.dropdown .dropdown-menu a.transaction_status',function(e){
        e.preventDefault();
        console.log('okk');
        var id = $(this).data('id');
        var value = $(this).data('value');
        $.ajax({
            type: "GET",
            url: '{{ route("admin.booking_report.update_status") }}',
            data: {
                'status' : value,
                'id' : id
            },
            dataType: "json",
            success: function (response) {
                if (response.view) {
                    $('.table-wrapper').replaceWith(response.view);
                    if(response.success == true){
                        toastr.success(response.msg);
                    }
                }
            }
        });
    });

</script>
@endpush
