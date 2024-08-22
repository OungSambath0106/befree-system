@if (@$service->extra_info)
<tbody class="extra_detail_tbody_{{ $lang['code'] }}">
    {{-- @dd($translate[$lang['code']]['extra_info']) --}}
    @foreach (isset($translate[$lang['code']]['extra_info']) ? json_decode($translate[$lang['code']]['extra_info'], true) : $service['extra_info'] as $extra_info_key => $row)
        <tr class="product_detail_1">
            <td>
                <input type="text" name="extra_info[{{ $lang['code'] }}][title][]" class="form-control" value="{{ $row['title'] ?? null }}">
            </td>
            <td>
                <textarea name="extra_info[{{ $lang['code'] }}][description][]" id="" rows="3" class="form-control value_summernote">{{ $row['description'] }}</textarea>
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
    $('.value_summernote').summernote({
            placeholder: '{{ ("Type something") }}',
            tabsize: 2,
            height: 100,
            width: 500
    });
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