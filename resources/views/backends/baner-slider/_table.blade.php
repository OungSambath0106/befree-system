<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th >#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Banner') }}</th>
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($baners as $baner)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $baner->name }}</td>
                    <td>
                        <img width="40%" height="auto" src="
                        @if ($baner->image && file_exists(public_path('uploads/baner-slider/' . $baner->image))) {{ asset('uploads/baner-slider/' . $baner->image) }}
                        @else
                            {{ asset('uploads/image/default.png') }} @endif
                        "
                            alt="" class="profile_img_table">
                    </td>
                    <td>{{ $baner->createdBy->name }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $baner->id }}" data-id="{{ $baner->id }}" {{ $baner->status == 1 ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $baner->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('admin.baner-slider.edit', $baner->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>

                        <form action="{{ route('admin.baner-slider.destroy', $baner->id) }}" class="d-inline-block form-delete-{{ $baner->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $baner->id }}" data-href="{{ route('admin.baner-slider.destroy', $baner->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
