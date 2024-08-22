<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>#</th>
                <th class="">{{ __('UserName') }}</th>
                <th>{{ __('Comment') }}</th>
                @if (auth()->user()->can('comment.create'))
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ @$comment->customer->first_name }} {{ @$comment->customer->last_name }}</td>
                    <td>{{ Str::limit($comment->content, 80) }}</td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status"
                                id="status_{{ $comment->id }}" data-id="{{ $comment->id }}"
                                {{ $comment->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $comment->id }}"></label>
                        </div>
                    </td>
                    @if (auth()->user()->can('comment.create'))
                        <td>
                            <a href="{{ route('admin.comment.show', $comment->id) }}"
                                class="btn btn-info btn-sm btn-edit">
                                <i class="fas fa-eye"></i>
                                {{ __('View') }}
                            </a>
                            <form action="{{ route('admin.comment.destroy', $comment->id) }}"
                                class="d-inline-block form-delete-{{ $comment->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $comment->id }}"
                                    data-href="{{ route('admin.comment.destroy', $comment->id) }}"
                                    class="btn btn-danger btn-sm btn-delete">
                                    <i class="fa fa-trash-alt"></i>
                                    {{ __('Delete') }}
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $comments->firstItem() }} {{ __('to') }} {{ $comments->lastItem() }}
                    {{ __('of') }} {{ $comments->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $comments->links() }}</div>
            </div>
        </div>
    </div>
</div>
