<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Image') }}</th>
                {{-- <th>{{__('Description')}}</th> --}}
                <th>{{__('Category')}}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('gallery.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($gallerys as $gallery)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($gallery->image && file_exists(public_path('uploads/gallery/' . $gallery->image)))
                            {{ asset('uploads/gallery/'. $gallery->image) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    {{-- <td>{{Str::limit($gallery->description,50)}}</td> --}}
                    
                    <td>
                        @foreach ($gallery->categories as $index => $category)
                                <span class="badge badge-category">{{ $category->name }}</span>
                                @if ($index < $gallery->categories->count() - 1)
                                            |
                                @endif
                        @endforeach
                    </td>
                    <td>{{ @$gallery->createdBy->name }}</td>
                    @if (auth()->user()->can('gallery.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $gallery->id }}" data-id="{{ $gallery->id }}" {{ $gallery->status == "active" ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $gallery->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('gallery.edit'))                            
                        <a href="#" data-href="{{ route('admin.gallery.edit', $gallery->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('gallery.delete'))                            
                        <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" class="d-inline-block form-delete-{{ $gallery->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $gallery->id }}" data-href="{{ route('admin.gallery.destroy', $gallery->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $gallerys->firstItem() }} {{ __('to') }} {{ $gallerys->lastItem() }} {{ __('of') }} {{ $gallerys->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $gallerys->links() }}</div>
            </div>
        </div>
    </div>


</div>

