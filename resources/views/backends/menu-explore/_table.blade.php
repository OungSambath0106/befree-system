<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th >#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Menu URL') }}</th>
                <th>{{ __('Created By') }}</th>
                @if (auth()->user()->can('menu.explore.edit'))
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($menu_explores as $menu_explore)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $menu_explore->name }}</td>
                    <td>{{ $menu_explore->menu_url }}</td>
                    <td>{{ @$menu_explore->createdBy->name }}</td>
                    @if (auth()->user()->can('menu.explore.edit'))
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input switcher_input status" id="status_{{ $menu_explore->id }}" data-id="{{ $menu_explore->id }}" {{ $menu_explore->status == "active" ? 'checked' : '' }} name="status">
                            <label class="custom-control-label" for="status_{{ $menu_explore->id }}"></label>
                        </div>
                    </td>
                    @endif
                    <td>
                        @if (auth()->user()->can('menu.explore.edit'))                            
                        <a href="#" data-href="{{ route('admin.explore_menu.edit', $menu_explore->id) }}" class="btn btn-info btn-sm btn-modal btn-edit" data-toggle="modal" data-container=".modal_form">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                        @endif

                        {{-- @if (auth()->user()->can('menu.explore.delete'))                            
                        <form action="{{ route('admin.explore_menu.destroy', $menu_explore->id) }}" class="d-inline-block form-delete-{{ $menu_explore->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-id="{{ $menu_explore->id }}" data-href="{{ route('admin.explore_menu.destroy', $menu_explore->id) }}" class="btn btn-danger btn-sm btn-delete">
                                <i class="fa fa-trash-alt"></i>
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endif --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $menu_explores->firstItem() }} {{ __('to') }} {{ $menu_explores->lastItem() }} {{ __('of') }} {{ $menu_explores->total() }} {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $menu_explores->links() }}</div>
            </div>
        </div>
    </div>


</div>

