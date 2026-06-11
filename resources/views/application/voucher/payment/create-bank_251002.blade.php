@extends('layouts/contentNavbarLayout')

@section('title', 'New Bank Payment')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex items-center justify-content-between">
                    <span>{{ __('New Bank Payment') }}</span>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary float-left btn-sm">
                        Go Back
                    </a>
                </div>
               <div class="card-body">
                    <form method="POST" action="{{route('bank.voucher.create')}}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="voucherType" value="2"/>
                        <div class="p-3 border border-secondary">
                            <div class="row mb-1">
                                <div class="col-6 col-sm-6">
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
                                <div class="col-6 col-sm-6">
                                   <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="basic-default-ledger">Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" readonly class="form-control form-control-sm " id="basic-default-date"
                                                name="date" value="{{date('Y-m-d')}}" placeholder="Enter Date" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="credit_head">Credit A/C</label>
                                        <div class="col-sm-8">
                                            <select type="text" id="credit_head" class="form-control form-control-sm   js-example-basic-single"
                                                name="credit_head" 
                                                aria-label="Enter Cost Center"
                                                aria-describedby="credit_head" required="required">
                                                <option value="">--Select Credit A/C--</option>
                                                @foreach ($bankLedgers as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label"
                                            for="debit_head">Debit A/C</label>
                                        <div class="col-sm-8">
                                            <select type="text" class="form-control form-control-sm js-example-basic-single"
                                                id="debit_head" name="debit_head" required="required">
                                                <option value="">--Select Debit A/C--</option>
                                                @foreach ($partyLedgers as $key => $item)
                                                    <option value="{{ $key }}">{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="check_no">Check No</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm " id="check_no" name="check_no" placeholder="Enter Check No" required="required"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="check_date">Check Date</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control form-control-sm " id="check_date" name="check_date" value="{{date('Y-m-d')}}" placeholder="Enter Check Date" required="required"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="dueOn">Amount</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control form-control-sm " id="basic-default-mode"
                                                    name="amount" placeholder="Enter Amount" required="required"/>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6 col-s-12">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label" for="attachment">Attachment</label>
                                        <div class="col-sm-8">
                                            <input aria-disabled="true" type="file" id="attachment"
                                                class="form-control form-control-sm   " name="attachment" aria-describedby="attachment" required="required"/>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row mb-1">
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