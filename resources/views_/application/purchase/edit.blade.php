@extends('layouts/contentNavbarLayout')

@section('title', 'Purchase Update')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Purchase Update') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('purchase-update', ['id' => $data[0]['sales_id']])}}">
                        @csrf
                        <div class="mb-4 p-4 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Voucher No.</label>
                                        <div class="col-sm-8">
                                            <span class="form-control form-control-sm bg-secondary-subtle">{{ $data[0]['voucher_no'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mb-2">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{ $data[0]['date'] }}" placeholder="Enter Date" required="required"/>
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
                                                    <option value="{{ $item->id }}" {{ $item->id==$data[0]['debit_head']?'selected':'' }}>{{ $item->alias.'-'.$item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">

                            <div class="mt-2 mb-2 fieldHeader">
                                <div class="row py-2">
                                    <span>Items</span>
                                </div>
                            </div>
                            @foreach ($data[0]['sales_items'] as $ikey => $ivalue)
                            <div id="key{{$ikey}}" class="mt-2 mb-2 pb-2 border border-secondary">
                                @if($ikey!=0)
                                <div class="row mr-4" style="">
                                    <div class="mx-3 my-2">
                                        <button type="button" onclick="removeItemOnSalesOrder({{$ikey}})" class="btn btn-danger btn-sm mb-1 col-1 float-end" style="width: fit-content; margin-right: 40px;">X</button>
                                    </div>
                                </div>
                                @endif
                                <div class="row px-4 pt-4">
                                    <div class="col-12 col-sm-8 mb-2">
                                        <div class="row">
                                            <input type="hidden" name="item[{{$ikey}}][id]"  value="{{$ivalue['id']}}"/>
                                            <label class="col-4 col-sm-3 col-form-label"
                                                for="basic-default-name">Name</label>
                                            <div class="col-8 col-sm-9">
                                                <select onchange="fetchItemDetails({{$ikey}})" type="text" class="form-control form-control-sm items js-example-basic-single" id="items{{$ikey}}"
                                                    name="item[{{$ikey}}][name]" required="required" >
                                                    <option value="">Select Item</option>
                                                    @forelse($stockItem as $key => $item)
                                                        <option value="{{ $item->id }}" {{ $item->id==$ivalue['item_id']?'selected':'' }}>{{ $item->name }}{{!empty($item->alias)?' ('.$item->alias.')':''}}</option>
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
                                            <label class="col-4 col-form-label" for="rate{{$ikey}}">Rate</label>
                                            <div class="col-8">
                                                <input id="rate{{$ikey}}" onkeyup ="getAmount({{$ikey}})" type="text" class="form-control form-control-sm " name="item[{{$ikey}}][rate]" placeholder="Enter Rate" autocomplete="off" value="{{$ivalue['rate']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label"
                                                for="quantity{{$ikey}}">Quantity</label>
                                            <div class="col-7">
                                                <input id="quantity{{$ikey}}" onkeyup ="getAmount({{$ikey}})" type="text" class="form-control form-control-sm "
                                                    name="item[{{$ikey}}][quantity]"
                                                    placeholder="Enter Quantity" required="required" autocomplete="off" value="{{$ivalue['purchase_quantity']}}" />
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2"> 
                                        <div class="row">   
                                            <label class="col-5 col-form-label" for="amount{{$ikey}}">AMOUNT</label>
                                            <div class="col-7">
                                                <input id="amount{{$ikey}}" onkeyup ="getRate({{$ikey}})" type="text" class="form-control form-control-sm "
                                                    name="item[{{$ikey}}][amount]" placeholder="(Qty x Rate)" required="required" autocomplete="off" value="{{$ivalue['rate']*$ivalue['purchase_quantity']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="extraItemField extraItemField_0" data-slid="0"></div> -->
                            </div>
                            @endforeach
                            <div id="dynamic_field"></div>

                            <div class="row">
                                <div class="col-12 col-sm-6 mt-2">
                                    <button id="newItemOnEnter" type="button"
                                        class=" btn btn-sm btn-secondary mb-1">Add New
                                        Item</button>
                                </div>
                                <div class="col-12 col-sm-6 mt-2">
                                    <div class="float-end btn btn-outline-info btn-sm">GRAND TOTAL  :  <span class="gran_total text-left" style="min-width: 50px">0</span></div>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="mb-4">
                            <div class="mt-2 mb-2 border border-secondary p-4">
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-4 col-form-label" for="paid_amount"
                                            >Paid</label>
                                            <div class="col-8">
                                                <input type="text" class="form-control form-control-sm " id="paid_amount"
                                                    name="paid_amount" placeholder="Enter Paid Amount" autocomplete="off" value="{{$data[0]['paid_amount']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-4 col-form-label" for="balance">Balance</label>
                                            <div class="col-8">
                                                <input type="text" class="form-control form-control-sm " id="balance"
                                                    name="balance" placeholder="Balance" readonly="readonly"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <div class="row">
                                            <label class="col-12 col-sm-3 col-form-label" for="narration_remarks">Narration/Remarks</label>
                                            <div class="col-12 col-sm-9">
                                                <textarea name="narration_remarks" id="narration_remarks" class="form-control form-control-sm">{{$data[0]['narration']}}</textarea>
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
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>     
    <script>
    $(document).ready(function() {
        discount(0);
    });
    </script>

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
            $(`.gran_total`).html(total);
        }
    
        let i = {{$ikey+1}};
        function newItemOnSalesOrder() {
            let salesNewItemForm =
                `<div id="key${i}" class="mt-3 pb-3 border border-secondary">
                    <div class="row mr-4" style="">
                        <div class="mx-3 my-2">
                            <button type="button" onclick="removeItemOnSalesOrder(${i})" class="btn btn-danger btn-sm mb-1 col-1 float-end" style="width: fit-content; margin-right: 40px;">X</button>
                        </div>
                    </div>
                    <div class="row px-4 pt-2">
                        <div class="col-12 col-sm-8 mb-2">
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="basic-default-name">Name</label>
                                <div class="col-sm-10">
                                    <select onchange="fetchItemDetails(${i})" type="text" class="form-control form-control-sm items js-example-basic-single" id="items${i}"
                                        name="item[${i}][name]" placeholder="Enter Name" required="required">
                                        <option value="">Select Item</option>
                                        @forelse($stockItem as $key => $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}{{!empty($item->alias)?' ('.$item->alias.')':''}}</option>
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
            salesItemsVariousInfoIND(i);
            i++;
            $('.js-example-basic-single').select2();
        }

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let inputs = document.querySelectorAll("input, select, textarea"); 

            inputs.forEach((input, index) => {
                input.addEventListener("keydown", function (e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // stop form submit

                        if (e.shiftKey) {
                            // Shift + Enter → go to previous field
                            let prev = inputs[index - 1];
                            if (prev) prev.focus();
                        } else {
                            // Enter → go to next field
                            let next = inputs[index + 1];
                            if (next) next.focus();
                        }
                    }else if (e.key === "Backspace" && !this.value) {
                        let prev = inputs[index - 1];
                        if (prev) prev.focus();
                    }
                });
            });
        });
    </script>


@endsection