<div class="card-body p-0 table-wrapper">
    <table class="table">
        <thead class="text-uppercase">
            <tr>
                <th>#</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Default Title') }}</th>
                <th>{{ __('Page') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sectionTitles as $sectionTitle)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $sectionTitle->title }}
                    </td>
                    <td>{{ $sectionTitle->default_title }}</td>
                    <td>{{ @$sectionTitle->page->title }}</td>
                    <td>
                        <a href="{{ route('admin.section_title.edit', $sectionTitle->id) }}"
                            class="btn btn-info btn-sm btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 d-flex flex-row flex-wrap">
            <div class="row" style="width: -webkit-fill-available;">
                <div class="col-12 col-sm-6 text-center text-sm-left pl-3" style="margin-block: 20px">
                    {{ __('Showing') }} {{ $sectionTitles->firstItem() }} {{ __('to') }}
                    {{ $sectionTitles->lastItem() }} {{ __('of') }} {{ $sectionTitles->total() }}
                    {{ __('entries') }}
                </div>
                <div class="col-12 col-sm-6 pagination-nav pr-3"> {{ $sectionTitles->links() }}</div>
            </div>
        </div>
    </div>

</div>
