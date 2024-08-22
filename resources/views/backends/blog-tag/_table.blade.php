<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('tag.edit'))                    
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($blog_tags as $tag)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tag->title }}</td>
                    <td>{{ $tag->createdBy->name }}</td>
                    @if (auth()->user()->can('tag.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $tag->id }}" data-id="{{ $tag->id }}" {{ $tag->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $tag->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('tag.edit'))                            
                        <a href="#" data-href="{{ route('admin.blog-tag.edit', $tag->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('tag.delete'))                            
                        <form action="{{ route('admin.blog-tag.destroy', $tag->id) }}" class="d-inline-block form-delete-{{ $tag->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $tag->id }}" data-href="{{ route('admin.blog-tag.destroy', $tag->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $blog_tags->firstItem() }} {{ __('to') }} {{ $blog_tags->lastItem() }} {{ __('of') }} {{ $blog_tags->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $blog_tags->links() }}</div>
            </div>
        </div>
    </div>


</div>

