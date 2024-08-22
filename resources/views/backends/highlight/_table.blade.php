<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>#</th>
                <th>{{ __('Thumbnail') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('highlight.edit'))
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($highlights as $highlight)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($highlight->thumbnail && file_exists(public_path('uploads/highlight/' . $highlight->thumbnail)))
                            {{ asset('uploads/highlight/'. $highlight->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" width="120px" class="profile_img_table">
                    </td>
                    <td>
                        {{ $highlight->title }}
                    </td>
                    <td>{{ @$highlight->createdBy->name }}</td>
                    @if (auth()->user()->can('highlight.edit'))
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input switcher_input status"
                                    id="status_{{ $highlight->id }}" data-id="{{ $highlight->id }}"
                                    {{ $highlight->status == 'active' ? 'checked' : '' }} name="status">
                                <label class="custom-control-label" for="status_{{ $highlight->id }}"></label>
                            </div>
                        </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('highlight.edit'))
                            <a href="{{ route('admin.highlight.edit', $highlight->id) }}"
                                class="btn btn-info btn-sm btn-edit">
                                <i class="fas fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        @endif
                        @if (auth()->user()->can('highlight.delete'))
                            <form action="{{ route('admin.highlight.destroy', $highlight->id) }}"
                                class="d-inline-block form-delete-{{ $highlight->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $highlight->id }}"
                                    data-href="{{ route('admin.highlight.destroy', $highlight->id) }}"
                                    class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $highlights->firstItem() }} {{ __('to') }}
                    {{ $highlights->lastItem() }} {{ __('of') }} {{ $highlights->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $highlights->links() }}</div>
            </div>
        </div>
    </div>


</div>
