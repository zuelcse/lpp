@extends('layouts/contentNavbarLayout')

@section('title', 'New Sales Invoice')

@section('content')
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js" type="text/javascript"></script>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Sales Invoice') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('sales.voucher.create')}}">
                        @csrf
                        <input type="hidden" name="voucherType" value="{{$voucherType}}"/>
                        <div class="mb-1 p-1 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-3">
                                    <div class="row">
                                        <label class="col-sm-6 col-form-label">Voucher No.</label>
                                        <div class="col-sm-6">
                                            <span class="form-control form-control-sm bg-secondary-subtle">{{ $voucher_no }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-3">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="date">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" autofocus class="form-control form-control-sm " name="date" id="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="row">
                                        <label class="col-8 col-sm-3 col-form-label"
                                            for="ledger">Ledger Name</label>
                                        <div class="col-12 col-sm-9">
                                            <select type="text" class="form-control form-control-sm js-example-basic-single" name="ledger" id="ledger"
                                                placeholder="Enter Ledger Type" required="required">
                                                <option value="">Select Ledger Type</option>
                                                @foreach ($ledgers as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->alias.'-'.$item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <hr class="border-primary mt-0 mb-2"> -->

                            <div class="mt-2">
                                <table class="table table-bordered border border-secondary" id="itemTable">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="20%">Item Name</th>
                                            <th width="15%">Qty</th>
                                            <th width="15%">Rate</th>
                                            <th width="15%">Amount</th>
                                            <th width="15%">Disc(%)</th>
                                            <th width="15%">DiscAmt</th>
                                            <th width="15%">Total</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                        <tr>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="itemSelect" >
                                                    <option value="">Select Item</option>
                                                    @forelse($items as $key => $item)
                                                        <option value="{{ $item['id'] }}" data-rate="{{ $item['rate'] }}">{{ $item['barcode'].'-'.$item['name'] }}</option>
                                                    @empty
                                                    <option value="">No Items Found</option>
                                                    @endforelse
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="qtyInput" type="text" class="form-control form-control-sm"
                                                    placeholder="Enter Quantity" autocomplete="off" onkeyup="rateCal('qty')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="rateInput" type="text" class="form-control form-control-sm"  placeholder="Enter Rate" autocomplete="off" onkeyup="rateCal('rate')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="amount" type="text" class="form-control form-control-sm amount" placeholder="(Qty x Rate)" autocomplete="off" onkeyup="rateCal('amount')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="discount" type="text" class="form-control form-control-sm discount" placeholder="Disc(%)" autocomplete="off" onkeyup="rateCal('discount')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="damount" type="text" class="form-control form-control-sm damount" placeholder="DiscAmt" autocomplete="off" onkeyup="rateCal('damount')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="amountwithtax" type="text" class="form-control form-control-sm amountwithtax" placeholder="Total" autocomplete="off" onkeyup="rateCal('amountwithtax')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <button title="Add More!" onclick="addItemBtn(0)" type="button" class="btn btn-sm btn-secondary float-start">+</button>
                                                <button title="Add & Next!" onclick="addItemBtn(1)" type="button" class="btn btn-sm btn-secondary float-end">></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <!-- <hr class="border-primary mt-2 mb-2"> -->
                            <div class="mt-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="grand_total">Grand Total TK</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" class="form-control form-control-sm text-end" id="grand_total" name="grand_total" readonly placeholder="Extra Discount TK" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="extra_discount">Extra Discount TK</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" onkeyup="getBalanceAmount()" class="form-control form-control-sm text-end" id="extra_discount" name="extra_discount" placeholder="Extra Discount TK" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="payable_amount">Payable Amount TK</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" class="form-control form-control-sm text-end" id="payable_amount" name="payable_amount" placeholder="Payable Amount" readonly autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="paid_amount">Paid Amount TK</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" onkeyup="getBalanceAmount()" class="form-control form-control-sm text-end" id="paid_amount" name="paid_amount" placeholder="Enter Paid Amount" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="balance">Balance/Refund TK</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" class="form-control form-control-sm text-end" id="balance" name="balance" placeholder="Balance" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="row">
                                            <label class="col-8 col-sm-4 col-form-label" for="narration_remarks">Narration/Remarks</label>
                                            <div class="col-12 col-sm-8">
                                                <textarea name="narration_remarks" id="narration_remarks" rows="1" class="form-control form-control-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary active d-table m-0 m-auto" onclick="return confirm('Are you want to proceed?')">Create</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                



    <script>
        $(document).ready(function () {
            // Initialize select2 on class, not ID
            $('.js-example-basic-single').select2();

            // Bind event reliably
            $(document).on('select2:select', '#itemSelect', function(e) {
                let rate = e.params.data.element.dataset.rate || 0;
                console.log("Selected rate:", rate);
                $('#rateInput').val(rate);
            });

            // rateCal('qty');
        });

    // When item selected, auto-fill rate
    // document.getElementById('itemSelect').addEventListener('change', function() {
    //     let rate = this.options[this.selectedIndex].getAttribute('data-rate') || 0;
    //     document.getElementById('rateInput').value = rate;
    // });




    // Add item to table
    // document.getElementById('addItemBtn').addEventListener('click', function() {
    let m = 1;
    function addItemBtn(i) {
        // alert(i);
        let itemSelect = document.getElementById('itemSelect');
        let itemId = itemSelect.value;
        let itemName = itemSelect.options[itemSelect.selectedIndex].text;
        let qty = parseFloat(document.getElementById('qtyInput').value);
        let rate = parseFloat(document.getElementById('rateInput').value);
        let discount = parseFloat(document.getElementById('discount').value);
        let damount = parseFloat(document.getElementById('damount').value);
        // let discount = parseFloat(document.getElementById('discountInput').value);

        if(!itemId){
            // $('#paid_amount').focus();
            alert('Select an item');
            return;
        }

        let amount = (qty * rate);
        /*if(discount>0 && amount>0 ){
            damount = parseFloat((discount/100) * amount).toFixed(2);
        }*/

        var amountwithtax = amount - damount;



        // let total = (qty * rate);

        let row = `
            <tr>
                <td>${itemName} <input type="hidden" name="item[${m}][name]" value="${itemId}"></td>
                <td>${qty} <input name="item[${m}][quantity]" class="form-control tableQty" type="hidden" value="${qty}"></td>
                <td>${rate} <input name="item[${m}][rate]" class="form-control tableRate" type="hidden" value="${rate}" readonly></td>
                <td>${amount} <input name="item[${m}][amount]" class="form-control tableamount" type="hidden" value="${amount}" readonly></td>
                <td>${discount} <input name="item[${m}][discount]" class="form-control tablediscount" type="hidden" value="${discount}" readonly></td>
                <td>${damount} <input name="item[${m}][damount]" class="form-control tabledamount" type="hidden" value="${damount}" readonly></td>
                <td>${amountwithtax.toFixed(2)} <input name="item[${m}][amountwithtax]" class="form-control tableamountwithtax" type="hidden" value="${amountwithtax.toFixed(2)}" readonly></td>
                <td><button class="btn btn-danger btn-sm removeBtn">X</button></td>
            </tr>`;

        document.querySelector('#itemTable tbody').insertAdjacentHTML('beforeend', row);

        updateGrandTotal();

        // Reset inputs
        // itemSelect.value = null;
        let qtyint='';
        $('#itemSelect').val(null).trigger('change');
        if(i==1){
            // $('#paid_amount').focus();
            $('#extra_discount').focus();
        } else{
            $('#itemSelect').select2('open');
            qtyint=1;
        }

        $(`#qtyInput`).val(0);
        $(`#rateInput`).val(0);
        $(`#amount`).val(0);
        $(`#discount`).val(0);
        $(`#damount`).val(0);
        $(`#amountwithtax`).val(0);

        // document.getElementById('qtyInput').value = qtyint;
        // document.getElementById('rateInput').value = '';
        m++;
        // document.getElementById('discountInput').value = 0;
    }

    // Handle delete, qty, discount change
    /*document.addEventListener('input', function(e){
        // if(e.target.classList.contains('tableQty') || e.target.classList.contains('tableDiscount')){
        if(e.target.classList.contains('qtyInput')){
        alert(e);
            let row = e.target.closest('tr');
            recalcRow(row);
            updateGrandTotal();
        }
    });*/

    document.addEventListener('click', function(e){
        if(e.target.classList.contains('removeBtn')){
            e.target.closest('tr').remove();
            updateGrandTotal();
        }
    });

    function recalcRow(row){
        // alert(row);
        let qty = parseFloat(row.querySelector('.tableQty').value);
        let rate = parseFloat(row.querySelector('.tableRate').value);
        let discount = parseFloat(row.querySelector('.tableDiscount').value);
        let total = (qty * rate) - discount;
        row.querySelector('.tableTotal').value = total.toFixed(2);
    }

    function rateCal(itype){
        let qty = $(`#qtyInput`).val();
        let rate = $(`#rateInput`).val();
        let discount = $(`#discount`).val();
        let damount = $(`#damount`).val();
        let amount = $(`#amount`).val();

        if((itype === 'qty') || (itype === 'rate')){
            amount = parseFloat(qty * rate);
            $(`#amount`).val(Number(amount.toFixed(2)));
            discount=0;
            damount=0;
            $(`#discount`).val(0);
            $(`#damount`).val(0);
        }else if(itype == 'amount'){
            rate = parseFloat(amount/qty);
            $(`#rateInput`).val(Number(rate.toFixed(2)));
            $(`#discount`).val(0);
        }else if(itype == 'discount'){
            damount = parseFloat((discount/100) * amount).toFixed(2);
            // alert('H'+damount);
            $(`#damount`).val(damount);
        }
        else if(itype == 'damount'){
            // damount = parseFloat((discount/100) * amount).toFixed(2);
            // $(`#discount`).val(0);
        }


        let amountwithtax = parseFloat(amount-damount);
        $(`#amountwithtax`).val(Number(amountwithtax.toFixed(2)));
    }

    function getBalanceAmount(){
        let grand_total = $(`#grand_total`).val();
        let payable_amount = $(`#payable_amount`).val();
        let paid_amount = $(`#paid_amount`).val();
        let extra_discount = $(`#extra_discount`).val();
        payable_amount = grand_total - extra_discount; 

        let rest_amount = parseFloat(paid_amount - payable_amount);
        $(`#payable_amount`).val(Number(payable_amount.toFixed(2)));
        $(`#balance`).val(Number(rest_amount.toFixed(2)));
    }

    function updateGrandTotal(){
        let sum = 0;
        document.querySelectorAll('.tableamountwithtax').forEach(t => sum += parseFloat(t.value));
        document.getElementById('payable_amount').value = sum.toFixed(2);
        document.getElementById('grand_total').value = sum.toFixed(2);
    }
</script>

@endsection