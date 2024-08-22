<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th >#</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('First Name') }}</th>
                <th>{{ __('Last Name') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Email') }}</th>
                @if (auth()->user()->can('customer.edit'))    
                <th>{{ __('Status') }}</th>
                @endif
                <th>{{ __('Created By') }}</th>
                <th>{{ __('Created date') }}</th>
                @if (auth()->user()->can('customer.edit'))    
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($customer->image && file_exists(public_path('uploads/customers/' . $customer->image)))
                            {{ asset('uploads/customers/'. $customer->image) }}
                        @else
                            {{ asset('uploads/default-profile.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td>
                    <td>{{ $customer->first_name }}</td>
                    <td>{{ $customer->last_name }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->email }}</td>
                    @if (auth()->user()->can('customer.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $customer->id }}" data-id="{{ $customer->id }}" {{ $customer->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $customer->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>{{ @$customer->createdBy->name }}</td>
                    <td>{{ $customer->created_at->format('d M Y h:i A') }}</td>
                    <td>
                        @if (auth()->user()->can('customer.edit'))
                        <a href="{{ route('admin.customer.edit', $customer->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('customer.delete'))                            
                        <form action="{{ route('admin.customer.destroy', $customer->id) }}" class="d-inline-block form-delete-{{ $customer->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $customer->id }}" data-href="{{ route('admin.customer.destroy', $customer->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $customers->firstItem() }} {{ __('to') }} {{ $customers->lastItem() }} {{ __('of') }} {{ $customers->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $customers->links() }}</div>
            </div>
        </div>
    </div>
</div>
