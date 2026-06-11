@extends('layouts/contentNavbarLayout')

@section('title', 'Update Sales Order')

@section('content')

    

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('Update Sales Order') }}</span>
                    <a href="/" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ url('sales/update-action')}}">
                        @csrf

                        <input type="hidden" class="form-control form-control-sm " id="basic-default-date"
                                                name="id" value="{{$data[0]['sales_id']}}" placeholder="" />

                        <div class="mb-5 p-4 border border-secondary">
                            <div class="row">
                                <div class="col-12 col-sm-6 mb-2">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{date('Y-m-d', strtotime($data[0]['date']))}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-sm-6 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Ledger
                                            Name</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control form-control-sm js-example-basic-single"
                                                id="basic-default-ledger" name="ledger"
                                                placeholder="Enter Ledger Type" required="required">
                                                <option value="">Select Ledger Type</option>
                                                @foreach ($ledgerTypes as $key => $ledgerItems)
                                                    @foreach ($ledgerItems->Ledger as $item)
                                                        <option value="{{ $item->id }}"  @if($data[0]['ledger_id'] == $item->id) selected @endif>{{ $item->name }}</option>
                                                    @endforeach
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
                                                    aria-describedby="basic-default-voucherType" required="required">
                                                    <option value="">Select Voucher Type</option>
                                                    {{--@foreach ($voucherTypes as $key => $item)
                                                        <option value="{{ $key }}" @if($data[0]['voucher_type_id'] == $key) selected @endif>{{ $item }}</option>
                                                    @endforeach--}}
                                                    <option value="{{ $data[0]['voucher_type_id'] }}" selected>{{ $data[0]['voucher_type'] }}</option>
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
                                                    <option value="{{ $key }}" @if($data[0]['cost_center_id'] == $key) selected @endif>{{ $item }}</option>
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
                                                value="{{$data[0]['voucher_no']}}"
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
                                                name="dueOn" value="{{date('Y-m-d', strtotime($data[0]['due_on']))}}" placeholder="Enter Due On"
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
                                            >Sub Dealer Name<!--Mode--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                    name="mode" placeholder="Enter Sub Dealer Name" value="{{$data[0]['mode_termsofpayment']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-otherReferences">Sub Dealer Address <!--Other Reference(s)--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-otherReferences"
                                                    name="otherReferences" placeholder="Enter Sub Dealer Address" value="{{$data[0]['other_references']}}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-tod">Mobile <!--Terms of Delivery--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-tod"
                                                    name="tod" placeholder="Enter Sub Dealer Mobile" value="{{$data[0]['termsofdelivery']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-despatch_doc_no"
                                            >Sales Executive <!--Doc No.--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-despatch_doc_no"
                                                    name="despatch_doc_no" placeholder="Enter Sales Executive" value="{{$data[0]['despatch_docno']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-motor_vehicle_no">Motor Vehicle No.</label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-motor_vehicle_no"
                                                    name="motor_vehicle_no" placeholder="Enter Motor Vehicle No." value="{{$data[0]['motor_vehicle_no']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                   

                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-destination">Mobile<!--Destination--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-destination"
                                                    name="destination" placeholder="Enter Sales Executive Mobile" value="{{$data[0]['destination']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-despatch_through">Zonal/Regional Sales Manager<!--Through--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-despatch_through"
                                                    name="despatch_through" placeholder="Enter Zonal/Regional Sales Manager" value="{{$data[0]['despatched_through']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-2">
                                        <div class="row">
                                            <label class="col-5 col-form-label" for="basic-default-bill_of_ladding"
                                            >Mobile<!--Bill of Ladding/ LR-PR No.--></label>
                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-bill_of_ladding"
                                                    name="bill_of_ladding" placeholder="Enter Zonal/Regional Sales Manager Mobile" value="{{$data[0]['bill_lading_lr_rr_no']}}"/>
                                            </div>
                                        </div>
                                    </div>
                                    

                                    <div class="col-12 mb-2">
                                        <div class="row">
                                            <label class="col-3 col-form-label" for="basic-default-motor_vehicle_no">Narration/Remarks</label>
                                            <div class="col-9">
                                                <textarea name="narration_remarks" class="form-control form-control-sm" value="{{$data[0]['narration']}}">{{$data[0]['narration']}}</textarea>
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

                            @php 
                                $info = $data[0];
                                $totalItem = sizeof($info['sales_items']);
                            @endphp
                
                            @foreach($info['sales_items'] as $i => $sales_item)

                                <input name="salesID" value="{{$sales_item['item_id']}}" type="hidden" />                     

                                <div class="mt-2 mb-2 pb-2 border border-secondary">
                                    <div class="row px-4 pt-4">
                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="row">
                                                <label class="col-sm-3 col-form-label"
                                                    for="basic-default-name">Name</label>
                                                <div class="col-sm-9">
                                                    <select onchange="fetchItemDetails({{$i}})" type="text" class="form-control form-control-sm items js-example-basic-single" id="items{{$i}}"
                                                        name="item[{{$i}}][name]" placeholder="Enter Name" required="required" >
                                                        <option value="">Select Item</option>
                                                        @forelse($stockItem as $key => $data)
                                                            @foreach ($data->StockItem as $key1 => $item)
                                                                <option value="{{ $item->id }}" @if($item->id == $sales_item['item_id']) selected @endif >{{ $item->name }}{{!empty($item->alias)?' ('.$item->alias.')':''}}</option>
                                                            @endforeach
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
                                                    <textarea type="text" class="form-control form-control-sm " id="basic-default-description" name="item[{{$i}}][description]"
                                                        placeholder="Enter Description" rows="1" value="{{$sales_item['item_description']}}">{{$sales_item['item_description']}}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="row">
                                                <label class="col-3 col-form-label"
                                                    for="basic-default-quantity">Quantity</label>
                                                <div class="col-9">
                                                    <input id="quantity{{$i}}" onchange="getAmount({{$i}})" type="text" class="form-control form-control-sm "
                                                        name="item[{{$i}}][quantity]"
                                                        placeholder="Enter Quantity" required="required" value="{{$sales_item['quantity']}}" />
                                                </div>
                                            </div>    
                                        </div>
                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="row">
                                                <label class="col-3 col-form-label" for="basic-default-unit">Unit</label>
                                                <div class="col-9">
                                                    <select id="units{{$i}}" type="text" class="form-control form-control-sm " id="basic-default-unit"
                                                        name="item[{{$i}}][unit]" placeholder="Enter Unit" required="required">
                                                        <option value="">Select Unit</option>
                                                        @foreach($allUnits as $key => $item)
                                                        <option value="{{$key}}" @if($sales_item['unit_id'] == $key) selected @endif>{{$item}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-6 mb-2">
                                            <div class="row">
                                                <label class="col-3 col-form-label" for="basic-default-rate">Rate</label>
                                                <div class="col-9">
                                                    <input id="rate{{$i}}" type="text" class="form-control form-control-sm " readonly="readonly" 
                                                        name="item[{{$i}}][rate]" placeholder="Enter Rate" required="required" value="{{$sales_item['rate']}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 mb-2"> 
                                            <div class="row">   
                                                <label class="col-3 col-form-label" for="basic-default-amount">AMOUNT</label>
                                                <div class="col-9">
                                                    <input id="amount{{$i}}" type="text" readonly="readonly" class="form-control form-control-sm "
                                                        name="item[{{$i}}][amount]" placeholder="(Qty x Rate)" required="required" value="{{$sales_item['amount']}}"/>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-12 col-sm-4 mb-2">
                                            <div class="row">
                                                <label class="col-6 col-form-label"
                                                    for="basic-default-discount">Discount(%)</label>
                                                <div class="col-6">
                                                    <input onchange="discount({{$i}})" type="text" value="0" class="form-control form-control-sm "
                                                        id="basic-default-discount{{$i}}" name="item[{{$i}}][discount]"
                                                        placeholder="Enter Discount %" value="{{$sales_item['discount_percent']}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4 mb-2">
                                            <div class="row">
                                                <label class="col-6 col-form-label"
                                                    for="basic-default-damount0">DISCOUNT Amount</label>
                                                <div class="col-6">
                                                    <input type="number" name="item[{{$i}}][damount]" value="0" class="form-control form-control-sm "
                                                        step=".001"id="basic-default-damount{{$i}}" readonly="readonly" 
                                                        placeholder="Enter Amount" value="{{$sales_item['discount_amount']}}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4 mb-2">
                                            <div class="row">
                                                <label class="col-6 col-form-label"
                                                    for="basic-default-amountwithtax{{$i}}">Total Amount</label>
                                                <div class="col-6">
                                                    <input type="number" class="form-control form-control-sm total" readonly="readonly"  
                                                        id="basic-default-amountwithtax{{$i}}" name="item[{{$i}}][amountwithtax]"
                                                        step=".001" placeholder="Enter Amount with Tax" required="required" value="{{$sales_item['amount']}}"/>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    @if($info['voucher_type_id'] == 3)

                                        

                                        <div class="px-4">
                                            <div class="row">
                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-discount">Customized Size</label>
                                                        <div class="col-6">
                                                            {{-- item[{{$i}}][customize_size]  --}}
                                                            <input type="text" class="form-control form-control-sm "
                                                                name="item[{{$i}}][customize_size]"
                                                                id="basic-default-discount" value="{{$sales_item['extraField'][0]['customized_size'] ?? ''}}"
                                                                placeholder="Enter Customized Size" />
                                                        </div>
                                                    </div>    
                                                </div>
                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-amount">CNC B/S</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm " value="{{$sales_item['extraField'][0]['cnc_bs'] ?? '' }}"
                                                                step=".001"id="basic-default-amount" name="item[{{$i}}][cnc_bs]"
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
                                                                id="basic-default-amountwithtax" name="item[{{$i}}][cnc_no]"
                                                                step=".001" placeholder="Enter CNC NO"  value="{{$sales_item['extraField'][0]['cnc_no']  ?? '' }}" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-discount">CNC Colour</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm "
                                                                id="basic-default-discount" name="item[{{$i}}][cnc_colour]"
                                                                placeholder="Enter CNC Colour" value="{{$sales_item['extraField'][0]['cnc_colour']  ?? '' }}" />
                                                        </div>
                                                    </div>    
                                                </div>
                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-amount">Lacquer Colour</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm "
                                                                step=".001"id="basic-default-amount" name="item[{{$i}}][lacquer_colour]"
                                                                placeholder="Enter Lacquer Colour" value="{{$sales_item['extraField'][0]['lacquer_color']  ?? '' }}" />
                                                        </div>
                                                    </div>    
                                                </div>
                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-amountwithtax">Handel Position</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm "
                                                                id="basic-default-amountwithtax" name="item[{{$i}}][handle_position]"
                                                                step=".001" placeholder="Enter Handel Position" value="{{$sales_item['extraField'][0]['handle_position']  ?? '' }}" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4 mb-2">
                                                    <div class="row">
                                                        <label class="col-6 col-form-label"
                                                            for="basic-default-discount">Under Clear</label>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control form-control-sm "
                                                                id="basic-default-discount" name="item[{{$i}}][under_clear]"
                                                                placeholder="Enter Under Clear" value="{{$sales_item['extraField'][0]['under_clear']  ?? '' }}"/>
                                                        </div>
                                                    </div>    
                                                </div>
                                                
                                            </div>
                                        </div>

                                    @else

                                        <div class="extraItemField extraItemField_0" data-slid="{{$i}}"></div>

                                    @endif

                                </div>

                            @endforeach
                                                           
                            <div id="dynamic_field"></div>

                            <div class="row">
                                <div class="col-5 col-sm-5 mt-2">
                                    <button id="newItemOnEnterForEdit" type="button" onClick="newItemInfo({{$totalItem}})"
                                        class=" btn btn-sm btn-secondary mb-1">Add New
                                        Item</button>
                                </div>
                                <div class="col-7 col-sm-7 mt-2">
                                    <div class="float-end btn btn-outline-info btn-sm">GRAND TOTAL  :  <span class="gran_total text-left" style="min-width: 50px">
                                        @php $sales_item['amount'] += $sales_item['amount'] ; @endphp
                                        {{ $sales_item['amount'] }}
                                    </span></div>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary active d-table m-0 m-auto" onclick="return confirm('Are you want to proceed?')">Update</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
                

    <script>

        function newItemInfo(i){
            // var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            newItemOnSalesOrder(i);
        };

        function salesItemsVariousInfo(){
            var voucherType = $('#basic-default-voucherType').val();
            if(voucherType == 3) {
                $(".extraItemField" ).each(function( index ) {
                    var i = $(this).data("slid");
                    salesItemsVariousInfoIND(i);
                });
            } else {
                $('.extraItemField').html('');
            }
            voucherNo();
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
            console.log('item : '.itemId,'  #items',i);
            var url = "{{url('/stockitem/getItems/')}}/"+itemId;

            const data = fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Data from api : ',data);
                $(`#rate${i}`).val(data?.standard_price);
                $(`#units${i}`).val(data?.baseUnits);
                // var amount = parseInt(data?.standard_price) * parseInt(data?.baseUnits);
                var amount = parseInt(data?.standard_price);
                $(`#amount${i}`).val(amount);
                discount(i);
            })
            .catch(error => console.error('Error:', error));
        }
        
        async function voucherNo() {
            $(`#voucher_no`).val('');
            
            var voucherType = $(`#basic-default-voucherType`).val();
            var url = "{{url('/voucher-no/')}}/"+voucherType;

            const data = fetch(url)
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
    
        let p = 0;
        function newItemOnSalesOrder(i) {
            console.log(i);
            i += p;
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
                                    <select onchange="fetchItemDetails(${i})" type="text" class="form-control form-control-sm items js-example-basic-single" id="items${i}"
                                        name="item[${i}][name]" placeholder="Enter Name" required="required">
                                        <option value="">Select Item</option>
                                        @forelse($stockItem as $key => $data)
                                            @foreach ($data->StockItem as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}{{!empty($item->alias)?' ('.$item->alias.')':''}}</option>
                                            @endforeach
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
                                        placeholder="Enter Quantity" required="required"/>
                                </div>
                            </div>    
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <div class="row">
                                <label class="col-3 col-form-label" for="basic-default-unit">Unit</label>
                                <div class="col-9">
                                    <select id="units${i}" type="text" class="form-control form-control-sm " id="basic-default-unit"
                                        name="item[${i}][unit]" placeholder="Enter Unit" required="required">
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
                                        name="item[${i}][rate]" placeholder="Enter Rate" required="required" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mb-2"> 
                            <div class="row">   
                                <label class="col-3 col-form-label" for="basic-default-amount">AMOUNT</label>
                                <div class="col-9">
                                    <input id="amount${i}" type="text" readonly="readonly" class="form-control form-control-sm "
                                        name="item[${i}][amount]" placeholder="(Qty x Rate)" required="required" />
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
                                        step=".001" placeholder="Enter Amount with Tax" required="required"/>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="extraItemField extraItemField_${i}" data-slid="${i}"></div>
                </div>
                `;
            $('#dynamic_field').append(salesNewItemForm);
            salesItemsVariousInfoIND(i);
            p++;
            $('.js-example-basic-single').select2();
        }

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
        }
    </script>

@endsection