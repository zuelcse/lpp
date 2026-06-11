@extends('layouts/contentNavbarLayout')

@section('title', 'New Invoice (Printing Press)')

@section('content')
<script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js" type="text/javascript"></script>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Invoice (Printing Press)') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('sales-invoice-create')}}">
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
                                            <select type="text" class="form-control form-control-sm js-example-basic-single" name="ledger" id="ledger" placeholder="Enter Ledger Type" required="required" onchange="fetchLedgerDetails()">
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
                                            <th rowspan="2" width="15%">Qty</th>
                                            <th colspan="2" width="20%">Work</th>
                                            <th rowspan="2" width="15%">Size</th>
                                            <th rowspan="2" width="15%">Color</th>
                                            <th rowspan="2" width="15%">Weight</th>
                                            <th rowspan="2" width="15%">Paper</th>
                                            <th rowspan="2" width="15%">Lamination</th>
                                            <th rowspan="2" width="15%">Note</th>
                                            <th rowspan="2" width="15%">Rate</th>
                                            <th rowspan="2" width="15%">Amount</th>
                                            <th rowspan="2" width="12%">Action</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th width="20%">Name</th>
                                            <th width="20%">Type</th>
                                        </tr>
                                        <tr>
                                            <th class="m-0 p-1">
                                                <input id="qtyInput" type="text" class="form-control form-control-sm"
                                                    placeholder="Enter Quantity" autocomplete="off" onkeyup="rateCal('qty')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="work_name" >
                                                    <option value="">-Work Name-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="work_type" onchange ="fetchWorkTypeVeriations()">
                                                    <option value="">-Work Type-</option>
                                                    @forelse($WorkTypes as $key => $item)
                                                        <option value="{{ $item['id'] }}" >{{ $item['name'] }}</option>
                                                    @empty
                                                    <option value="">No Items Found</option>
                                                    @endforelse
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="size" >
                                                    <option value="">-Size-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="color" >
                                                    <option value="">-Color-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="weight" >
                                                    <option value="">-Weight-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="paper" >
                                                    <option value="">-Paper-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <select class="form-control form-control-sm items js-example-basic-single" id="lamination" >
                                                    <option value="">-Lamination-</option>
                                                </select>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="note" type="text" class="form-control form-control-sm"  placeholder="Note" autocomplete="off"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="rateInput" type="text" class="form-control form-control-sm"  placeholder="Enter Rate" autocomplete="off" onkeyup="rateCal('rate')"/>
                                            </th>
                                            <th class="m-0 p-1">
                                                <input id="amount" type="text" class="form-control form-control-sm amount" placeholder="(Qty x Rate)" autocomplete="off" onkeyup="rateCal('amount')"/>
                                            </th>
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
                                            <label class="col-6 col-sm-10 col-form-label text-end" for="previous_balance">Previous Balance</label>
                                            <div class="col-6 col-sm-2">
                                                <input type="text" class="form-control form-control-sm text-end" id="previous_balance" name="previous_balance" placeholder="Enter Paid Amount" autocomplete="off"/>
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
                                <button type="submit" class="btn btn-primary active d-table m-0 m-auto">Create</button>
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
                // $('#rateInput').val(rate);
            });

            // rateCal('qty');
        });


        async function fetchLedgerDetails() {
            $('#work_name')
            .empty()
            .append('<option value="">-Work Name-</option>');

            $(`#previous_balance`).val('');

            var ledger = $(`#ledger`).val();
            var url = "{{url('/ledger/work-names/')}}/"+ledger;

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Data from api : ',data);

                $.each(data.work_names, function(id, name){
                    $('#work_name').append(
                        `<option value="${id}">${name}</option>`
                    );
                });
                $(`#previous_balance`).val(data?.closing_balance);


                var amount = parseInt(data?.openingRate) * parseInt(data?.baseUnits);
            })
            .catch(error => console.error('Error:', error));
        }

        async function fetchWorkTypeVeriations() {
            $('#size').empty().append('<option value="">-Size-</option>');
            $('#color').empty().append('<option value="">-Color-</option>');
            $('#weight').empty().append('<option value="">-Weight-</option>');
            $('#paper').empty().append('<option value="">-Paper-</option>');
            $('#lamination').empty().append('<option value="">-Lamination-</option>');

            // var work_type = $(`#work_type`).val();
            var url = "{{url('/worktypeveriations/')}}/"+$(`#work_type`).val();

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Data from api : ',data);

                let item = data[0];

                $.each(item.sizes, function(index, size){
                    $('#size').append(`<option value="${size.id}">${size.name}</option>`);
                });
                $.each(item.colors, function(index, color){
                    $('#color').append(`<option value="${color.id}">${color.name}</option>`);
                });
                $.each(item.weights, function(index, weight){
                    $('#weight').append(`<option value="${weight.id}">${weight.name}</option>`);
                });
                $.each(item.papers, function(index, paper){
                    $('#paper').append(`<option value="${paper.id}">${paper.name}</option>`);
                });
                $.each(item.laminations, function(index, lamination){
                    $('#lamination').append(`<option value="${lamination.id}">${lamination.name}</option>`);
                });
                // $(`#previous_balance`).val(data?.closing_balance);


                var amount = parseInt(data?.openingRate) * parseInt(data?.baseUnits);
            })
            .catch(error => console.error('Error:', error));
        }


    // Add item to table
    // document.getElementById('addItemBtn').addEventListener('click', function() {
    let m = 1;
    function addItemBtn(i) {
        var qty = $(`#qtyInput`).val();

        var work_name = $(`#work_name`).val();
        // var work_name_text = $('#work_name option:selected').text();
        var work_name_text ='';
        if(work_name) var work_name_text = $('#work_name option:selected').text();
        
        var work_type = $(`#work_type`).val();
        if(!work_type){alert('Select an Work Type');return;}
        var work_type_text = $('#work_type option:selected').text();
        
        var size = $(`#size`).val();
        var size_text = size?$('#size option:selected').text():'';
        
        var color = $(`#color`).val();
        var color_text = color?$('#color option:selected').text():'';
        
        var weight = $(`#weight`).val();
        var weight_text = weight?$('#weight option:selected').text():'';
        
        var paper = $(`#paper`).val();
        var paper_text = paper?$('#paper option:selected').text():'';
        
        var lamination = $(`#lamination`).val();
        var lamination_text = lamination?$('#lamination option:selected').text():'';

        var note = $(`#note`).val();
        var rate = $(`#rateInput`).val();
        // var amount = $(`#amount`).val();

        let amount = (qty * rate);
        // alert(note+lamination_text);
        // return;

        let row = `
            <tr>
                <td>${qty} <input name="item[${m}][quantity]" class="form-control tableQty" type="hidden" value="${qty}"></td>
                <td>${work_name_text} <input type="hidden" name="item[${m}][work_name]" value="${work_name}"></td>
                <td>${work_type_text} <input type="hidden" name="item[${m}][work_type]" value="${work_type}"></td>
                <td>${size_text} <input type="hidden" name="item[${m}][size]" value="${size}"></td>
                <td>${color_text} <input type="hidden" name="item[${m}][color]" value="${color}"></td>
                <td>${weight_text} <input type="hidden" name="item[${m}][weight]" value="${weight}"></td>
                <td>${paper_text} <input type="hidden" name="item[${m}][paper]" value="${paper}"></td>
                <td>${lamination_text} <input type="hidden" name="item[${m}][lamination]" value="${lamination}"></td>
                <td>${note} <input type="hidden" name="item[${m}][note]" value="${note}"></td>
                <td>${rate} <input type="hidden" name="item[${m}][rate]" class="form-control tableRate" value="${rate}"></td>
                <td>${amount.toFixed(2)} <input type="hidden" name="item[${m}][amount]" class="form-control tableamount" value="${amount.toFixed(2)}"></td>
                <td><button class="btn btn-danger btn-sm removeBtn">X</button></td>
            </tr>`;

        document.querySelector('#itemTable tbody').insertAdjacentHTML('beforeend', row);

        updateGrandTotal();

        // Reset inputs
        // itemSelect.value = null;
        let qtyint='';
        // $('#itemSelect').val(null).trigger('change');
        if(i==1){
            // $('#paid_amount').focus();
            $('#extra_discount').focus();
        } else{
            // $('#itemSelect').select2('open');
            $('#qtyInput').focus();
            qtyint=1;
        }

        $(`#qtyInput`).val('');
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
        document.querySelectorAll('.tableamount').forEach(t => sum += parseFloat(t.value));
        document.getElementById('payable_amount').value = sum.toFixed(2);
        document.getElementById('grand_total').value = sum.toFixed(2);
    }
</script>

@endsection