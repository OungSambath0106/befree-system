<tbody class="payment_tbody">
    @if ($payments)
        @foreach (json_decode($payments, true) as $key => $row)
            <tr>
                <td>
                    <input type="text" class="form-control" name="payment[title][]" value="{{ $row['title'] ?? null }}">
                </td>
                <td>
                    <input type="file" class="d-none payment_icon_input_{{ $key }}" name="payment[icon][]">
                    <img src="{{ $row['icon'] ? asset('uploads/social_media/'. $row['icon']) : asset('uploads/image/default.png') }}" height="auto" width="60px" style="margin-bottom: 6px; cursor:pointer; border:none !important" alt="" class="avatar border payment_icon payment_icon_{{ $key }}">

                    <input type="hidden" name="payment[old_icon][]" value="{{ $row['icon'] ?? null }}">
                </td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input switcher_input status" id="{{ $row['title'] }}" data-id="{{ $row['title'] }}" {{ $row['status'] == 1 ? 'checked' : '' }} name="payment[status_{{ $key }}]">
                        <label class="custom-control-label" for="{{ $row['title'] }}"></label>
                    </div>
                </td>
                <td>
                    <a type="button">
                        <i class="fa fa-trash-alt text-danger delete_payment"></i>
                    </a>
                </td>
            </tr>
            @push('js')
                <script>
                    $(function () {
                        $('.payment_icon_{{ $key }}').click(function (e) {
                            console.log('payment_icon_{{ $key }}');
                            $('.payment_icon_input_{{ $key }}').trigger('click');
                        });

                        $('.payment_icon_input_{{ $key }}').change(function (e) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                $('.payment_icon_{{ $key }}').attr('src', e.target.result).show();
                            }
                            reader.readAsDataURL(this.files[0]);
                        });

                        $('.delete_payment').click(function (e) {
                            var tbody = $('.tbody');
                            var numRows = tbody.find("tr").length;
                            if (numRows == 1) {
                                toastr.error('{{ __('Cannot remove all row') }}');
                                return;
                            } else if (numRows >= 2) {

                                $(this).closest('tr').remove();
                            }
                        });
                    });
                </script>
            @endpush
        @endforeach
    @endif

</tbody>
