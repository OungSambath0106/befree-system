<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Tttle') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('amenity.edit'))                    
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($amenities as $amenity)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $amenity->title }}</td>
                    <td>{{ @$amenity->createdBy->name }}</td>
                    @if (auth()->user()->can('amenity.edit'))                        
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $amenity->id }}" data-id="{{ $amenity->id }}" {{ $amenity->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $amenity->id }}"></label>
                        </div>
                    </td>
                    @endif
                    @if (auth()->user()->can('amenity.edit'))                        
                    <td>
                        <a href="{{ route('admin.amenities.edit', $amenity->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        {{-- <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" class="d-inline-block form-delete-{{ $amenity->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $amenity->id }}" data-href="{{ route('admin.amenities.destroy', $amenity->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form> --}}
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
                    {{ __('Showing') }} {{ $amenities->firstItem() }} {{ __('to') }} {{ $amenities->lastItem() }} {{ __('of') }} {{ $amenities->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $amenities->links() }}</div>
            </div>
        </div>
    </div>
</div>

