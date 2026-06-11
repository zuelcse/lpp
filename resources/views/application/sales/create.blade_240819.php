@extends('layouts/contentNavbarLayout')

@section('title', 'New Sales Order')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Sales Order') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('sales.voucher.create')}}">
                        @csrf

                        <div class="mb-5 p-4 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Ledger
                                            Name</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control form-control-sm "
                                                id="basic-default-ledger" name="ledger"
                                                placeholder="Enter Ledger Type">
                                                <option value="">Select Ledger Type</option>
                                                @foreach ($ledgerTypes as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-voucherType">Voucher Type</label>
                                        <div class="col-sm-8">
                                            <div class="input-group input-group-merge">
                                                <select onchange="salesItemsVariousInfo()" type="text" id="basic-default-voucherType"
                                                    class="form-control form-control-sm " name="voucherType"
                                                    placeholder="Enter Voucher Type"
                                                    aria-label="voucherType"
                                                    aria-describedby="basic-default-voucherType">
                                                    <option value="">Select Voucher Type</option>
                                                    @foreach ($voucherTypes as $key => $item)
                                                        <option value="{{ $key }}">
                                                            {{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="costCenter">Cost
                                            Center</label>
                                        <div class="col-sm-8">
                                            <select type="text" id="costCenter" class="form-control form-control-sm   "
                                                name="costCenter" placeholder="Enter Cost Center"
                                                aria-label="Enter Cost Center"
                                                aria-describedby="costCenter">
                                                <option value="">Select Cost Center</option>
                                                @foreach ($costCentreType as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="voucher_no">Voucher
                                            No.</label>
                                        <div class="col-sm-8">
                                            <input aria-disabled ="true" disabled="disabled" type="text" id="voucher_no"
                                                class="form-control form-control-sm   " name="voucher_no"
                                                placeholder="Enter Tax Costing Method"
                                                aria-label="Enter Voucher No. ( Optional )"
                                                aria-describedby="voucher_no" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="dueOn">Due On</label>
                                        <div class="col-sm-8">
                                            <input type="date" id="dueOn" class="form-control form-control-sm   "
                                                name="dueOn" value="{{date('Y-m-d', strtotime(date('Y-m-d'). ' + 10 days'))}}" placeholder="Enter Due On"
                                                aria-label="Enter Due On" aria-describedby="dueOn" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-5">
                            <div class="mt-2 mb-2 fieldHeader">
                                <div class="row py-2">
                                    <span>Order Details</span>
                                </div>
                            </div>
                            <div class="mt-2 mb-2 border border-secondary p-4">
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-mode"
                                            >Mode</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                    name="mode" placeholder="Enter Mode/Terms of Payment" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-otherReferences">Other Reference(s)</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-otherReferences"
                                                    name="otherReferences" placeholder="Enter Other Reference(s)" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-tod">Terms of Delivery</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-tod"
                                                    name="tod" placeholder="Enter Terms of Delivery" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-despatch_doc_no"
                                            >Doc No.</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-despatch_doc_no"
                                                    name="despatch_doc_no" placeholder="Enter Despatch Doc No." />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-despatch_through">Through</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-despatch_through"
                                                    name="despatch_through" placeholder="Enter Despatch Through" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-destination">Destination</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-destination"
                                                    name="destination" placeholder="Enter Destination" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-bill_of_ladding"
                                            >Bill of Ladding/ LR-PR No.</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-bill_of_ladding"
                                                    name="bill_of_ladding" placeholder="Enter Bill of Ladding/ LR-PR No." />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-motor_vehicle_no">Motor Vehicle No.</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-motor_vehicle_no"
                                                    name="motor_vehicle_no" placeholder="Enter Motor Vehicle No." />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-form-label" for="basic-default-motor_vehicle_no">Narration/Remarks</label>
                                            <div class="col-9">
                                                <textarea name="narration_remarks" class="form-control form-control-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="">

                            <div class="mt-2 mb-2 fieldHeader">
                                <div class="row py-2">
                                    <span>Items</span>
                                </div>
                            </div>

                            <div class="mt-2 mb-2 pb-2 border border-secondary">
                                <div class="row px-4 pt-4">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label"
                                                for="basic-default-name">Name</label>
                                            <div class="col-sm-9">
                                                <select onchange="fetchItemDetails(0)" type="text" class="form-control form-control-sm items" id="items0"
                                                    name="item[0][name]" placeholder="Enter Name">
                                                    <option value="">Select Item</option>
                                                    @forelse($stockItem as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                    @empty
                                                    <option value="">No Items Found</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-sm-3 col-form-label"
                                                for="basic-default-description">Description</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" class="form-control form-control-sm " id="basic-default-description" name="item[0][description]"
                                                    placeholder="Enter Description" rows="1"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-form-label"
                                                for="basic-default-quantity">Quantity</label>
                                            <div class="col-9">
                                                <input id="quantity0" onchange="getAmount(0)" type="text" class="form-control form-control-sm "
                                                    name="item[0][quantity]"
                                                    placeholder="Enter Quantity" />
                                            </div>
                                        </div>    
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-form-label" for="basic-default-unit">Unit</label>
                                            <div class="col-9">
                                                <select id="units0" type="text" class="form-control form-control-sm " id="basic-default-unit"
                                                    name="item[0][unit]" placeholder="Enter Unit">
                                                    <option value="">Select Unit</option>
                                                    @foreach($allUnits as $key => $item)
                                                    <option value="{{$key}}">{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-form-label" for="basic-default-rate">Rate</label>
                                            <div class="col-9">
                                                <input id="rate0" type="text" class="form-control form-control-sm " readonly="readonly" 
                                                    name="item[0][rate]" placeholder="Enter Rate" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2"> 
                                        <div class="row">   
                                            <label class="col-3 col-form-label" for="basic-default-amount">AMOUNT</label>
                                            <div class="col-9">
                                                <input id="amount0" type="text" readonly="readonly" class="form-control form-control-sm "
                                                    name="item[0][amount]" placeholder="(Qty x Rate)" />
                                            </div>
                                        </div>
                                    </div>

                                   
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-6 col-form-label"
                                                for="basic-default-discount">Discount(%)</label>
                                            <div class="col-6">
                                                <input onchange="discount(0)" type="text" value="0" class="form-control form-control-sm "
                                                    id="basic-default-discount0" name="item[0][discount]"
                                                    placeholder="Enter Discount %" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-6 col-form-label"
                                                for="basic-default-damount0">DISCOUNT Amount</label>
                                            <div class="col-6">
                                                <input type="number" name="item[0][damount]" value="0" class="form-control form-control-sm "
                                                    step=".001"id="basic-default-damount0" readonly="readonly" 
                                                    placeholder="Enter Amount" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4 mb-2">
                                        <div class="row">
                                            <label class="col-6 col-form-label"
                                                for="basic-default-amountwithtax0">Total Amount</label>
                                            <div class="col-6">
                                                <input type="number" class="form-control form-control-sm total" readonly="readonly"  
                                                    id="basic-default-amountwithtax0" name="item[0][amountwithtax]"
                                                    step=".001" placeholder="Enter Amount with Tax" />
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="extraItemField extraItemField_0" data-slid="0"></div>

                            </div>

                            <div id="dynamic_field"></div>

                            <div class="row">
                                <div class="col-5 col-sm-5 mt-2">
                                    <button id="newItemOnEnter" type="button"
                                        class=" btn btn-sm btn-secondary mb-1">Add New
                                        Item</button>
                                </div>
                                <div class="col-7 col-sm-7 mt-2">
                                    <div class="float-end btn btn-outline-info btn-sm">GRAND TOTAL  :  <span class="gran_total text-left" style="min-width: 50px">0</span></div>
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
        function salesItemsVariousInfo(){
            var voucherType = $('#basic-default-voucherType').val();
            if(voucherType == 3) {
                $(".extraItemField" ).each(function( index ) {
                    var i =$(this).data("slid");
                    salesItemsVariousInfoIND(i);
                });
            } else {
                $('.extraItemField').html('');
            }
        }
        function salesItemsVariousInfoIND(i=0) {
            var voucherType = $('#basic-default-voucherType').val();
            if(voucherType == 3) {
                html = `<div class="px-4">
                            <div class="row">
                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-discount">Customized Size</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                id="basic-default-discount" name="item[${i}][customize_size]"
                                                placeholder="Enter Customized Size" />
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-amount">CNC B/S</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                step=".001"id="basic-default-amount" name="item[${i}][cnc_bs]"
                                                placeholder="Enter CNC B/S" />
                                        </div>
                                    </div>   
                                </div>
                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-amountwithtax">CNC NO</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                id="basic-default-amountwithtax" name="item[${i}][cnc_no]"
                                                step=".001" placeholder="Enter CNC NO" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-discount">CNC Colour</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                id="basic-default-discount" name="item[${i}][cnc_colour]"
                                                placeholder="Enter CNC Colour" />
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-amount">Lacquer Colour</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                step=".001"id="basic-default-amount" name="item[${i}][lacquer_colour]"
                                                placeholder="Enter Lacquer Colour" />
                                        </div>
                                    </div>    
                                </div>
                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-amountwithtax">Handel Position</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                id="basic-default-amountwithtax" name="item[${i}][handle_position]"
                                                step=".001" placeholder="Enter Handel Position" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-6 col-form-label"
                                            for="basic-default-discount">Under Clear</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control form-control-sm "
                                                id="basic-default-discount" name="item[${i}][under_clear]"
                                                placeholder="Enter Under Clear" />
                                        </div>
                                    </div>    
                                </div>
                                
                            </div>
                    </div>`;

                $('.extraItemField_'+i).html(html);

            }
  
        }

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
                $(`#rate${i}`).val(data?.openingRate);
                $(`#units${i}`).val(data?.baseUnits);
                var amount = parseInt(data?.openingRate) * parseInt(data?.baseUnits);
                $(`#amount${i}`).val(amount);
                discount(i);
            })
            .catch(error => console.error('Error:', error));

            

        }

        function getAmount(i) {
            var openingRate = $(`#rate${i}`).val();
            var quantity = $(`#quantity${i}`).val();
            if(quantity == '') quantity = 1;
            var amount = parseInt(openingRate) * parseInt(quantity);
            $(`#amount${i}`).val(amount);
            discount(i);
        }

        function discount(i) {

            var discount  = parseInt($(`#basic-default-discount${i}`).val());
            var amount = parseInt($(`#amount${i}`).val());
            var discountAmount = parseInt((discount/100) * amount);
            console.log(amount,' - ',discountAmount);
            var totalAmount = amount - discountAmount;
            $(`#basic-default-damount${i}`).val(discountAmount);
            $(`#basic-default-amountwithtax${i}`).val(totalAmount);

            var total=parseInt(0);
            $(".total" ).each(function( index ) {
                total += parseInt($(this).val());
            });
            $(`.gran_total`).html(total);
        }
    
        let i = 1;
        function newItemOnSalesOrder() {
            let salesNewItemForm =
                `<div id="key${i}" class="mt-3 pb-3 border border-secondary">
                    <div class="row mr-4" style="">
                        <div class="mx-3 my-2">
                            <button type="button" onclick="removeItemOnSalesOrder(${i})" class="btn btn-danger btn-sm mb-1 col-1 float-end" style="width: fit-content; margin-right: 40px;">X</button>
                        </div>
                    </div>
                    <div class="row px-4 pt-2">
                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-sm-3 col-form-label"
                                    for="basic-default-name">Name</label>
                                <div class="col-sm-9">
                                    <select onchange="fetchItemDetails(${i})" type="text" class="form-control form-control-sm items" id="items${i}"
                                        name="item[${i}][name]" placeholder="Enter Name">
                                        <option value="">Select Item</option>
                                        @forelse($stockItem as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @empty
                                        <option value="">No Items Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-sm-3 col-form-label"
                                    for="basic-default-description">Description</label>
                                <div class="col-sm-9">
                                    <textarea type="text" class="form-control form-control-sm " id="basic-default-description" name="item[${i}][description]"
                                        placeholder="Enter Description" rows="1"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-3 col-form-label"
                                    for="basic-default-quantity">Quantity</label>
                                <div class="col-9">
                                    <input id="quantity${i}" onchange="getAmount(${i})" type="text" class="form-control form-control-sm "
                                        name="item[${i}][quantity]"
                                        placeholder="Enter Quantity" />
                                </div>
                            </div>    
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-3 col-form-label" for="basic-default-unit">Unit</label>
                                <div class="col-9">
                                    <select id="units1" type="text" class="form-control form-control-sm " id="basic-default-unit"
                                        name="item[${i}][unit]" placeholder="Enter Unit">
                                        <option value="">Select Unit</option>
                                        @foreach($allUnits as $key => $item)
                                        <option value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-3 col-form-label" for="basic-default-rate">Rate</label>
                                <div class="col-9">
                                    <input id="rate${i}" type="text" class="form-control form-control-sm " readonly="readonly" 
                                        name="item[${i}][rate]" placeholder="Enter Rate" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mb-2"> 
                            <div class="row">   
                                <label class="col-3 col-form-label" for="basic-default-amount">AMOUNT</label>
                                <div class="col-9">
                                    <input id="amount${i}" type="text" readonly="readonly" class="form-control form-control-sm "
                                        name="item[${i}][amount]" placeholder="(Qty x Rate)" />
                                </div>
                            </div>
                        </div>

                       
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="row">
                                <label class="col-6 col-form-label"
                                    for="basic-default-discount">Discount(%)</label>
                                <div class="col-6">
                                    <input onchange="discount(${i})" type="text" value="0" class="form-control form-control-sm "
                                        id="basic-default-discount${i}" name="item[${i}][discount]"
                                        placeholder="Enter Discount %" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="row">
                                <label class="col-6 col-form-label"
                                    for="basic-default-damount1">DISCOUNT Amount</label>
                                <div class="col-6">
                                    <input type="number" name="item[${i}][damount]" value="0" class="form-control form-control-sm "
                                        step=".001"id="basic-default-damount${i}" readonly="readonly" 
                                        placeholder="Enter Amount" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 mb-2">
                            <div class="row">
                                <label class="col-6 col-form-label"
                                    for="basic-default-amountwithtax">Total Amount</label>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm total" readonly="readonly"  
                                        id="basic-default-amountwithtax${i}" name="item[${i}][amountwithtax]"
                                        step=".001" placeholder="Enter Amount with Tax" />
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
        }

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
        }
    </script>

@endsection