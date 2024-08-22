<tr class="description_{{ $key }}">
    <td>
        <select name="description[{{ $lang }}][option][]" class="form-control" id="">
            <option value="">{{ __('Select option') }}</option>
            <option value="yes">{{ __('Yes') }}</option>
            <option value="no">{{ __('No') }}</option>
        </select>
    </td>
    <td>
        <input type="text" name="description[{{ $lang }}][title][]" class="form-control">
    </td>
    <td class="text-center">
        <a type="button">
            <i class="fa fa-trash-alt text-danger delete_product_detail"></i>
        </a>
    </td>
    <script>
        $('.delete_product_detail').click(function (e) {
            var tbody = $('.extra_detail_tbody_{{ $lang }}');
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
</tr>