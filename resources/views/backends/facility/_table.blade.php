<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Thumbnail') }}</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('facility.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $facility)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($facility->thumbnail && file_exists(public_path('uploads/facility/' . $facility->thumbnail)))
                            {{ asset('uploads/facility/'. $facility->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>
                        <img src="
                        @if ($facility->image && file_exists(public_path('uploads/facility/' . $facility->image)))
                            {{ asset('uploads/facility/'. $facility->image) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>
                        {{ $facility->title }}
                    </td>
                    <td>{{ @$facility->createdBy->name }}</td>
                    @if (auth()->user()->can('facility.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $facility->id }}" data-id="{{ $facility->id }}" {{ $facility->status == "active" ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $facility->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('facility.edit'))                            
                        <a href="{{ route('admin.facility.edit', $facility->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('facility.delete'))
                        <form action="{{ route('admin.facility.destroy', $facility->id) }}" class="d-inline-block form-delete-{{ $facility->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $facility->id }}" data-href="{{ route('admin.facility.destroy', $facility->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $facilities->firstItem() }} {{ __('to') }} {{ $facilities->lastItem() }} {{ __('of') }} {{ $facilities->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $facilities->links() }}</div>
            </div>
        </div>
    </div>


</div>

