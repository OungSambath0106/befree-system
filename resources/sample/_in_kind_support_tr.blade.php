<tr class="in_kind_support_{{ $key }}">
    <td>
        <input type="text" name="in_kind_support[{{ $lang }}][title][]" class="form-control">
    </td>
    <td>
        <input type="text" name="in_kind_support[{{ $lang }}][subtitle][]" class="form-control">
    </td>
    <td>
        @if ($lang == 'en')
            <input type="file" class="d-none in_kind_support_icon_{{ $lang }}_input_{{ $key }}" name="in_kind_support[{{ $lang }}][icon][]" accept="image/*">
        @endif

        <img src="{{ asset('uploads/image/default.png') }}" height="auto" width="60px" style="margin-bottom: 6px; cursor:pointer; border:none !important" alt="" class="avatar border in_kind_support_icon in_kind_support_icon_{{ $lang }}_{{ $key }}">

        <input type="hidden" name="in_kind_support[{{ $lang }}][old_icon][]" value="">
    </td>
    <td>
        <textarea name="in_kind_support[{{ $lang }}][description][]" id="" rows="3" class="form-control in_kind_support_summernote"></textarea>
    </td>
    <td class="text-center">
        <a type="button">
            <i class="fa fa-trash-alt text-danger delete_in_kind_support"></i>
        </a>
    </td>

    <script>
        $('.in_kind_support_summernote').summernote({
            placeholder: '{{ __("Type something") }}',
            tabsize: 2,
            height: 100,
            width: 500
        });

        $('.in_kind_support_icon_{{ $lang }}_{{ $key }}').click(function (e) {
            console.log('in_kind_support_icon_{{ $lang }}_{{ $key }}');
            $('.in_kind_support_icon_{{ $lang }}_input_{{ $key }}').trigger('click');
        });

        $('.in_kind_support_icon_{{ $lang }}_input_{{ $key }}').change(function (e) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.in_kind_support_icon_{{ $lang }}_{{ $key }}').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('.delete_in_kind_support').click(function (e) {
            var tbody = $('.in_kind_support_tbody_{{ $lang }}');
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
</tr>
