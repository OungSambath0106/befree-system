<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Image') }}</th>
                <th class="">{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($product->image && file_exists(public_path('uploads/products/' . $product->image))) {{ asset('uploads/products/' . $product->image) }}
                        @else
                            {{ asset('uploads/defualt.png') }} @endif
                        "
                            alt="" class="profile_img_table">
                    </td>
                    <td>
                        <span class="ml-2">
                            {{ $product->name ?? 'Null' }}
                        </span>
                    </td>
                    <td>{{ $product->brand->name ?? 'Null' }}</td>
                    <td>{{ $product->createdBy->name ?? 'Null' }}</td>
                    <td>
                        @if (auth()->user()->can('product.edit'))
                            <a href="{{ route('admin.product.edit', $product->id) }}"
                                class="btn btn-info btn-sm btn-edit">
                                <i class="fas fa-pencil-alt"></i>
                                {{ __('Edit') }}
                            </a>
                        @endif
                        @if (auth()->user()->can('product.delete'))
                            <form action="{{ route('admin.product.destroy', $product->id) }}"
                                class="d-inline-block form-delete-{{ $product->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-id="{{ $product->id }}"
                                    data-href="{{ route('admin.product.destroy', $product->id) }}"
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
                    {{ __('Showing') }} {{ $products->firstItem() }} {{ __('to') }} {{ $products->lastItem() }}
                    {{ __('of') }} {{ $products->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $products->links() }}</div>
            </div>
        </div>
    </div>


</div>
