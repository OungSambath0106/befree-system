@if (@$extra_service->description)
<tbody class="extra_detail_tbody_{{ $lang['code'] }}">

    @foreach (isset($translate[$lang['code']]['description']) ? json_decode($translate[$lang['code']]['description'], true) : $extra_service->description as $key => $row)
        <tr class="product_detail_1">
            <td>
                <select name="description[{{ $lang['code'] }}][option][]" class="form-control" id="">
                    <option value="yes" {{ ($row['option'] == 'yes') ? 'selected' : '' }}>{{ __('Yes') }}</option>
                    <option value="no" {{ ($row['option'] == 'no') ? 'selected' : '' }}>{{ __('No') }}</option>
                </select>
            </td>
            <td>
                <input type="text" name="description[{{ $lang['code'] }}][title][]" class="form-control" value="{{ $row['title'] ?? null }}">
            </td>
            <td class="text-center">
                <a type="button">
                    <i class="fa fa-trash-alt text-danger delete_product_detail"></i>
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
    $('.delete_product_detail').click(function (e) {
        var tbody = $('.extra_detail_tbody_{{ $lang["code"] }}');
        var numRows = tbody.find("tr").length;
        if (numRows == 1) {
            console.log(numRows);
            toastr.error('{{ ('Cannot remove all row') }}');
            return;
        } else if (numRows >= 2) {
            $(this).closest('tr').remove();
        }
    });
</script>
@endpush
@else
<tbody class="extra_detail_tbody_{{ $lang['code'] }}">


</tbody>

<script>

</script>

@push('js')
<script>
    $('.delete_product_detail').click(function (e) {
        var tbody = $('.extra_detail_tbody_{{ $lang["code"] }}');
        var numRows = tbody.find("tr").length;
        if (numRows == 1) {
            console.log(numRows);
            toastr.error('{{ ('Cannot remove all row') }}');
            return;
        } else if (numRows >= 2) {
            $(this).closest('tr').remove();
        }
    });
</script>
@endpush
@endif
