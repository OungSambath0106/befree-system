<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th class="">{{ __('Image') }}</th>
                {{-- <th>{{ __('Name') }}</th> --}}
                <th>{{ __('Page') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('slider.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($sliders as $slider)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="
                        @if ($slider->image && file_exists(public_path('uploads/sliders/' . $slider->image)))
                            {{ asset('uploads/sliders/'. $slider->image) }}
                        @else
                            {{ asset('uploads/image/default.png') }}
                        @endif
                        " alt="" class="profile_img_table">

                    </td>
                    <td class="text-capitalize">
                        @if ($slider->type == 'company_info')
                            {{ __('Home Chaufea') }}
                        @elseif ($slider->type == 'the_property')
                            {{ __('The Property') }}
                        @elseif ($slider->type == 'booking_history')
                            {{ __('Booking History') }}
                        @else
                            {{ $slider->type }}
                        @endif
                        {{-- {{ $slider->type }} --}}
                    </td>
                    <td>{{ $slider->createdBy->name }}</td>
                    @if (auth()->user()->can('slider.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $slider->id }}" data-id="{{ $slider->id }}" {{ $slider->status == 'active' ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $slider->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('slider.edit'))
                        <a href="{{ route('admin.slider.edit', $slider->id) }}" class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif
                        @if (auth()->user()->can('slider.delete'))
                        <form action="{{ route('admin.slider.destroy', $slider->id) }}" class="d-inline-block form-delete-{{ $slider->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $slider->id }}" data-href="{{ route('admin.slider.destroy', $slider->id) }}" class="btn btn-danger btn-sm btn-delete">
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
                    {{ __('Showing') }} {{ $sliders->firstItem() }} {{ __('to') }} {{ $sliders->lastItem() }} {{ __('of') }} {{ $sliders->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $sliders->links() }}</div>
            </div>
        </div>
    </div>


</div>

