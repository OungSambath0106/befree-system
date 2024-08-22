@if (@$amenity->value)
<tbody class="product_detail_tbody_{{ $lang['code'] }}">
    @foreach (isset($translate[$lang['code']]['value']) ? json_decode($translate[$lang['code']]['value'], true) : $amenity->value as $key => $row)
        <tr class="product_detail_1">
            <td>
                <input type="text" name="value[{{ $lang['code'] }}][title][]" class="form-control" value="{{ $row['title'] ?? null }}">
            </td>
            <td>
                @if ($lang['code'] == 'en')
                    <input type="file" class=" value_image_{{ $lang['code'] }}_input" name="value[{{ $lang['code'] }}][image][]" accept="image/*">
                @endif

                <img src="{{ $amenity->value[$key]['image'] ? asset('uploads/amenity/'.$amenity->value[$key]['image']) : asset('uploads/image/default.png') }}" height="auto" width="60px" style="margin-bottom: 6px; cursor:pointer; border:none !important" alt="" class="avatar border value_image_{{ $lang['code'] }} value_image_0">

                <input type="hidden" name="value[{{ $lang['code'] }}][old_image][]" value="{{ $amenity->value[$key]['image'] ?? null }}">
            </td>
            <td>
                <textarea name="value[{{ $lang['code'] }}][description][]" id="" rows="3" class="form-control value_summernote">{{ $row['description'] ?? '' }}</textarea>
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
    $('.value_image_{{ $lang["code"] }}').click(function (e) {
        $(this).closest('td').find('.value_image_{{ $lang["code"] }}_input').trigger('click');
    });

    $('.value_image_{{ $lang["code"] }}_input').change(function (e) {
        var preview_img = $(this).closest('td').find('.value_image_{{ $lang["code"] }}');

        var reader = new FileReader();
        reader.onload = function(e) {
            preview_img.attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

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
    $('.value_image').click(function (e) {
        $(this).closest('td').find('.value_image_input').trigger('click');
    });

    $('.value_image_input').change(function (e) {
        var preview_img = $(this).closest('td').find('.value_image');

        var reader = new FileReader();
        reader.onload = function(e) {
            preview_img.attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

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
