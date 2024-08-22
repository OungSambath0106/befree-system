<tr class="room_checkout_{{ $key }}">
    <td>
        <input type="text" name="checkout[{{ $lang }}][title][]" class="form-control">
    </td>
    <td class="text-center">
        <a type="button">
            <i class="fa fa-trash-alt text-danger delete_room_checkout"></i>
        </a>
    </td>

    <script>
        $('.delete_room_checkout').click(function (e) {
            var tbody = $('.room_checkout_tbody_{{ $lang }}');
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