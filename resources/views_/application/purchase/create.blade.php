@extends('layouts/contentNavbarLayout')

@section('title', 'New Purchase')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Purchase') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('purchase.voucher.create')}}">
                        @csrf
                        <input type="hidden" name="voucherType" value="{{$voucherType}}"/>
                        <div class="mb-5 p-4 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Voucher No.</label>
                                        <div class="col-sm-8">
                                            <span class="form-control form-control-sm bg-secondary-subtle">{{ $voucher_no }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mb-2">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" autofocus class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-8 col-sm-4 col-form-label"
                                            for="basic-default-ledger">Ledger
                                            Name</label>
                                        <div class="col-12 col-sm-8">
                                            <select type="text" class="form-control form-control-sm js-example-basic-single"
                                                id="basic-default-ledger" name="ledger"
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
                        </div>

                        <hr>

                        <div class="">
                            <div class="mt-2 mb-2 fieldHeader">
                                <div class="row py-2">
                                    <div class="col-6 mt-2">
                                        <span>Items</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-1 mb-1 pb-1 border border-secondary">
                                <div class="row px-4 pt-2">
                                    <div class="col-12 col-sm-12 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-sm-1 col-form-label"
                                                for="basic-default-name">Name</label>
                                            <div class="col-9 col-sm-7">
                                                <select onchange="fetchItemDetails(0)" type="text" class="form-control form-control-sm items js-example-basic-single" id="items0" name="item[0][name]" placeholder="Enter Name" required="required" >
                                                    <option value="">Select Item</option>
                                                    @forelse($stockItem as $key => $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @empty
                                                    <option value="">No Items Found</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-4">
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-4 col-form-label" for="basic-default-rate">Rate</label>
                                            <div class="col-8">
                                                <input id="rate0" onkeyup ="getAmount(0)" type="text" class="form-control form-control-sm" name="item[0][rate]" placeholder="Enter Rate" required="required" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label"
                                                for="basic-default-quantity">Quantity</label>
                                            <div class="col-7">
                                                <input id="quantity0" onkeyup ="getAmount(0)" type="text" class="form-control form-control-sm "
                                                    name="item[0][quantity]"
                                                    placeholder="Enter Quantity" required="required" autocomplete="off"/>
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2"> 
                                        <div class="row">   
                                            <label class="col-5 col-form-label" for="basic-default-amount">AMOUNT</label>
                                            <div class="col-7">
                                                <input id="amount0" onkeyup ="getRate(0)" type="text" class="form-control form-control-sm total"
                                                    name="item[0][amount]" placeholder="(Qty x Rate)" required="required" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="extraItemField extraItemField_0" data-slid="0"></div>
                            </div>

                            <div id="dynamic_field"></div>

                            <div class="row">
                                <div class="col-5 col-sm-5 mt-2">
                                    <button id="newItemOnEnter" type="button" class=" btn btn-sm btn-secondary mb-1">Add New Item</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="mb-5">
                            <div class="mt-2 mb-2 border border-secondary p-4">
                                <div class="row">
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
                                            <label class="col-8 col-sm-4 col-form-label" for="basic-default-motor_vehicle_no">Narration/Remarks</label>
                                            <div class="col-12 col-sm-8">
                                                <textarea name="narration_remarks" class="form-control form-control-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>


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

        async function fetchItemDetails(i) {

            $(`#rate${i}`).val('');
            $(`#units${i}`).val('');
            $(`#amount${i}`).val('');
            $(`#quantity${i}`).val('');
            $(`#basic-default-damount${i}`).val(0);
            $(`#basic-default-amountwithtax${i}`).val(0);
            $(`#basic-default-discount${i}`).val(0);
            
            var itemId = $(`#items${i}`).val();
            var url = "{{url('/stockitem/getItems/')}}/"+itemId;

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Data from api : ',data);
                $(`#rate${i}`).val(data?.salesRate);
                $(`#units${i}`).val(data?.baseUnits);
                // var amount = parseFloat(data?.salesRate) * parseFloat(data?.baseUnits);
                var amount = parseFloat(data?.salesRate);
                $(`#amount${i}`).val(amount);
                discount(i);
            })
            .catch(error => console.error('Error:', error));
        }
        


        async function voucherNo() {
            $(`#voucher_no`).val('');

            var date = $(`#basic-default-date`).val();
            var voucherType = $(`#basic-default-voucherType`).val();

            var url = "{{url('/voucher-no')}}";
            const data = fetch(url, {
                method: 'POST',  // Specify the HTTP method
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ date: date, voucherType: voucherType })
            })
            .then(response => response.json())
            .then(data => {
                // console.log('Data from api : ',data);
                $(`#voucher_no`).val(data.voucher_no);
            })
            // $(`#voucher_no`).val(data);
            .catch(error => console.error('Error:', error));
        }

        function getAmount(i) {
            var openingRate = $(`#rate${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            if(openingRate == '') openingRate = 1;
            if(quantity == '') quantity = 1;
            var amount = parseFloat(openingRate) * parseFloat(quantity);
            $(`#amount${i}`).val(amount);
            discount(i);
        }

        function getBalanceAmount(){
            var payable_amount = $(`#payable_amount`).val();
            var paid_amount = $(`#paid_amount`).val();

            payable_amount = payable_amount==''?0:payable_amount;
            paid_amount = paid_amount==''?0:paid_amount;

            var balance = parseFloat(paid_amount) - parseFloat(payable_amount);
            // alert(paid_amount);
            $(`#balance`).val(balance);
        }

        function getRate(i) {
            var amount = $(`#amount${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            if(amount == '') amount = 1;
            if(quantity == '') quantity = 1;
            var rate = (parseFloat(amount) / parseFloat(quantity));
            $(`#rate${i}`).val(rate);
            discount(i);
        }

        function discount(i) {
           /* var discount  = parseFloat($(`#basic-default-discount${i}`).val());
            var amount = parseFloat($(`#amount${i}`).val());
            var discountAmount = parseFloat((discount/100) * amount).toFixed(2);
            console.log(amount,' - ',discountAmount);
            var totalAmount = amount - discountAmount;
            $(`#basic-default-damount${i}`).val(discountAmount);
            $(`#basic-default-amountwithtax${i}`).val(totalAmount);
            */
            var total=parseFloat(0);
            $(".total" ).each(function( index ) {
                total += parseFloat($(this).val());
            });
            $(`#payable_amount`).val(total);
            getBalanceAmount();
        }
    
        let i = 1;
        function newItemOnSalesOrder() {
            let salesNewItemForm =
                `<div id="key${i}" class="mt-1 border border-secondary">
                    <div class="row px-4 pt-2">
                        <div class="col-12 mb-2">
                            <div class="row">
                                <label class="col-3 col-sm-1 col-form-label"
                                    for="basic-default-name">Name</label>
                                <div class="col-8 col-sm-7">
                                    <select onchange="fetchItemDetails(${i})" type="text" class="form-control form-control-sm items js-example-basic-single" id="items${i}" name="item[${i}][name]" placeholder="Enter Name" required="required">
                                        <option value="">Select Item</option>
                                        @forelse($stockItem as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                        <option value="">No Items Found</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-1 col-sm-4">
                                    <button type="button" onclick="removeItemOnSalesOrder(${i})" class="btn btn-danger btn-sm mb-1 col-1 float-end">X</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row px-4">

                        <div class="col-12 col-sm-4 mb-2">
                            <div class="row">
                                <label class="col-4 col-form-label" for="basic-default-rate">Rate</label>
                                <div class="col-8">
                                    <input id="rate${i}" onkeyup="getAmount(${i})" type="text" class="form-control form-control-sm" 
                                        name="item[${i}][rate]" placeholder="Enter Rate" autocomplete="off"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 mb-2">
                            <div class="row">
                                <label class="col-5 col-form-label"
                                    for="basic-default-quantity">Quantity</label>
                                <div class="col-7">
                                    <input id="quantity${i}" onkeyup="getAmount(${i})" type="text" class="form-control form-control-sm "
                                        name="item[${i}][quantity]"
                                        placeholder="Enter Quantity" required="required" autocomplete="off"/>
                                </div>
                            </div>    
                        </div>

                        <div class="col-12 col-sm-4 mb-2"> 
                            <div class="row">   
                                <label class="col-5 col-form-label" for="basic-default-amount">AMOUNT</label>
                                <div class="col-7">
                                    <input id="amount${i}" onkeyup="getRate(${i})" type="text" class="form-control form-control-sm total"
                                        name="item[${i}][amount]" placeholder="(Qty x Rate)" required="required" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="extraItemField extraItemField_${i}" data-slid="${i}"></div>
                </div>
                `;
            $('#dynamic_field').append(salesNewItemForm);
            i++;
            $('.js-example-basic-single').select2();
        }

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
        }
    </script>

@endsection