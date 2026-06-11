@extends('layouts/contentNavbarLayout')

@section('title', 'New Purchase')
@section('content')
<div class="container">
    <h3 class="mb-3">New Purchase</h3>

    {{-- ==== ENTRY LINE ==== --}}
    <div class="card p-3 mb-3">
        <div class="row g-2 align-items-end">

            {{-- ITEM SELECT --}}
            <div class="col-md-4">
                <label>Item</label>
                <select id="itemSelect" class="form-control">
                    <option value="">Select Item</option>
                    @foreach($items as $item)
                        <option value="{{ $item['id'] }}" data-rate="{{ $item['rate'] }}">
                            {{ $item['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- QTY --}}
            <div class="col-md-2">
                <label>Qty</label>
                <input type="number" id="qtyInput" class="form-control" min="1" value="1">
            </div>

            {{-- RATE --}}
            <div class="col-md-2">
                <label>Rate</label>
                <input type="number" id="rateInput" class="form-control" readonly>
            </div>

            {{-- DISCOUNT --}}
            <div class="col-md-2">
                <label>Discount</label>
                <input type="number" id="discountInput" class="form-control" value="0">
            </div>

            {{-- ADD BUTTON --}}
            <div class="col-md-2">
                <button class="btn btn-primary w-100" id="addItemBtn">Add</button>
            </div>
        </div>
    </div>

    {{-- ==== TABLE ==== --}}
    <table class="table table-bordered" id="itemTable">
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Discount</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <h4 class="text-end">Grand Total: <span id="grandTotal">0.00</span></h4>
</div>

<script>
    // When item selected, auto-fill rate
    document.getElementById('itemSelect').addEventListener('change', function() {
        let rate = this.options[this.selectedIndex].getAttribute('data-rate') || 0;
        document.getElementById('rateInput').value = rate;
    });

    // Add item to table
    document.getElementById('addItemBtn').addEventListener('click', function() {
        let itemSelect = document.getElementById('itemSelect');
        let itemId = itemSelect.value;
        let itemName = itemSelect.options[itemSelect.selectedIndex].text;
        let qty = parseFloat(document.getElementById('qtyInput').value);
        let rate = parseFloat(document.getElementById('rateInput').value);
        let discount = parseFloat(document.getElementById('discountInput').value);

        if(!itemId){ alert('Select an item'); return; }

        let total = (qty * rate) - discount;

        let row = `
            <tr>
                <td>${itemName} <input type="hidden" name="item_id[]" value="${itemId}"></td>
                <td>${qty} <input name="qty[]" class="form-control tableQty" type="hidden" value="${qty}"></td>
                <td>${rate} <input name="rate[]" class="form-control tableRate" type="hidden" value="${rate}" readonly></td>
                <td>${discount} <input name="discount[]" class="form-control tableDiscount" type="hidden" value=""></td>
                <td>${total.toFixed(2)} <input name="total[]" class="form-control tableTotal" type="hidden" value="${total.toFixed(2)}" readonly></td>
                <td><button class="btn btn-danger btn-sm removeBtn">X</button></td>
            </tr>`;

        document.querySelector('#itemTable tbody').insertAdjacentHTML('beforeend', row);

        updateGrandTotal();

        // Reset inputs
        itemSelect.value = '';
        document.getElementById('qtyInput').value = 1;
        document.getElementById('rateInput').value = '';
        document.getElementById('discountInput').value = 0;
    });

    // Handle delete, qty, discount change
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('tableQty') || e.target.classList.contains('tableDiscount')){
            let row = e.target.closest('tr');
            recalcRow(row);
            updateGrandTotal();
        }
    });

    document.addEventListener('click', function(e){
        if(e.target.classList.contains('removeBtn')){
            e.target.closest('tr').remove();
            updateGrandTotal();
        }
    });

    function recalcRow(row){
        let qty = parseFloat(row.querySelector('.tableQty').value);
        let rate = parseFloat(row.querySelector('.tableRate').value);
        let discount = parseFloat(row.querySelector('.tableDiscount').value);
        let total = (qty * rate) - discount;
        row.querySelector('.tableTotal').value = total.toFixed(2);
    }

    function updateGrandTotal(){
        let sum = 0;
        document.querySelectorAll('.tableTotal').forEach(t => sum += parseFloat(t.value));
        document.getElementById('grandTotal').innerText = sum.toFixed(2);
    }
</script>
@endsection