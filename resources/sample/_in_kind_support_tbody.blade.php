{{-- @dump($event->in_kind_support) --}}
@if (@$event->in_kind_support)
<tbody class="in_kind_support_tbody_{{ $lang['code'] }}">
    @foreach (json_decode($translate[$lang['code']]['in_kind_support'] ?? $event->in_kind_support, true) as $key => $row)
        <tr class="in_kind_support_1">
            <td>
                <input type="text" name="in_kind_support[{{ $lang['code'] }}][title][]" class="form-control" value="{{ $row['title'] ?? null }}">
            </td>
            <td>
                <input type="text" name="in_kind_support[{{ $lang['code'] }}][subtitle][]" class="form-control" value="{{ $row['subtitle'] ?? null }}">
            </td>
            <td>
                @if ($lang['code'] == 'en')
                    <input type="file" class="d-none in_kind_support_icon_{{ $lang['code'] }}_input" name="in_kind_support[{{ $lang['code'] }}][icon][]" accept="image/*">
                @endif

                <img src="{{ json_decode($event->in_kind_support, true)[$key]['icon'] ? json_decode($event->in_kind_support, true)[$key]['icon'] : asset('uploads/image/default.png') }}" height="auto" width="60px" style="margin-bottom: 6px; cursor:pointer; border:none !important" alt="" class="avatar border in_kind_support_icon_{{ $lang['code'] }} in_kind_support_icon_0">

                <input type="hidden" name="in_kind_support[{{ $lang['code'] }}][old_icon][]" value="{{ json_decode($event->in_kind_support, true)[$key]['icon'] ?? null }}">
            </td>
            <td>
                <textarea name="in_kind_support[{{ $lang['code'] }}][description][]" id="" rows="3" class="form-control in_kind_support_summernote">{{ $row['description'] }}</textarea>
            </td>
            <td class="text-center">
                <a type="button">
                    <i class="fa fa-trash-alt text-danger delete_in_kind_support"></i>
                </a>
            </td>
        </tr>

    @endforeach

    {{-- @endif --}}
</tbody>

<script>

</script>

@push('js')
<script>
    $('.in_kind_support_icon_{{ $lang["code"] }}').click(function (e) {
        $(this).closest('td').find('.in_kind_support_icon_{{ $lang["code"] }}_input').trigger('click');
    });

    $('.in_kind_support_icon_{{ $lang["code"] }}_input').change(function (e) {
        var preview_img = $(this).closest('td').find('.in_kind_support_icon_{{ $lang["code"] }}');

        var reader = new FileReader();
        reader.onload = function(e) {
            preview_img.attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('.delete_in_kind_support').click(function (e) {
        var tbody = $('.in_kind_support_tbody_{{ $lang["code"] }}');
        var numRows = tbody.find("tr").length;
        if (numRows == 1) {
            console.log(numRows);
            toastr.error('{{ __('Cannot remove all row') }}');
            return;
        } else if (numRows >= 2) {
            $(this).closest('tr').remove();
        }
    });
</script>
@endpush
@else
<tbody class="in_kind_support_tbody_{{ $lang['code'] }}">


</tbody>

<script>

</script>

@push('js')
<script>
    $('.in_kind_support_icon').click(function (e) {
        $(this).closest('td').find('.in_kind_support_icon_input').trigger('click');
    });

    $('.in_kind_support_icon_input').change(function (e) {
        var preview_img = $(this).closest('td').find('.in_kind_support_icon');

        var reader = new FileReader();
        reader.onload = function(e) {
            preview_img.attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

    $('.delete_in_kind_support').click(function (e) {
        var tbody = $('.in_kind_support_tbody');
        var numRows = tbody.find("tr").length;
        if (numRows == 1) {
            console.log(numRows);
            toastr.error('{{ __('Cannot remove all row') }}');
            return;
        } else if (numRows >= 2) {
            $(this).closest('tr').remove();
        }
    });
</script>
@endpush
@endif
