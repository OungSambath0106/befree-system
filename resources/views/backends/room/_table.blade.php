<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                {{-- <th class="">{{ __('Thumbnail') }}</th> --}}
                <th>{{ __('Title') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('room.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($rooms as $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    {{-- <td>
                        <img src="
                        @if ($room->thumbnail && file_exists(public_path('uploads/room/' . $room->thumbnail)))
                            {{ asset('uploads/room/'. $room->thumbnail) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">
                    </td> --}}
                    <td>{{ $room->title }}</td>
                    <td>{{ $room->createdBy->name }}</td>
                    @if (auth()->user()->can('room.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $room->id }}" data-id="{{ $room->id }}" {{ $room->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $room->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                    @if (auth()->user()->can('allotment.view'))
                    <a href="{{ route('admin.room.allotment_calendar.index',['room_id' => $room->id]) }}" class="btn btn-warning mr-2 text-white btn-edit">
                        <i class="fas fa fa-cog" aria-hidden="true"></i>
                        {{ __('Room allotment') }}
                    </a>
                    @endif
                    @if (auth()->user()->can('rate.view'))
                        <a href="{{ route('admin.rate_plan.index',['room_id' => $room->id]) }}" class="btn btn-warning mr-2 text-white btn-edit">
                            <i class="fas fa fa-cog" aria-hidden="true"></i>
                            {{ __('Manage Rate Plan') }}
                        </a>
                    @endif
                    @if (auth()->user()->can('room.edit'))
                    <a href="{{ route('admin.room.edit', $room->id) }}" class="btn btn-info btn-sm btn-edit">
                        <i class="fas fa-pencil-alt"></i>
                        {{ __('Edit') }}
                    </a>
                    @endif
                    @if (auth()->user()->can('room.delete'))
                        <form action="{{ route('admin.room.destroy', $room->id) }}" class="d-inline-block form-delete-{{ $room->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $room->id }}" data-href="{{ route('admin.room.destroy', $room->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $rooms->firstItem() }} {{ __('to') }} {{ $rooms->lastItem() }} {{ __('of') }} {{ $rooms->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $rooms->links() }}</div>
            </div>
        </div>
    </div>


</div>

