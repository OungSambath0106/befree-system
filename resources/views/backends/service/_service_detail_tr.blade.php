<tr class="extra_info_{{ $key }}">
    <td>
        <input type="text" name="extra_info[{{ $lang }}][title][]" class="form-control">
    </td>
    <td>
        <textarea name="extra_info[{{ $lang }}][description][]" id="" rows="3" class="form-control value_summernote"></textarea>
    </td>
    <td class="text-center">
        <a type="button">
            <i class="fa fa-trash-alt text-danger delete_product_detail"></i>
        </a>
    </td>

    <script>
        $('.value_summernote').summernote({
            placeholder: '{{ ("Type something") }}',
            tabsize: 2,
            height: 100,
            width: 500
        });
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