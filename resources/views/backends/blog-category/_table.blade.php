<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('category.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($blog_categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->title }}</td>
                    <td>{{ $category->createdBy->name }}</td>
                    @if (auth()->user()->can('category.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $category->id }}" data-id="{{ $category->id }}" {{ $category->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $category->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('category.edit'))                            
                        <a href="#" data-href="{{ route('admin.blog-category.edit', $category->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif

                        @if (auth()->user()->can('category.delete'))
                        <form action="{{ route('admin.blog-category.destroy', $category->id) }}" class="d-inline-block form-delete-{{ $category->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $category->id }}" data-href="{{ route('admin.blog-category.destroy', $category->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $blog_categories->firstItem() }} {{ __('to') }} {{ $blog_categories->lastItem() }} {{ __('of') }} {{ $blog_categories->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $blog_categories->links() }}</div>
            </div>
        </div>
    </div>


</div>

