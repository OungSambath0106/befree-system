<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Title') }}</th>
                <th>{{ __('Room') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('rate.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($ratePlans as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->title }}</td>
                    <td>{{ @$row->room->title }}</td>
                    <td>{{ Str::limit($row->description,50) }}</td>
                    <td>{{ $row->type }}</td>
                    <td>{{ $row->price }}</td>
                    <td>{{ @$row->createdBy->name }}</td>
                    @if (auth()->user()->can('rate.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $row->id }}" data-id="{{ $row->id }}" {{ $row->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $row->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        <a href="{{ route('admin.room.room_rate_calendar.index', ['rate_plan_id' => $row->id]) }}" class="btn btn-warning mr-2 text-white btn-edit">
                            <i class="fas fa fa-cog" aria-hidden="true"></i>
                            {{ __('Manage Calendar') }}
                        </a>

                        @if (auth()->user()->can('rate.edit'))
                        <a href="{{ route('admin.rate_plan.edit', ['rate_plan' => $row->id, 'room_id' => $row->room_id]) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('rate.delete'))
                        <form action="{{ route('admin.rate_plan.destroy', $row->id) }}" class="d-inline-block form-delete-{{ $row->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $row->id }}" data-href="{{ route('admin.rate_plan.destroy', $row->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $ratePlans->firstItem() }} {{ __('to') }} {{ $ratePlans->lastItem() }} {{ __('of') }} {{ $ratePlans->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $ratePlans->links() }}</div>
            </div>
        </div>
    </div>


</div>

