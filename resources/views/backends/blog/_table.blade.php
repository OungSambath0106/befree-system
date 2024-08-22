<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Thumbnail') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Category') }}</th>
                {{-- <th>{{ __('Tags') }}</th> --}}
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('blog.edit'))                    
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($blogs as $blog)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($blog->thumbnail && file_exists(public_path('uploads/blogs/' . $blog->thumbnail)))
                            {{ asset('uploads/blogs/'. $blog->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>{{ $blog->title }}</td>
                    <td>{{ @$blog->category->title }}</td>
                    <td>{{ $blog->createdBy->name }}</td>
                    @if (auth()->user()->can('blog.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $blog->id }}" data-id="{{ $blog->id }}" {{ $blog->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $blog->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('blog.edit'))
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('blog.delete'))                            
                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" class="d-inline-block form-delete-{{ $blog->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $blog->id }}" data-href="{{ route('admin.blog.destroy', $blog->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $blogs->firstItem() }} {{ __('to') }} {{ $blogs->lastItem() }} {{ __('of') }} {{ $blogs->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $blogs->links() }}</div>
            </div>
        </div>
    </div>

</div>

