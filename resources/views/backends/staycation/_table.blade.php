<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Thumbnail') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('staycation.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($staycations as $staycation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($staycation->thumbnail && file_exists(public_path('uploads/staycation/' . $staycation->thumbnail)))
                            {{ asset('uploads/staycation/'. $staycation->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>{{ $staycation->title }}</td>
                    <td>{{ $staycation->createdBy->name }}</td>
                    @if (auth()->user()->can('staycation.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $staycation->id }}" data-id="{{ $staycation->id }}" {{ $staycation->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $staycation->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                    @if (auth()->user()->can('staycation.edit'))
                    <a href="{{ route('admin.staycation.edit', $staycation->id) }}" class="btn btn-info btn-sm btn-edit">
                        <i class="fas fa-pencil-alt"></i>
                        {{ __('Edit') }}
                    </a>
                    @endif
                    @if (auth()->user()->can('staycation.delete'))
                        <form action="{{ route('admin.staycation.destroy', $staycation->id) }}" class="d-inline-block form-delete-{{ $staycation->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $staycation->id }}" data-href="{{ route('admin.staycation.destroy', $staycation->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $staycations->firstItem() }} {{ __('to') }} {{ $staycations->lastItem() }} {{ __('of') }} {{ $staycations->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $staycations->links() }}</div>
            </div>
        </div>
    </div>


</div>

