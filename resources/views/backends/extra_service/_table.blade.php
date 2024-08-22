<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Thumbnail') }}</th>
                <th>{{ __('Title') }}</th>
                {{-- <th>{{ __('Description') }}</th> --}}
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('extra_service.edit'))                    
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($extra_services as $extra_service)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($extra_service->thumbnail && file_exists(public_path('uploads/service/' . $extra_service->thumbnail)))
                            {{ asset('uploads/service/'. $extra_service->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">

                    </td>
                    <td>{{ $extra_service->title }}</td>
                    <td>{{ @$extra_service->createdBy->name }}</td>
                    @if (auth()->user()->can('extra_service.edit'))                        
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $extra_service->id }}" data-id="{{ $extra_service->id }}" {{ $extra_service->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $extra_service->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('extra_service.edit'))
                        <a href="{{ route('admin.extra-service.edit', $extra_service->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('extra_service.delete'))                            
                        <form action="{{ route('admin.extra-service.destroy', $extra_service->id) }}" class="d-inline-block form-delete-{{ $extra_service->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $extra_service->id }}" data-href="{{ route('admin.extra-service.destroy', $extra_service->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $extra_services->firstItem() }} {{ __('to') }} {{ $extra_services->lastItem() }} {{ __('of') }} {{ $extra_services->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $extra_services->links() }}</div>
            </div>
        </div>
    </div>
</div>

