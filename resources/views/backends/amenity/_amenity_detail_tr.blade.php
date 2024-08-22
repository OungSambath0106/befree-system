<tr class="value_{{ $key }}">
    <td>
        <input type="text" name="value[{{ $lang }}][title][]" class="form-control">
    </td>
    <td>
        @if ($lang == 'en')
        <input type="file" class="d-none value_image_{{ $lang }}_input_{{ $key }}" name="value[{{ $lang }}][image][]" accept="image/*">
        @endif
        
        <img src="{{ asset('uploads/image/default.png') }}" height="auto" width="60px" style="margin-bottom: 6px; cursor:pointer; border:none !important" alt="" class="avatar border value_image value_image_{{ $lang }}_{{ $key }}">
        
        <input type="hidden" name="value[{{ $lang }}][old_image][]" value="">
    </td>
    <td>
        <textarea name="value[{{ $lang }}][description][]" id="" rows="3" class="form-control value_summernote"></textarea>
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

        $('.value_image_{{ $lang }}_{{ $key }}').click(function (e) {
            console.log('product_detail_image_{{ $lang }}_{{ $key }}');
            $('.value_image_{{ $lang }}_input_{{ $key }}').trigger('click');
        });

        $('.value_image_{{ $lang }}_input_{{ $key }}').change(function (e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.value_image_{{ $lang }}_{{ $key }}').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.delete_product_detail').click(function (e) {
            var tbody = $('.product_detail_tbody_{{ $lang }}');
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