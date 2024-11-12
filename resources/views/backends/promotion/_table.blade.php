<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Discount Type') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Banner') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('End Date') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($promotions as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->discount_type ?? 'Null' }}</td>
                    <td>{{ $item->title ?? 'Null' }}</td>
                    <td>
                        <img width="90%" height="auto" src="
                        @if ($item->banner && file_exists(public_path('uploads/promotions/' . $item->banner))) {{ asset('uploads/promotions/' . $item->banner) }}
                        @else
                        {{ asset('uploads/defualt.png') }} @endif
                        "
                        alt="" class="profile_img_table" style="object-fit: cover">
                    </td>
                    <td> {{ \Carbon\Carbon::parse($item->start_date)->format('F d, Y') }} </td>
                    <td> {{ \Carbon\Carbon::parse($item->end_date)->format('F d, Y') }} </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status"
                                id="status_{{ $item->id }}" data-id="{{ $item->id }}"
                                {{ $item->status == 1 ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $item->id }}"></label>
                        </div>
                    </td>

                    <td>
                        <a href="{{ route('admin.promotion.edit', $item->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        <form action="{{ route('admin.promotion.destroy', $item->id) }}"
                            class="d-inline-block form-delete-{{ $item->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $item->id }}"
                                data-href="{{ route('admin.promotion.destroy', $item->id) }}"
                                class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12  text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $promotions->firstItem() }} {{ __('to') }} {{ $promotions->lastItem() }}
                    {{ __('of') }} {{ $promotions->total() }} {{ __('entries') }}
                </div>
                <div class="col-12  pagination-nav pr-3"> {{ $promotions->links() }}</div>
            </div>
        </div>
    </div>
</div>
