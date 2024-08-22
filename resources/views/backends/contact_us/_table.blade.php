<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('UserName') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Subject') }}</th>
                <th>{{ __('Message') }}</th>
                @if (auth()->user()->can('contact.edit'))    
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($customer_contacts as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->username }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->subject }}</td>
                    <td>{{ $row->message }}</td>
                    <td>
                        @if (auth()->user()->can('contact.edit'))
                        <a href="{{ route('admin.contact.view', $row->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-solid fa-eye"></i>
                            {{ __('View') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('contact.delete'))                            
                        <form action="{{ route('admin.contact.destroy', $row->id) }}" class="d-inline-block form-delete-{{ $row->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $row->id }}" data-href="{{ route('admin.contact.destroy', $row->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $customer_contacts->firstItem() }} {{ __('to') }} {{ $customer_contacts->lastItem() }} {{ __('of') }} {{ $customer_contacts->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $customer_contacts->links() }}</div>
            </div>
        </div>
    </div>
</div>

