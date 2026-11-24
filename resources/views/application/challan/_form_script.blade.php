<script>
    $(document).ready(function () {
        let rowIndex = $('#challanItemsBody tr.challan-row').length;

        function initRowSelect2($row) {
            const $select = $row.find('.item-select');
            if ($select.length && !$select.hasClass('select2-hidden-accessible')) {
                $select.select2({ width: '100%' });
            }
        }

        function renumberRows() {
            let totalQty = 0;
            const $rows = $('#challanItemsBody tr.challan-row');
            $rows.each(function (i) {
                $(this).find('.row-sl').text(i + 1);
                const qty = parseFloat($(this).find('.item-qty').val()) || 0;
                totalQty += qty;
            });
            $('#totalItemsCount').text($rows.length);
            $('#totalQtySum').text(totalQty.toFixed(2));
        }

        function addRow(prefill = null) {
            const templateHtml = $('#challanRowTemplate').html().replace(/__IDX__/g, rowIndex++);
            const $row = $(templateHtml);
            $('#challanItemsBody').append($row);
            initRowSelect2($row);

            if (prefill) {
                if (prefill.item_id) {
                    $row.find('.item-select').val(String(prefill.item_id)).trigger('change.select2');
                }
                $row.find('.item-name').val(prefill.item_name || '');
                $row.find('.item-unit').val(prefill.unit_name || '');
                $row.find('.item-qty').val(prefill.quantity || '');
                $row.find('input[name$="[note]"]').val(prefill.note || '');
            }

            renumberRows();
        }

        $('#challanItemsBody tr.challan-row').each(function () {
            initRowSelect2($(this));
        });
        renumberRows();

        $('#addRowBtn').on('click', function () {
            addRow();
        });

        $(document).on('click', '.remove-row-btn', function () {
            const $rows = $('#challanItemsBody tr.challan-row');
            if ($rows.length <= 1) {
                toastr.warning('At least one delivery item is required.');
                return;
            }
            $(this).closest('tr.challan-row').remove();
            renumberRows();
        });

        $(document).on('change', '.item-select', function () {
            const $row = $(this).closest('tr.challan-row');
            const $opt = $(this).find('option:selected');
            const name = $opt.data('name') || '';
            const unit = $opt.data('unit') || '';

            if ($(this).val()) {
                $row.find('.item-name').val(name);
                $row.find('.item-unit').val(unit);
            }
        });

        $(document).on('input', '.item-qty', function () {
            renumberRows();
        });

        $('#ledger_id').on('change', function () {
            const $opt = $(this).find('option:selected');
            const address = $opt.data('address') || '';
            const mobile = $opt.data('mobile') || '';

            if (!$('#delivery_address').val()) {
                $('#delivery_address').val(address);
            }
            if (!$('#contact_phone').val()) {
                $('#contact_phone').val(mobile);
            }
        });

        $('#sales_id').on('change', function () {
            const salesId = $(this).val();
            if (!salesId) {
                return;
            }

            $.getJSON("{{ url('/challan/sales-info') }}/" + salesId, function (res) {
                if (!res.status || !res.data) {
                    return;
                }
                const data = res.data;

                if (data.ledger_id) {
                    $('#ledger_id').val(String(data.ledger_id)).trigger('change');
                }
                $('#delivery_address').val(data.delivery_address || '');
                $('#contact_phone').val(data.contact_phone || '');
                $('#reference_no').val(data.voucher_no || '');
                $('#deduct_stock').prop('checked', false);

                if (Array.isArray(data.items) && data.items.length > 0) {
                    $('#challanItemsBody').empty();
                    data.items.forEach(function (item) {
                        addRow(item);
                    });
                }
                toastr.info('Loaded items from Invoice ' + data.voucher_no);
            }).fail(function () {
                toastr.error('Could not load details for the selected invoice.');
            });
        });
    });
</script>
