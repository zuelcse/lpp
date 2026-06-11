@extends('layouts/contentNavbarLayout')
@section('title', 'New Cash Receive')


@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Cash Receive') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm float-left">
                        Go Back
                    </a>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{route('voucher.create')}}">
                        @csrf
                        <input type="hidden" name="voucherType" value="{{$voucherType}}"/>
                        <div class="mb-1 p-2 border border-secondary">
                            <div class="row px-2 pt-2">
                                <div class="col-12 col-sm-4">
                                    <div class="row">
                                        <label class="col-sm-5 col-form-label">Voucher No.</label>
                                        <div class="col-sm-7">
                                            <span class="form-control form-control-sm bg-secondary-subtle">{{ $voucher_no }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="date">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm " id="date"
                                                name="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-1">
                            <div class="mt-2 border border-secondary" id="dynamic_field">
                                <div class="row px-4 pt-2">
                                    <div class="col-12 col-sm-3 mb-1">
                                        <div class="row">
                                            <label class="col-4 col-sm-5 col-form-label" for="item0drname">Debit A/C</label>
                                            <div class="col-8 col-sm-7">
                                                <select onchange="fetchItemDetails(0)" class="form-control form-control-sm js-example-basic-single" id="item0drname"
                                                    name="item[0][drname]" required="required" >
                                                    <!-- <option value="">--Select--</option> -->
                                                    @foreach ($drLedgers as $key => $item)
                                                        <option value="{{ $key }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mb-1">
                                        <div class="row">
                                            <label class="col-4 col-sm-5 col-form-label" for="item0crname">Credit A/C</label>
                                            <div class="col-8 col-sm-7">
                                                <select class="form-control form-control-sm js-example-basic-single"
                                                    id="item0crname" name="item[0][crname]"
                                                    placeholder="Enter Ledger Type" required="required">
                                                    <option value="">--Select--</option>
                                                    @foreach ($crLedgers as $key => $item)
                                                        <option value="{{ $key }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mb-1">
                                        <div class="row">
                                            <label class="col-4 col-sm-4 col-form-label" for="item0amount">Amount</label>
                                            <div class="col-8 col-sm-8">
                                                <input type="text" onkeyup="getAmount(0)" class="form-control form-control-sm amount" id="amount0" name="item[0][amount]" placeholder="Enter Amount" required="required"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-3 mb-1">
                                        <div class="row">
                                            <label class="col-4 col-sm-3 col-form-label" for="dueOn">Note</label>
                                            <div class="col-8 col-sm-9">
                                                <input type="text" class="form-control form-control-sm " id="basic-default-mode" name="item[0][note]" placeholder="Enter Note"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div ></div>
                            </div>


                            <div class="row">
                                <div class="col-4 col-sm-6 mt-2">
                                    <button id="newItemOnEnter" type="button" class=" btn btn-sm btn-secondary mb-1">Add More</button>
                                </div>
                                <div class="col-8 col-sm-6 mt-2">
                                    <div class="float-end btn btn-outline-info btn-sm">GRAND TOTAL  :  <span class="gran_total text-left" style="min-width: 50px">0</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="mt-1 mb-1 border border-secondary p-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <label class="col-12 col-sm-2 col-form-label" for="basic-default-motor_vehicle_no">Narration/Remarks</label>
                                            <div class="col-12 col-sm-10">
                                                <textarea name="narration_remarks" class="form-control form-control-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-1">
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
            return 0; //off
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
            var gtotal=0;
            $(".amount").each(function( index ) {
                var amt = $(this).val() == '' ? 0 : $(this).val();
                gtotal += parseFloat(amt);
            });
            
            $(".gran_total").html(gtotal);
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
            var amount = parseFloat($(`#amount${i}`).val());
            var totalAmount = amount;

            var total=parseFloat(0);
            $("#amount" ).each(function( index ) {
                total += parseFloat($(this).val());
            });

            $(`.gran_total`).html(total);
        }
    
        let i = 1;
        function newItemOnSalesOrder() {
            let salesNewItemForm =
                `<div id="key${i}" class="row px-4 pt-2">
                    <div class="col-12 col-sm-3 mb-1">
                        <div class="row">
                            <label class="col-4 col-sm-5 col-form-label" for="item${i}drname">Debit A/C</label>
                            <div class="col-8 col-sm-7">
                                <select onchange="fetchItemDetails(${i})" class="form-control form-control-sm js-example-basic-single" id="item${i}drname" name="item[${i}][drname]" required="required">
                                    <!--<option value="">--Select--</option>-->
                                    @foreach ($drLedgers as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mb-1">
                        <div class="row">
                            <label class="col-4 col-sm-5 col-form-label" for="item${i}crname">Credit A/C</label>
                            <div class="col-8 col-sm-7">
                                <select onchange="fetchItemDetails(${i})" class="form-control form-control-sm js-example-basic-single" id="item${i}crname" name="item[${i}][crname]" required="required">
                                    <option value="">--Select--</option>
                                        @foreach ($crLedgers as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mb-1">
                        <div class="row">
                            <label class="col-4 col-form-label" for="item${i}amount">Amount</label>
                            <div class="col-8">
                                <input id="amount${i}" onkeyup="getAmount(${i})" type="text" class="form-control form-control-sm amount" name="item[${i}][amount]" placeholder="Enter Amount" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-3 mb-1"> 
                        <div class="row">   
                            <label class="col-3 col-form-label" for="item${i}note">Note</label>
                            <div class="col-7">
                                <input id="item${i}note" type="text" class="form-control form-control-sm "
                                    name="item[${i}][note]" placeholder="Enter Note"/>
                            </div>
                            <div class="col-2">
                                <span title="Remove the row." onclick="removeItemOnSalesOrder(${i})" class="text-danger col-form-label cursor-pointer float-end">X</span>
                            </div>
                        </div>
                    </div>
                </div>
                `;
            $('#dynamic_field').append(salesNewItemForm);
            $('.js-example-basic-single').select2();
            
            i++;
        }

        function removeItemOnSalesOrder(x) {
            $('#key'+x+'').remove();
            getAmount(x);
        }
    </script>

@endsection