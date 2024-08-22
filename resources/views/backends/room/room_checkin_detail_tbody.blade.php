@if (@$room->checkin)
<tbody class="product_detail_tbody_{{ $lang['code'] }}">
    @foreach (json_decode($translate[$lang['code']]['checkin'] ?? $room->checkin, true) as $key => $row)
        <tr class="product_detail_1">
            <td>
                <input type="text" name="checkin[{{ $lang['code'] }}][title][]" class="form-control" value="{{ $row['title'] ?? null }}">
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
        var tbody = $('.product_detail_tbody_{{ $lang["code"] }}');
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
<tbody class="product_detail_tbody_{{ $lang['code'] }}">


</tbody>

<script>

</script>

@push('js')
<script>
    $('.delete_product_detail').click(function (e) {
        var tbody = $('.product_detail_tbody');
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