@extends('layouts/contentNavbarLayout')

@section('title', 'New Receipt')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Receipt') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary float-left btn-sm">
                        Go Back
                    </a>
                </div>
               <div class="card-body">
                    <form method="POST" action="{{route('receipt.voucher.create')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="p-4 border border-secondary">
                            <div class="row">
                                <div class="col-4 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="voucher_no">Voucher
                                            No.</label>
                                        <div class="col-sm-8">
                                            <input aria-disabled ="true" disabled="disabled" type="text" id="voucher_no" value="{{ $voucher_no }}" 
                                                class="form-control form-control-sm   " name="voucher_no"
                                                placeholder="Enter Tax Costing Method"
                                                aria-label="Enter Voucher No. ( Optional )"
                                                aria-describedby="voucher_no" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 col-sm-4 mb-2">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" readonly onchange="salesItemsVariousInfo()" class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 col-sm-4 mb-2">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Ledger
                                            Name</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control form-control-sm js-example-basic-single"
                                                id="basic-default-ledger" name="ledger"
                                                placeholder="Enter Ledger Type" required="required">
                                                <option value="">Select Ledger Type</option>
                                                @foreach ($ledgerTypes as $key => $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="p-3 border border-secondary">

                            <div class="row mb-3">
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="costCenter">Bank/Cash Name</label>
                                        <div class="col-sm-8">
                                            <select type="text" id="costCenter" class="form-control form-control-sm   "
                                                name="bank_chas_name" placeholder="Enter Cost Center"
                                                aria-label="Enter Cost Center"
                                                aria-describedby="costCenter" required="required">
                                                <option value="">Select Bank/Cash Name</option>
                                                @foreach ($ledgerBankCash as $key => $item)
                                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="dueOn">Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                    name="amount" placeholder="Enter Amount" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="payment_mode">Payment Mode</label>
                                        <div class="col-sm-8">
                                            <select type="text" id="payment_mode" class="form-control form-control-sm   "
                                                name="payment_mode" placeholder="Enter Cost Center"
                                                aria-label="Enter Cost Center"
                                                aria-describedby="costCenter">
                                                <option value="">Select Payment Mode</option>
                                                <option value="1">Cash</option>
                                                <option value="2">Bank Transfer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="attachment">Attachment</label>
                                        <div class="col-sm-8">
                                            <input aria-disabled="true" type="file" id="attachment"
                                                class="form-control form-control-sm   " name="attachment" aria-describedby="attachment" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="row">
                                        <label class="col-sm-2 col-form-label" for="narration">Narration</label>
                                        <div class="col-sm-10">
                                            <textarea type="text" class="form-control form-control-sm " id="narration" name="narration"
                                                placeholder="Enter Narration" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        
                        <hr>

                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary active d-table m-0 m-auto" onclick="return confirm('Are you want to proceed?')">Save</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>

@endsection